<?php
	include("../includes/sql.inc");
	include("../includes/envvars.inc");

	$seasonFrom = htmlspecialchars($_GET["season_from"] ?? 1);
	$seasonTo = htmlspecialchars($_GET["season_to"] ?? $currentseason);
	$region = isset($_GET["region"]) && $_GET["region"] !== '' ? htmlspecialchars($_GET["region"]) : null; // complicated to ensure $region is NULL rather than an empty string when not passed.
	$output = htmlspecialchars($_GET["output"] ?? 'json'); // expect json or csv

	$tsql="
DECLARE @seasonFrom INTEGER = ?; -- $seasonFrom
DECLARE @seasonTo INTEGER = ?; -- $seasonTo
DECLARE @region NVARCHAR(3) = ?; -- $region

SELECT
LeagueMeet.Id AS Id,
LeagueMeet.CompetitionId AS CompetitionId,
LeagueMeet.Status AS Status,
CONVERT(VARCHAR(10), LeagueMeet.Date, 23) AS Date,
LeagueMeet.Host AS Host,
(
  SELECT COUNT(Id) FROM Result where CompetitionId = LeagueMeet.CompetitionId
) AS Attendance,
LeagueMeet.Location AS Location,
LeagueMeet.Latitude AS Latitude,
LeagueMeet.Longitude AS Longitude,
LeagueMeet.RegionId AS RegionId,
Region.Name AS RegionName,
Region.Synonym AS RegionSynonym,
LeagueMeet.SeasonId AS Season,
LeagueMeet.MeetNumber AS MeetNumber
FROM LeagueMeet
INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
WHERE LeagueMeet.Status <> 4 -- Ignore cancelled (except for current season?)
AND SeasonId >= @seasonFrom 
AND SeasonId <= @seasonTo
AND (@region IS NULL OR Region.Synonym = @region)
ORDER BY Region.SortOrder, LeagueMeet.SeasonId, LeagueMeet.MeetNumber
";

	$params = array($seasonFrom, $seasonTo, $region);
	$result = sqlsrv_query($sqlConnection, $tsql, $params);
	if ($result == FALSE)
	{
		echo "query borken.";
		die(print_r(sqlsrv_errors(), true));
	}

	$league_meets = array();
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$league_meets[] = array(
			'id' => $row['Id'],
			'competition_id' => $row['CompetitionId'],
			'region_id' => $row['RegionId'],
// Will conditionally add joined tables on an 'include' queryparam later
//			'region' => [
//				'name' => $row['RegionName'],
//				'synonym' => $row['RegionSynonym'],
//			],
			'season' => $row['Season'],
			'meet_number'  => $row['MeetNumber'],
			'date' => $row['Date'],
			'status' => $row['Status'],
			'host' => $row['Host'],
			'location' => $row['Location'],
			'latitude' => $row['Latitude'], 
			'longitude' => $row['Longitude'],
			'attendance' => $row['Attendance']
		);
	}

	if ($output == 'json')
	{
		header('Content-Type: application/json');
		echo json_encode($league_meets);
	}
	else if ($output = 'csv')
	{
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="league_meets.csv"');
		
		// Open output stream, writing directly to php output stream (same as echo)
	    $outputStream = fopen('php://output', 'w');

		// Write the CSV header based on the keys of the first element in the array, then write csv lines for each element in the array
        fputcsv($outputStream, array_keys($league_meets[0]));
		foreach ($league_meets as $row) 
		{
			fputcsv($outputStream, $row);
		}

		// Close the output stream
		fclose($outputStream);
	}
?>