<?php
	$season = htmlspecialchars($_GET["season"]);
	$region = htmlspecialchars($_GET["region"]);
	$meet = htmlspecialchars($_GET["meet"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>UK Pinball League - League Meet Results.</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Content -->

<?php 
	require_once("includes/header.inc"); 
	require_once("includes/envvars.inc");
	require_once("includes/sql.inc");

	$tsql= "
	SELECT
	Season.SeasonNumber,
	Region.Name AS RegionName,
	LeagueMeet.MeetNumber,
	CONVERT(varchar, LeagueMeet.Date, 103) AS Date,
	LeagueMeet.Host
	FROM LeagueMeet
	INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
	INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
	WHERE Region.Synonym = ? -- $region 
	AND Season.SeasonNumber = ? -- $season 
	AND LeagueMeet.MeetNumber = ? -- $meet 
	";

	// Perform query with parameterised values.
	$result= sqlsrv_query($sqlConnection, $tsql, array($region, $season, $meet));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$leagueMeetRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$leagueMeetRegionName = $leagueMeetRow['RegionName'];
	$leagueMeetHostName = $leagueMeetRow['Host'];
	$leagueMeetDate = $leagueMeetRow['Date'];
?>

	<div class="panel">
	<?php 
		echo "<h1>$leagueMeetRegionName League, Season $season, Meet #$meet</h1>"; 

	// Get all scores at meet
	$tsql="
	SELECT
	RANK() OVER (PARTITION BY MachineId ORDER BY Score DESC) AS Rank,
	Score.PlayerId AS PlayerId,
	Player.Name AS PlayerName,
	Score.MachineId AS MachineId,
	Machine.Name AS MachineName,
	Score.CompetitionId AS CompetitionId,
	Score.Score AS GameScore,
	(
		SELECT TOP 1 PBScore.Score
		FROM Score PBScore
		WHERE PBScore.PlayerId = Score.PlayerId AND PBScore.MachineId = Score.MachineId 
		ORDER BY PBScore.Score DESC
	) AS PersonalBestScore,
	(
		SELECT TOP 1 HighScore.Score
		FROM Score HighScore
		WHERE HighScore.MachineId = Score.MachineId 
		ORDER BY HighScore.Score DESC
	) AS LeagueHighScore,
	(
		SELECT COUNT(PlayCount.Score)
		FROM Score PlayCount
		WHERE PlayCount.PlayerId = Score.PlayerId AND PlayCount.MachineId = Score.MachineId 
	) AS PlayCount
	FROM Score 
	INNER JOIN Player ON Player.Id = Score.PlayerId
	INNER JOIN Machine ON Machine.Id = Score.MachineId
	INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
	INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
	INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
	WHERE Region.Synonym = ? -- $region 
	AND Season.SeasonNumber = ? -- $season 
	AND LeagueMeet.MeetNumber = ? -- $meet 
	ORDER BY Machine.Name, GameScore desc, PlayerName
	";

	// Perform query with parameterised values.
	$result= sqlsrv_query($sqlConnection, $tsql, array($region, $season, $meet));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$lastMachineName = "";

	while ($scoreRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$scorePlayerId = $scoreRow['PlayerId'];
		$scorePlayerName = $scoreRow['PlayerName'];
		$scoreMachineId = $scoreRow['MachineId'];
		$scoreMachineName = $scoreRow['MachineName'];
		$scoreRank = $scoreRow['Rank'];
		$scoreCompetitionId = $scoreRow['CompetitionId'];
		$scoreGameScore = number_format($scoreRow['GameScore']);
		$pbScore = number_format($scoreRow['PersonalBestScore']);
		$hsScore = number_format($scoreRow['LeagueHighScore']);
		$playCount = $scoreRow['PlayCount'];

		// write new table header if this is a new machine
		if ($scoreMachineName !== $lastMachineName)
		{
			// Close off the last table (unless this was the first table)
			if ($lastMachineName !== "")
			{
				echo "</table>";
				echo "</div>";
			}

			echo "<div class='meet-table-holder'>";
        
			echo "<h2><a href='machine-info.php?machineid=$scoreMachineId' class='player-link'>$scoreMachineName</a></h2>";
        
			echo "<table>";
        
			echo "<thead>
				<tr class='white'>
					<th class='meetposition'>&nbsp;</th>
					<th class='meetplayer'>Player</th>
					<th class='score'>Score</th>
					<th>&nbsp;</th>
 				</tr>
			</thead>";

			$lastMachineName = $scoreMachineName;
			$counter = 0;
		}

		$counter++;
		$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

		$scoreLink = "scores.php?playerid=$scorePlayerId&machineid=$scoreMachineId&competitionid=$scoreCompetitionId";

        echo "<tr>\n
			<td class='meetposition' bgcolor='".$bgcolor."'>$scoreRank</td>\n
			<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"$scoreLink\" class='player-link'>$scorePlayerName</a></td>\n
			<td class='score' bgcolor='".$bgcolor."'>$scoreGameScore</td>\n";

		// Awards.
		if ($scoreGameScore === $hsScore)
		{
			// HS = New league high score.
			echo "<td class='padright'><b>HS</b></td>";
		}
		else if ($scoreGameScore === $pbScore AND $playCount > 1)
		{
			// PB = Personal Best. Only shown if player has played this game at least once before.
			echo "<td class='padright'>PB</td>";
		}
		else 
		{
			echo "<td>&nbsp;</td>";
		}

        echo "</tr>\n";
	}

	// Close off the last table
	echo "</table>";
	echo "</div>";


	// Overall results
	$tsql="
	SELECT
	Result.Position AS 'Rank',
	Player.Id AS 'PlayerId',
	Player.Name AS 'PlayerName',
	Result.Score AS 'Score',
	Result.Points AS 'Points'
	from Result
	INNER JOIN LeagueMeet on LeagueMeet.CompetitionId = Result.CompetitionId
	INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
	INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
	INNER JOIN Player ON Player.Id = Result.PlayerId
	WHERE Region.Synonym = ? -- $region 
	AND Season.SeasonNumber = ? -- $season 
	AND LeagueMeet.MeetNumber = ? -- $meet 
	ORDER BY Rank, PlayerName
	";
     
    // Perform query with parameterised values.
	$result= sqlsrv_query($sqlConnection, $tsql, array($region, $season, $meet));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$counter = 0;

	echo "<div class='meet-table-holder'>";

	echo "<h2>Results</h2>";
	echo "<table>";

	echo "<thead>
				<tr class='white'>
					<th class='meetposition'>&nbsp;</th>
					<th class='meetplayer'>Player</th>
					<th class='score'>Score</th>
					<th class='score padright'>Points</th>
 				</tr>
			</thead>";

	while ($resultsRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$resultRank = $resultsRow['Rank'];
		$resultPlayerName = $resultsRow['PlayerName'];
		$resultScore = (float)$resultsRow['Score'];
		$resultPoints = (float)$resultsRow['Points'];
		$playerId = $resultsRow['PlayerId'];

		$playerLink = "player-info.php?playerid=$playerId";

		$counter++;
		$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

		echo "<tr class='border'>\n
			<td class='meetposition' bgcolor='".$bgcolor."'>$resultRank</td>\n
			<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"$playerLink\" class='player-link'>$resultPlayerName</a></td>\n
			<td class='score' bgcolor='".$bgcolor."'>$resultScore</td>\n
			<td class='score padright' bgcolor='".$bgcolor."'>$resultPoints</td>\n
		</tr>\n";
	}

	echo "</table>\n";
	echo "</div>";

	sqlsrv_free_stmt($result);
?>

<div style="clear: both;"></div>

<!-- Footer -->
<?php include("includes/footer.inc"); ?>

</body>
</html>