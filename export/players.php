<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Export</title>
</head>
<body>

<?php
	include("../includes/sql.inc"); 

	$tsql="
	SELECT
	Player.Name AS 'PlayerName',
	Player.Id AS 'PlayerId',
	Region.Synonym AS 'RegionSynonym',
	Region.Name AS 'RegionName'
	FROM Player 
	LEFT OUTER JOIN (
		SELECT
		Result.PlayerId,
		MAX(Result.CompetitionId) AS CompetitionId
		FROM Result
		INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Result.CompetitionId -- exclude league finals
		GROUP BY Result.PlayerId
	) AS MostRecentResult ON MostRecentResult.PlayerId = Player.Id
	INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = MostRecentResult.CompetitionId
	INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
	ORDER BY Player.Name ASC";

	$result = sqlsrv_query($sqlConnection, $tsql);
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$playerName = $row['PlayerName'];
		$playerId = $row['PlayerId'];
		$regionSynonym = $row['RegionSynonym'];
		$regionName = $row['RegionName'];

		echo "$playerName, $playerId, $regionSynonym, $regionName <br>";
	}
?>

</body>
</html>