<?php
	// First, validate region and season# 
	include("includes/sql.inc"); 

	$region = htmlspecialchars($_GET["region"] ?? null);
	$season = htmlspecialchars($_GET['season'] ?? $currentseason); // default to current season if not specified.

	$tsql="
	SELECT
	Region.Name AS 'RegionName',
	Season.Year AS 'SeasonYear',
	(SELECT COUNT(*) FROM LeagueMeet WHERE LeagueMeet.SeasonId = Season.Id AND LeagueMeet.RegionId = Region.Id AND LeagueMeet.Status = 3) AS 'TotalMeets',
	(SELECT MAX(competitionId) FROM LeagueFinal WHERE LeagueFinal.SeasonId = Season.Id and LeagueFinal.RegionId = Region.Id) AS 'FinalsCompetitionId'
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
	$seasonYear = $row['SeasonYear'];
	$totalMeets = $row['TotalMeets'];
	$finalsCompetitionId = $row['FinalsCompetitionId'];

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
<meta name="description" content="UK Pinball League Table" />
<title>UK Pinball League - <?=$regionName;?> League <?=$seasonYear;?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php include("includes/header.inc"); ?>


<div class="panel">
<?php

	include("functions/leagueinfo.inc");
	
	$info = GetLeagueInfo($region, $season);

	echo "<h1>$regionName League $seasonYear ";

	// Display a drop down to allow user to change season that is displayed.
	echo "<span class='dropdown'>
  <h1 class='dropbtn'>Season $season</h1>
  <div class='dropdown-content'>";
$seasonLoop=$currentseason;
while ($seasonLoop > 0)
{
	echo "<a href='league.php?region=$region&season=$seasonLoop'>Season $seasonLoop</a>";
	$seasonLoop--;
}
	echo "</span></div></h1>";

	echo "<p>$info->note</p>";

	if ($totalMeets < 1 && $season == $currentseason)
	{
		echo "<p>No results have been submitted for the current season yet.  You can view previous seasons using the button above.</p>";
	}
?>

<?php
	$tsql= "
DECLARE @region NVARCHAR(3) = ?; -- $region
DECLARE @season INTEGER = ?; -- $season
DECLARE @qualifyingMeets INTEGER = ?; -- $info->numberOfQualifyingMeets
DECLARE @finalsCompetitionId INTEGER = ?; -- $finalsCompetitionId

-- Simplify the region and season into id values
WITH Query (RegionId, SeasonId) AS
(
  SELECT 
  Region.Id AS RegionId,
  Season.Id AS SeasonId
  FROM Region, Season
  WHERE Region.Synonym = @region
  AND Season.SeasonNumber = @season
),
-- Select completed league meets for this region/season
LeagueMeets (Id, MeetNumber, CompetitionId) AS
(
  SELECT
  Id,
  MeetNumber,
  CompetitionId
  FROM LeagueMeet
  INNER JOIN Query on LeagueMeet.RegionId = Query.RegionId AND LeagueMeet.SeasonId = Query.SeasonId
  WHERE LeagueMeet.Status = 3
),
-- Select results for this region/season
Results (MeetNumber, CompetitionId, PlayerId, Score, Points, Position, Rnk) AS
(
  SELECT
  LeagueMeets.MeetNumber,
  LeagueMeets.CompetitionId,
  Result.PlayerId,
  Result.Score, 
  Result.Points,
  Result.Position,
  ROW_NUMBER() OVER (PARTITION BY PlayerId ORDER BY Points DESC) AS Rnk
  FROM Result
  INNER JOIN LeagueMeets ON Result.CompetitionId = LeagueMeets.CompetitionId
),
-- Select players for this region/season
SeasonPlayers (PlayerId, PlayerName) AS
(
	SELECT DISTINCT
	Player.Id,
	Player.Name
	FROM Result 
	INNER JOIN LeagueMeets ON Result.CompetitionId = LeagueMeets.CompetitionId
	INNER JOIN Player ON Player.Id = Result.PlayerId
),
LeagueResults AS
(
	SELECT 
	SeasonPlayers.PlayerId AS playerid,
	SeasonPlayers.PlayerName AS player,
	(
		SELECT COUNT(*) FROM Results WHERE Results.PlayerId = SeasonPlayers.PlayerId
	) AS played,
	MeetOne.Points as meet1,
	MeetTwo.Points as meet2,
	MeetThree.Points as meet3,
	MeetFour.Points as meet4,
	MeetFive.Points as meet5,
	MeetSix.Points as meet6,
	COALESCE(MeetOne.Points,0) + COALESCE(MeetTwo.Points,0) + COALESCE(MeetThree.Points,0) + COALESCE(MeetFour.Points,0) + COALESCE(MeetFive.Points,0) + COALESCE(MeetSix.Points,0) AS total,
	(
		SELECT 
		SUM(Results.Points)
		FROM Results
		WHERE Results.PlayerId = SeasonPlayers.PlayerId AND Results.Rnk <= @qualifyingMeets
	) AS best4,
	(
		SELECT 
		SUM(Results.Points)
		FROM Results
		WHERE Results.PlayerId = SeasonPlayers.PlayerId AND Results.Rnk <= (@qualifyingMeets + 1)
	) AS best5,
	(
		SELECT 
		SUM(Results.Points)
		FROM Results
		WHERE Results.PlayerId = SeasonPlayers.PlayerId AND Results.Rnk <= (@qualifyingMeets + 2)
	) AS best6
	FROM SeasonPlayers 
	LEFT OUTER JOIN Results AS MeetOne ON MeetOne.MeetNumber = 1 AND MeetOne.PlayerId = SeasonPlayers.PlayerId
	LEFT OUTER JOIN Results AS MeetTwo ON MeetTwo.MeetNumber = 2 AND MeetTwo.PlayerId = SeasonPlayers.PlayerId
	LEFT OUTER JOIN Results AS MeetThree ON MeetThree.MeetNumber = 3 AND MeetThree.PlayerId = SeasonPlayers.PlayerId
	LEFT OUTER JOIN Results AS MeetFour ON MeetFour.PlayerId = SeasonPlayers.PlayerId AND MeetFour.MeetNumber = 4
	LEFT OUTER JOIN Results AS MeetFive ON MeetFive.PlayerId = SeasonPlayers.PlayerId AND MeetFive.MeetNumber = 5
	LEFT OUTER JOIN Results AS MeetSix ON MeetSix.PlayerId = SeasonPlayers.PlayerId AND MeetSix.MeetNumber = 6
)

SELECT 
LeagueResults.*,
RANK() OVER (ORDER BY best4 DESC, best5 DESC, best6 DESC) AS 'league_pos',
Finals.Position AS 'finals_pos',
RANK() OVER (ORDER BY -Finals.Position DESC, best4 DESC, best5 DESC, best6 DESC) AS 'pos'
FROM LeagueResults
LEFT OUTER JOIN Result AS Finals ON Finals.PlayerId = LeagueResults.PlayerId AND Finals.CompetitionId = @finalsCompetitionId
ORDER BY -Finals.Position DESC, best4 DESC, best5 DESC, best6 DESC, played ASC
";

	$result = sqlsrv_query($sqlConnection, $tsql, array($region, $season, $info->numberOfQualifyingMeets, $finalsCompetitionId));

	if ($result == FALSE)
	{
		//echo "query borken.";
		echo (sqlsrv_errors());
	}

echo "<div class='table-holder'>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
				<th>Player</th>
				<th>Played</th>";

$meetNumber = 1;
while ($meetNumber <= $totalMeets)
{
	echo "<th><a href=\"leaguemeet.php?season=$season&amp;region=$region&amp;meet=$meetNumber\" class='link'>Meet $meetNumber</a></th>";
	$meetNumber++;
}

		echo "<th>Total</th>
				<th class='paddidge'>Best $info->numberOfQualifyingMeets</th>";

		if ($finalsCompetitionId !== null) {
			echo "<th><a href=\"regional-finals.php?season=$season&amp;region=$region\" class='link'>Finals</a></th>";
		}

		echo "</tr>
		</thead>";

		
	$counter = 0;
	$total = '';
	
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$player = $row['player'];
		$playerid = $row['playerid'];
		$played = $row['played'];
		$meet1 = (is_null($row['meet1']) ? "-" : (float)$row['meet1']);
		$meet2 = (is_null($row['meet2']) ? "-" : (float)$row['meet2']);
		$meet3 = (is_null($row['meet3']) ? "-" : (float)$row['meet3']);
		$meet4 = (is_null($row['meet4']) ? "-" : (float)$row['meet4']);
		$meet5 = (is_null($row['meet5']) ? "-" : (float)$row['meet5']);
		$meet6 = (is_null($row['meet6']) ? "-" : (float)$row['meet6']);
		$best4 = $row['best4'];
		$best5 = $row['best5'];
		$best6 = $row['best6'];
		$total = $row['total'];
	
		$best4 = round($best4,"1");
		$best5 = round($best5,"1");
		$best6 = round($best6,"1");
		$total = round($total,"1");
		$leaguePos = number_format($row['league_pos']);
		$finalsPos = $row['finals_pos'] !== null ? number_format($row['finals_pos']) : null;
		$pos = number_format($row['pos']);
	
		// Calculate row highlighting
		$counter++;
		if ($counter <= $info->aQualifyingPlaces) $bgcolor = "#fec171";
		else if ($counter <= ($info->aQualifyingPlaces + $info->bQualifyingPlaces)) $bgcolor = "#fee0b8";
		else $bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

		echo "<tr bgcolor='".$bgcolor."'>\n
		<td bgcolor='".$bgcolor."'>$pos</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"player-info.php?playerid=$playerid\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n";
		if ($totalMeets >= 1) echo "<td bgcolor='".$bgcolor."'>$meet1</td>\n";
		if ($totalMeets >= 2) echo "<td bgcolor='".$bgcolor."'>$meet2</td>\n";
		if ($totalMeets >= 3) echo "<td bgcolor='".$bgcolor."'>$meet3</td>\n";
		if ($totalMeets >= 4) echo "<td bgcolor='".$bgcolor."'>$meet4</td>\n";
		if ($totalMeets >= 5) echo "<td bgcolor='".$bgcolor."'>$meet5</td>\n";
		if ($totalMeets >= 6) echo "<td bgcolor='".$bgcolor."'>$meet6</td>\n";
		echo "<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n";

		if ($finalsCompetitionId !== null) {
			$finalsAdj = $leaguePos - $pos; // Calculate a + or - number that shows how finals adjusted the player's position

			if ($finalsAdj === 0 && $finalsPos === null) $finalsAdj = "-"; // If player did not play in finals, and had no adjustment to position, then show as '-'
			if ($finalsAdj >= 0) $finalsAdj = "+".$finalsAdj;
			// if ($finalsAdj < 0) $finalsAdj = "-".$finalsAdj;

			echo "<td bgcolor='".$bgcolor."'>$finalsAdj</td>\n";
		}

		echo "</tr>\n";
	}

	echo "</table>\n";
	echo "</div>";

	// Qualifying places descriptions.
	echo "<p class='qualifier'>"; 
	if ($info->aQualifyingPlaces > 0)
	{
		echo	"<span class='qual'>$info->aQualifyingDescription</span>";
	}
	if ($info->bQualifyingPlaces > 0)
	{
		echo 	" &nbsp; &nbsp;<span style='white-space:nowrap'><span class='qual-b'>$info->bQualifyingDescription</span>";
	}
	echo "</p>";

	sqlsrv_free_stmt($result);

?>

</div>

<!-- Header and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>