<?php
	// First, validate region and season# 
	include("includes/sql.inc"); 

	$region = htmlspecialchars($_GET["region"] ?? null);
	$season = htmlspecialchars($_GET['season'] ?? $currentseason - 1); // default to last season if not specified.
	
	$tsql="
	SELECT
	Region.Name AS 'RegionName',
	Region.Id AS 'RegionId',
	Season.Year AS 'SeasonYear',
	Season.Id AS 'SeasonId'
	FROM Season, Region
	WHERE Region.Synonym = ? -- $region
	AND Season.SeasonNumber = ? -- $season";

	$result = sqlsrv_query($sqlConnection, $tsql, array($region, $season));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$regionName = $row['RegionName'];
	$regionId = $row['RegionId'];
	$seasonYear = $row['SeasonYear'];
	$seasonId = $row['SeasonId'];

	if (is_null($regionName))
	{
		echo '<p>Unexpected region or season number.</p>';
		exit;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="UK Pinball League Regional Finals" />
<title>UK Pinball League - <?=$regionName;?> League <?=$seasonYear;?> Regional Finals.</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php include("includes/header.inc"); ?>

<div class="panel">

<?php

	include("functions/leagueinfo.inc");
	// $info = GetLeagueFinalsInfo($season);

	echo "<h1>$regionName Regional Finals $seasonYear ";

	// Display a drop down to allow user to change season that is displayed.
	echo "<span class='dropdown'>
  <h1 class='dropbtn'>Season $season</h1>
  <div class='dropdown-content'>";
	$seasonLoop=$currentseason;
	while ($seasonLoop > 0)
	{
		echo "<a href='regional-finals.php?season=$seasonLoop&region=$region'>Season $seasonLoop</a>";
		$seasonLoop--;
	}
	echo "</span></div>";

	// echo "<p>$info->note</p>";
	echo "</div>";

	// League finals are a bit complicated due to the varying formats used over the years. 
	// 

	$tsql = "
DECLARE @seasonId AS INTEGER = ? -- $seasonId
DECLARE @regionId AS INTEGER = ? --$regionId

-- select all scores from comps in finals this season
SELECT
LeagueFinal.CompetitionId AS 'CompetitionId',
LeagueFinal.Round AS 'Round',
LeagueFinal.Description AS 'Description',
Machine.Id AS 'MachineId',
Machine.Name AS 'MachineName',
Player.Id AS 'PlayerId',
Player.Name AS 'PlayerName',
Score.Score AS 'GameScore',
RANK() OVER (PARTITION BY LeagueFinal.CompetitionId, Score.MachineId ORDER BY Score.Score DESC) AS 'Position',
null AS 'ResultScore',
null AS 'ResultPoints',

(
	SELECT TOP 1 PBScore.Score
	FROM Score PBScore
	WHERE PBScore.PlayerId = Score.PlayerId AND PBScore.MachineId = Score.MachineId 
	ORDER BY PBScore.Score DESC
) AS 'PersonalBestScore',
(
	SELECT TOP 1 HighScore.Score
	FROM Score HighScore
	WHERE HighScore.MachineId = Score.MachineId 
	ORDER BY HighScore.Score DESC
) AS 'LeagueHighScore',
(
	SELECT COUNT(PlayCount.Score)
	FROM Score PlayCount
	WHERE PlayCount.PlayerId = Score.PlayerId AND PlayCount.MachineId = Score.MachineId 
) AS 'PlayCount'

FROM Score
INNER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
INNER JOIN Player ON Player.Id = Score.PlayerId
INNER JOIN Machine ON Machine.Id = Score.MachineId
WHERE LeagueFinal.SeasonId = @seasonId
AND LeagueFinal.RegionId = @regionId

UNION ALL

-- select all results
SELECT
Result.CompetitionId AS 'CompetitionId',
LeagueFinal.Round AS 'Round',
LeagueFinal.Description + ' Results' AS 'Description',
null AS 'MachineId',
null AS 'MachineName',
Player.Id AS 'PlayerId',
Player.Name AS 'PlayerName',
null AS 'GameScore',
Result.Position AS 'Position',
Result.Score AS 'ResultScore',
Result.Points AS 'ResultPoints',
null AS 'PersonalBestScore',
null AS 'LeagueHighScore',
null AS 'PlayCount'
FROM Result
INNER JOIN Player ON Player.Id = Result.PlayerId
INNER JOIN LeagueFinal on LeagueFinal.CompetitionId = Result.CompetitionId
WHERE Result.CompetitionId IN (SELECT CompetitionId FROM LeagueFinal WHERE LeagueFinal.SeasonId = @seasonId AND LeagueFinal.RegionId = @regionId)

ORDER BY Round ASC, Machine.Name DESC, GameScore DESC, Position
";

	$finalsResult = sqlsrv_query($sqlConnection, $tsql, array($seasonId, $regionId));

	if ($finalsResult == FALSE)
	{
		echo "query borken.";
	}

	$lastCompName = "";
	$lastMachineName = "";
	$lastDrew = "";
	$firstTable = TRUE;

	// Iterate over the finals competitions
	while ($finalsRow = sqlsrv_fetch_array($finalsResult, SQLSRV_FETCH_ASSOC)) 
	{
		$competitionId = $finalsRow['CompetitionId'];
		$compName = $finalsRow['Description'];
		$gameId = $finalsRow['Description'];
		$machineId = $finalsRow['MachineId'];
		$machineName = $finalsRow['MachineName'];
		$playerId = $finalsRow['PlayerId'];
		$playerName = $finalsRow['PlayerName'];
		$score = number_format($finalsRow['GameScore']);
		$position = $finalsRow['Position'];
		$resultScore = number_format($finalsRow['ResultScore']);
		$resultPoints = $finalsRow['ResultPoints'] !== null ? number_format($finalsRow['ResultPoints']) : null;
		$pbScore = number_format($finalsRow['PersonalBestScore']);
		$hsScore = number_format($finalsRow['LeagueHighScore']);
		$playCount = $finalsRow['PlayCount'];

		// Need some logic to help figure out what we're drawing
		$newCompPanel = ($compName !== $lastCompName); // comp has changed. Start a new panel
		$machineScoresRow = $machineId != null;
		$resultsScoresRow = $machineId == null;
		$newScoreTable = $machineScoresRow && ($newCompPanel || ($machineName !== $lastMachineName)); // start a new table for game scores
		$newResultsTable = $newCompPanel && $resultsScoresRow; // start a new table for results scores

		$lastCompName = $compName;
		$lastMachineName = $machineName;

		if ($newCompPanel)
		{
			if ($firstTable === FALSE) // close table and div for scores
			{
					echo "</table>";
					echo "</div>";
					echo "</div>"; // close div for last comp too
			}

			// Start a panel for this comp.
			echo "<div class='panel'>";
			echo "<h1>$compName</h1>";
			echo "</div>";

			echo "<div class='panel flex-row' '$compName'>";

			$firstTable = TRUE; // when comp changes then we don't close the previous div
		}

		if ($newScoreTable)
		{
			if ($firstTable === FALSE)
			{
					echo "</table>";
					echo "</div>";
			}

			echo "<div class='flex-column'>"; //  class='meet-table-holder'
			echo "<h2><a href='machine-info.php?machineid=$machineId' class='player-link'>$machineName</a></h2>";
			echo "<table class='table-scores'>";

			echo "<thead>
				<tr class='white'>
					<th class='meetposition'>&nbsp;</th>
					<th>Player</th> <!-- class='meetplayer' -->
					<th class='score'>Score</th> <!-- class='score' -->
					<th>&nbsp;</th>
 				</tr>
			</thead>";

			$counter = 0;
			$firstTable = FALSE;
		}

		if ($newResultsTable)
		{
			echo "<div class='flex-column'> <!-- Results table -->"; //  class='meet-table-holder'
			echo "<table class='table-scores'>";

			echo "<thead>
				<tr class='white'>
					<th class='meetposition'>&nbsp;</th> <!--  -->
					<th>Player</th> <!-- class='meetplayer' -->";

			if ($resultPoints != null)
			{
				echo "<th class='score'>Points</th>";
			}

			echo "</tr>
			</thead>";

			$counter = 0;
			$firstTable = FALSE;
		}

		$counter++;
		$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

		if ($machineScoresRow)
		{
			// Draw score row
			$scoreLink = "scores.php?playerid=$playerId&machineid=$machineId&competitionid=$competitionId";

			// remove same classes as header row

			echo "<tr>\n
				<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
				<td bgcolor='".$bgcolor."'><a href=\"$scoreLink\" class='player-link'>$playerName</a></td>\n
				<td class='score' bgcolor='".$bgcolor."'>$score</td>\n";

			// Awards.
			if ($score === $hsScore)
			{
				// HS = New league high score.
				echo "<td class='padright'><b>HS</b></td>";
			}
			else if ($score === $pbScore AND $playCount > 1)
			{
				// PB = Personal Best.
				echo "<td class='padright'>PB</td>";
			}
			else 
			{
				echo "<td class='padright'>&nbsp;</td>";
			}

			echo "</tr>\n";
		}

		if ($resultsScoresRow)
		{
			// Draw results row
			echo "<tr>\n
				<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
				<td bgcolor='".$bgcolor."'>$playerName</td>\n";

			if ($resultPoints != null)
			{
				echo "<td class='score' bgcolor='".$bgcolor."'>$resultPoints</td>\n";
			}

			echo "</tr>\n";
		}


	} // loop for next row

	// Close last table/div
	echo "</table>";
	echo "</div>";

?>

</div>

<?php include("includes/footer.inc"); ?>

</body>
</html>