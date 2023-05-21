<?php
	$season = htmlspecialchars($_GET["season"]);
	$region = htmlspecialchars($_GET["region"]);
	$meet = htmlspecialchars($_GET["meet"]);
	$excludeGuests = htmlspecialchars($_GET['exclude_guests'] ?? false); // include guest players (if any) by default.
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>UK Pinball League - League Meet Results.</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <script>
    function UpdateQueryParameterFromCheckbox(parameterName, checkbox) {
      const value = checkbox.checked ? 'false' : 'true';
      const url = new URL(window.location.href);
      url.searchParams.set(parameterName, value);
      window.location.href = url.toString();
    }
  </script>

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
	LeagueMeet.Host,
	(
		SELECT COUNT(PlayerId) 
		FROM CompetitionPlayer
		WHERE CompetitionPlayer.CompetitionId = LeagueMeet.CompetitionId
		AND CompetitionPlayer.ExcludeFromResults = 1
	) AS ExcludedPlayers,
	LeagueMeet.CompetitionId AS CompetitionId
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
	$leagueMeetExcludedPlayers = $leagueMeetRow['ExcludedPlayers'];
	$competitionId = $leagueMeetRow['CompetitionId'];
?>

	<div class="panel">
	<?php 
		echo "<h1>$leagueMeetRegionName League, Season $season, Meet #$meet</h1>"; 

	if ($leagueMeetExcludedPlayers > 0)
	{
		echo "
		<form method='GET'>
		<div class='checkbox-wrapper-13'>
		<input id='c1-13' type='checkbox' name='exclude_guests' onchange='UpdateQueryParameterFromCheckbox(\"exclude_guests\", this)' ";
		if ($excludeGuests != 'true') echo 'checked'; 
		echo " >
		<label for='c1-13'>Show $leagueMeetExcludedPlayers guest";
		if ($leagueMeetExcludedPlayers > 1) echo " players ";
		else echo " player ";
		echo "in results.</label>
		</div>
		</form>";
		echo "</p>";
	}

	// Get all scores at meet
	$tsql="
	DECLARE @region NVARCHAR(3) = ?; -- $region
	DECLARE @season INTEGER = ?; -- $season
	DECLARE @MeetNumber INTEGER = ?; -- $meet
	DECLARE @ExcludeGuests BIT = ?; -- $excludeGuests
	DECLARE @CompetitionId INTEGER = ?; -- $competitionId

	-- Conditionally build a table variable containing excluded player ids
	SET NOCOUNT ON;
	DECLARE @ExcludedPlayerIds TABLE (PlayerId INT)
	IF (@ExcludeGuests = 1) BEGIN
		INSERT INTO @ExcludedPlayerIds (PlayerId) 
		SELECT PlayerId 
		FROM CompetitionPlayer
		WHERE CompetitionPlayer.CompetitionId = @CompetitionId
		AND CompetitionPlayer.ExcludeFromResults = 1
		END

	SELECT
	RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score DESC) AS Rank,
	Score.PlayerId AS 'PlayerId',
	Player.Name AS 'PlayerName',
	Score.MachineId AS 'MachineId',
	Machine.Name AS 'MachineName',
	CompetitionMachine.Notes AS 'MachineNotes',
	Score.CompetitionId AS 'CompetitionId',
	Score.Score AS 'GameScore',
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
	) AS 'PlayCount',
	COALESCE(CompetitionPlayer.ExcludeFromResults, 0) AS 'ExcludedPlayer'
	FROM Score 
	INNER JOIN Player ON Player.Id = Score.PlayerId
	INNER JOIN Machine ON Machine.Id = Score.MachineId
	INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
	INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
	INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
	LEFT OUTER JOIN CompetitionPlayer ON CompetitionPlayer.CompetitionId = Score.CompetitionId AND CompetitionPlayer.PlayerId = Score.PlayerId
	LEFT OUTER JOIN CompetitionMachine ON CompetitionMachine.CompetitionId = Score.CompetitionId AND CompetitionMachine.MachineId = Score.MachineId
	WHERE Region.Synonym = @region
	AND Season.SeasonNumber = @season 
	AND LeagueMeet.MeetNumber = @meetNumber
	AND Score.PlayerId NOT IN (SELECT PlayerId FROM @ExcludedPlayerIds)
	ORDER BY Machine.Name, GameScore desc, PlayerName
	";

	if ($excludeGuests === 'true') $excludeGuestsBit = 1;
	else $excludeGuestsBit = 0;

	// Perform query with parameterised values.
	$result= sqlsrv_query($sqlConnection, $tsql, array($region, $season, $meet, $excludeGuestsBit, $competitionId));
	if ($result == FALSE)
	{
		echo "query borken.";
		/*
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
	            echo "code: ".$error[ 'code']."<br />";
	            echo "message: ".$error[ 'message']."<br />";
			}   
		}*/
	}

	$lastMachineName = "";

	while ($scoreRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$scorePlayerId = $scoreRow['PlayerId'];
		$scorePlayerName = $scoreRow['PlayerName'];
		$scoreMachineId = $scoreRow['MachineId'];
		$scoreMachineName = $scoreRow['MachineName'];
		$scoreMachineNotes = $scoreRow['MachineNotes'];
		$scoreRank = $scoreRow['Rank'];
		$scoreCompetitionId = $scoreRow['CompetitionId'];
		$scoreGameScore = number_format($scoreRow['GameScore']);
		$pbScore = number_format($scoreRow['PersonalBestScore']);
		$hsScore = number_format($scoreRow['LeagueHighScore']);
		$playCount = $scoreRow['PlayCount'];
		$excludedPlayer = $scoreRow['ExcludedPlayer'];

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
/*			echo "<h2 style='display:inline;'><a href='machine-info.php?machineid=$scoreMachineId' class='player-link'>$scoreMachineName</a></h2>";
			if ($scoreMachineNotes !== null)
			{
				echo "<p style='display:inline;'>($scoreMachineNotes)</p>";
			}*/
        
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
			<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"$scoreLink\" class='player-link'>$scorePlayerName</a>";

		if ($excludedPlayer == 1) echo " (*)";

		echo "</td>\n
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
	if ($excludeGuests === 'true')
	{
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

		$result= sqlsrv_query($sqlConnection, $tsql, array($region, $season, $meet));
	}
	else 
	{
		$tsql="
		DECLARE @CompetitionId INT = ?; -- competition id to calculate results for
		DECLARE @ExcludeNonLeaguePlayers BIT = ?; --

		-- Conditionally build a table variable containing excluded player ids
		DECLARE @ExcludedPlayerIds TABLE (PlayerId INT)
		IF (@ExcludeNonLeaguePlayers = 1) BEGIN
			INSERT INTO @ExcludedPlayerIds (PlayerId) 
			SELECT PlayerId 
			FROM CompetitionPlayer
			WHERE CompetitionPlayer.CompetitionId = @CompetitionId
			AND CompetitionPlayer.ExcludeFromResults = 1
			END

		-- Calculate number of players at meet
		DECLARE @TotalPlayers INT
		SET @TotalPlayers = (SELECT COUNT(DISTINCT(PlayerId))
			FROM Score 
			WHERE CompetitionId = @CompetitionId
			AND PlayerId NOT IN (SELECT PlayerId FROM @ExcludedPlayerIds));

		WITH MeetScores AS
		(
			SELECT
			Score.PlayerId AS 'PlayerId',
			Score.MachineId AS 'MachineId',
			Score.Score,
			RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score.Score DESC) AS 'Position',
			RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score.Score ASC) AS 'Points'
			FROM Score
			WHERE CompetitionId = @CompetitionId
			AND Score.PlayerId NOT IN (SELECT PlayerId FROM @ExcludedPlayerIds)
		),
		BonusPoints AS
		(
			SELECT
			TopScore.PlayerId,
			1 AS BonusPoint
			FROM MeetScores AS TopScore
			INNER JOIN MeetScores AS SecondScore ON SecondScore.MachineId = TopScore.MachineId AND SecondScore.Position = 2
			WHERE TopScore.Position = 1
			AND (TopScore.Score >= (SecondScore.Score * 2))
		),
		TotalPoints AS
		(
			SELECT
			MeetScores.PlayerId,
			SUM(MeetScores.Points)
			+ (SELECT COALESCE(SUM(BonusPoints.BonusPoint),0) FROM BonusPoints WHERE BonusPoints.PlayerId = MeetScores.PlayerId)
				AS TotalPoints
			FROM MeetScores
			GROUP BY MeetScores.PlayerId
		),
		RankedResults AS
		(
			SELECT
			PlayerId AS 'PlayerId',
			TotalPoints.TotalPoints AS 'Score',
			RANK() OVER (ORDER BY (TotalPoints.TotalPoints) DESC) AS 'Position',
			CASE WHEN (((ROW_NUMBER() OVER (ORDER BY (TotalPoints.TotalPoints) ASC))-@TotalPlayers)+20) < 0 THEN 0 
				ELSE (((ROW_NUMBER() OVER (ORDER BY (TotalPoints.TotalPoints) ASC))-@TotalPlayers)+20) 
				END as 'Points'
			FROM TotalPoints
		)

		SELECT
			@CompetitionId,
			PlayerId AS 'PlayerId',
			Player.Name AS 'PlayerName',
			Score,
			Position AS 'Rank',
			CASE
				-- When two (or more) players have the same position, then sum the points and divide by the number of players
				WHEN (SELECT COUNT(*) FROM RankedResults WHERE Position = r.Position) > 1 THEN (
					SELECT CAST(SUM(RankedResults.Points) AS float) FROM RankedResults WHERE Position = r.Position ) / (SELECT COUNT(*) FROM RankedResults WHERE Position = r.Position
				) ELSE (
					r.Points
				)
			END as 'Points'
		FROM RankedResults r
		INNER JOIN Player ON Player.Id = r.PlayerId
		ORDER BY Score DESC, Player.Name ASC";

		 // Perform query with parameterised values.
		$result= sqlsrv_query($sqlConnection, $tsql, array($competitionId, $excludeGuestsBit));
	}
	
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

<!-- Page notes -->
<p>
<?php
	if ($leagueMeetExcludedPlayers > 0 AND $excludeGuests == 'false')
	{
		echo "<br>(*) indicates guest appearance from player in different league. Player is excluded from official results.";
	}
?>
<br><b>HS</b> - This is the current league High Score for this machine.
<br>PB - Indicates a Personal Best score for this player on the machine.</p>

<!-- Footer -->
<?php include("includes/footer.inc"); ?>

</body>
</html>



<style>
  @supports (-webkit-appearance: none) or (-moz-appearance: none) {
    .checkbox-wrapper-13 input[type=checkbox] {
      --active: #FEC171;
      --active-inner: #fff;
      --focus: 2px rgba(254, 193, 113, .3);
	  
      --border: #BBC1E1;
      --border-hover: #275EFE;
      --background: #fff;
      --disabled: #F6F8FF;
      --disabled-inner: #E1E6F9;
      -webkit-appearance: none;
      -moz-appearance: none;
      height: 21px;
	  margin: auto 0px 0px 20px;
      outline: none;
      display: inline-block;
      vertical-align: top;
      position: relative;
      /*margin: 0;*/
      cursor: pointer;
      border: 1px solid var(--bc, var(--border));
      background: var(--b, var(--background));
      transition: background 0.3s, border-color 0.3s, box-shadow 0.2s;
    }
    .checkbox-wrapper-13 input[type=checkbox]:after {
      content: "";
      display: block;
      left: 0;
      top: 0;
      position: absolute;
      transition: transform var(--d-t, 0.3s) var(--d-t-e, ease), opacity var(--d-o, 0.2s);
    }
    .checkbox-wrapper-13 input[type=checkbox]:checked {
      --b: var(--active);
      --bc: var(--active);
      --d-o: .3s;
      --d-t: .6s;
      --d-t-e: cubic-bezier(.2, .85, .32, 1.2);
    }
    .checkbox-wrapper-13 input[type=checkbox]:disabled {
      --b: var(--disabled);
      cursor: not-allowed;
      opacity: 0.9;
    }
    .checkbox-wrapper-13 input[type=checkbox]:disabled:checked {
      --b: var(--disabled-inner);
      --bc: var(--border);
    }
    .checkbox-wrapper-13 input[type=checkbox]:disabled + label {
      cursor: not-allowed;
    }
    .checkbox-wrapper-13 input[type=checkbox]:hover:not(:checked):not(:disabled) {
      --bc: var(--border-hover);
    }
    .checkbox-wrapper-13 input[type=checkbox]:focus {
      box-shadow: 0 0 0 var(--focus);
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch) {
      width: 21px;
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch):after {
      opacity: var(--o, 0);
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch):checked {
      --o: 1;
    }
    .checkbox-wrapper-13 input[type=checkbox] + label {
      display: inline-block;
      vertical-align: middle;
      cursor: pointer;
      margin-left: 4px;
    }

    .checkbox-wrapper-13 input[type=checkbox]:not(.switch) {
      border-radius: 7px;
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch):after {
      width: 5px;
      height: 9px;
      border: 2px solid var(--active-inner);
      border-top: 0;
      border-left: 0;
      left: 7px;
      top: 4px;
      transform: rotate(var(--r, 20deg));
    }
    .checkbox-wrapper-13 input[type=checkbox]:not(.switch):checked {
      --r: 43deg;
    }
  }

  .checkbox-wrapper-13 * {
    box-sizing: inherit;
	font-family: Arial, sans-serif;
	font-size: 14px;
  }
  .checkbox-wrapper-13 *:before,
  .checkbox-wrapper-13 *:after {
    box-sizing: inherit;
  }

</style>
