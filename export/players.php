<?php
	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename="players.csv"');

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
	LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = MostRecentResult.CompetitionId
	LEFT OUTER JOIN Region ON Region.Id = LeagueMeet.RegionId
	ORDER BY Player.Name ASC";

	$result = sqlsrv_query($sqlConnection, $tsql);
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	echo "player_name, player_id, region_synonym, region_name\n";
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$playerName = $row['PlayerName'];
		$playerId = $row['PlayerId'];
		$regionSynonym = $row['RegionSynonym'];
		$regionName = $row['RegionName'];

		echo "$playerName, $playerId, $regionSynonym, $regionName\n";
	}
?>

