<?php
	$playerid = htmlspecialchars($_GET["playerid"]);
	$machineid = htmlspecialchars($_GET["machineid"]);
	$competitionid = htmlspecialchars($_GET["competitionid"] ?? null);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>UK Pinball League - Score comparisons.</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php include("includes/header.inc"); ?>


<div class="panel">

	<?php 
		include("includes/envvars.inc");

		$connectionOptions = array(
			"Database" => $sqldbname,
			"Uid" => $sqluser,
			"PWD" => $sqlpassword
		);

		$conn = sqlsrv_connect($sqlserver, $connectionOptions);
		if( $conn === false ) 
		{
			echo "connection borken.";
			// die( print_r( sqlsrv_errors(), true));
		}


		// Machine info and league average score
		 $tsql= "
SELECT
Machine.Name AS 'MachineName',
AVG(Score) AS 'LeagueAverage',
COUNT(Score) AS 'LeagueCount'
FROM Score
INNER JOIN Machine ON Machine.Id = Score.MachineId
WHERE MachineId = ? -- $machineid
GROUP BY Machine.Name
";
		// Perform query with parameterised values.
		$result= sqlsrv_query($conn, $tsql, array($machineid));
		if ($result == FALSE)
		{
			echo "query borken.";
		}

		$machineRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		$machineName = $machineRow['MachineName'];
		$leagueAverage = number_format($machineRow['LeagueAverage']);
		$leagueCount = number_format($machineRow['LeagueCount']);

		// Player info and league average score
		$tsql="
SELECT
Player.Name AS 'PlayerName',
AVG(Score) AS 'PlayerAverage'
FROM Score
INNER JOIN Player on Player.Id = Score.PlayerId
WHERE MachineId = ? -- $machineid
AND PlayerId = ? -- $playerid
GROUP BY Player.Name
";
		// Perform query with parameterised values.
		$result= sqlsrv_query($conn, $tsql, array($machineid, $playerid));
		if ($result == FALSE)
		{
			echo "query borken.";
		}

		$playerRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		$playerName = $playerRow['PlayerName'];
		$playerAverage = number_format($playerRow['PlayerAverage']);
	?>

	
	<h1><?=$machineName?></h1>

	<p>Total games played : <?=$leagueCount?></p>
	<p>Average score : <?=$leagueAverage?></p>
	<p><a href="player-info.php?playerid=<?=$playerid?>" class='player-link'><?=$playerName?>'s</a> average score : <?=$playerAverage?></p>

	</div>

	<?php
		 $tsql= "
WITH RankedScores AS
(
	SELECT
	Score.Id AS 'ScoreId',
	RANK() OVER (PARTITION BY MachineId ORDER BY Score DESC) AS 'Rank'
	FROM Score
	WHERE Score.MachineId = ? -- $machineid
)
SELECT
Score.CompetitionId AS 'CompetitionId',
RankedScores.Rank AS 'ScoreRank',
Score.PlayerId AS 'PlayerId',
Player.Name AS 'PlayerName',
Score.Score AS 'Score',
Season.Year AS 'SeasonYear',
Season.SeasonNumber AS 'SeasonNumber',
Region.Name AS 'RegionName',
Region.Synonym AS 'RegionSynonym',
LeagueMeet.MeetNumber AS 'LeagueMeetNumber',
LeagueFinal.Description AS 'LeagueFinalDescription'
FROM Score
INNER JOIN RankedScores ON RankedScores.ScoreId = Score.Id
INNER JOIN Player ON Player.Id = Score.PlayerId
LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
INNER JOIN Season ON Season.Id = (COALESCE(LeagueMeet.SeasonId, LeagueFinal.SeasonId))
LEFT OUTER JOIN Region ON Region.Id = LeagueMeet.RegionId
WHERE (Rank = 1 OR Score.PlayerId = ?) -- $playerid
ORDER BY Rank, Player.Name
";

		// Perform query with parameterised values.
		$result= sqlsrv_query($conn, $tsql, array($machineid, $playerid));
		if ($result == FALSE)
		{
			echo "query borken.";
		}

		echo "<div class='panel'>";
		echo "<div class='table-holder'>";

		// Table header
		echo "<table>";

		echo "<thead>
			<tr class='white'>
				<th>Rank</th>
				<th>Player</th>
				<th class='score'>Score</th>
				<th>Event</th>
			</tr>
		</thead>";

		while ($scoreRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
		{
			$scoreCompetitionId = $scoreRow['CompetitionId'];
			$scoreRank = $scoreRow['ScoreRank'];
			$scorePlayerName = $scoreRow['PlayerName'];
			$scorePlayerId = $scoreRow['PlayerId'];
			$score = number_format($scoreRow['Score']);

			$scoreSeasonNumber = $scoreRow['SeasonNumber'];
			$scoreSeasonYear = $scoreRow['SeasonYear'];
			$scoreRegion = $scoreRow['RegionName'];
			$scoreRegionSynonym = $scoreRow['RegionSynonym'];
			$scoreLeagueMeetNumber = $scoreRow['LeagueMeetNumber'];

			$leagueFinal = $scoreRow['LeagueFinalDescription'];
			if (is_null($leagueFinal))
			{
				$event = "<a href=\"leaguemeet.php?season=$scoreSeasonNumber&region=$scoreRegionSynonym&meet=$scoreLeagueMeetNumber\" class='player-link'>$scoreSeasonYear $scoreRegion League - Meet $scoreLeagueMeetNumber</a>";
			}
			else if (str_starts_with($leagueFinal, 'League Final'))
			{
				$event = "$scoreSeasonYear $leagueFinal";
			}
			else 
			{
				$event = "$scoreSeasonYear League Final, $leagueFinal";
			}

			if ($scoreCompetitionId == $competitionid and $scorePlayerId == $playerid)
			{
				echo "<tr class='show-border'>\n";
			}
			else 
			{
				echo "<tr>\n";
			}

			echo "<td>$scoreRank</td>\n
				<td>$scorePlayerName</td>\n
				<td class='score'>$score</td>\n
				<td class=\"paddidge\">$event</td>\n
			</tr>\n";
		}

		// Close off the last table
		echo "</table>\n";
		echo "</div>";

		// Tidy up sql resources
		sqlsrv_free_stmt($result);
	?>

</div> 


<!-- footer -->
<?php include("includes/footer.inc"); ?>

</body>
</html>