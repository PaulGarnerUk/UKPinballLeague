<?php
	include("includes/sql.inc");

	$sort = htmlspecialchars($_GET["sort"] ?? "machine"); // sortby 'plays', 'machine', 'meets', 'player', 'score'
	$dir = htmlspecialchars($_GET["dir"] ?? "asc"); // sort direction ('asc' or 'desc')
	$region = htmlspecialchars($_GET["region"] ?? "all"); // region synonym ('n', 'm' etc) or 'all' for all regions.
	$season = htmlspecialchars($_GET["season"] ?? "all"); // season number, or 'all' for all seasons.

	// Validate parameters
	if ($region !== "all") {
		$tsql="
		SELECT
		Region.Id AS 'RegionId',
		Region.Name AS 'RegionName'
		FROM Region
		WHERE Region.Synonym = ? -- $region";

		$result = sqlsrv_query($sqlConnection, $tsql, array($region));
		if ($result == FALSE) {
			echo "query borken.";
		}

		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		$regionName = $row['RegionName'] . " League";
		$regionId = $row['RegionId'];

		if (is_null($regionName)) {
			echo '<p>Unexpected region.</p>';
			exit;
		}
	} else {
		$regionName = "All Leagues";
	}

	if ($season !== "all") 	{
		if ((int)$season < 1 || (int)$season > $currentseason) {
			echo "<p>Unexpected season.</p>";
			exit;
		}
		$seasonName = "Season $season";
		$seasonId = $season; // todo: Ok for now, but.
	} else {
		$seasonName = "All Seasons";
	}

	if ($dir === "desc") {
		$sortdir = "DESC";
		$sortchar = "▼";
		$oppositesortdir = "asc";
	} else {
		$sortdir = "ASC";
		$sortchar = "▲";
		$oppositesortdir = "desc";
	}

	if ($sort === "plays") {
		$sortColumn = 'GamesPlayed';
		$orderby = "ORDER BY GamesPlayed $sortdir, Appearances $sortdir";
	} else if ($sort === "meets") {
		$sortColumn = 'Appearances';
		$orderby = "ORDER BY Appearances $sortdir, GamesPlayed $sortdir";
	} else if ($sort === "player") {
		$sortColumn = 'PlayerName';
		$orderby = "ORDER BY PlayerName $sortdir, Machine.Name ASC";
	} else if ($sort === "score") {
		$sortColumn = 'HighScore';
		$orderby = "ORDER BY HighScore $sortdir, Machine.Name ASC";
	} else {
		$sort = "machine";
		$sortColumn = 'MachineName';
		$orderby = "ORDER BY MachineName $sortdir";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="All pinball machines played in the UK Pinball League." />
<title>UK Pinball League - Machines</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php include("includes/header.inc"); ?>

<div class="panel">

	<h1>High and Average Scores</h1>
	<p>Use the buttons to filter the data, and click on column headings to sort.</p>

<?php 

	echo "<h2>Filter: ";

	echo "<span class='dropdown'>
  <h2 class='dropbtn'>$regionName</h2>
  <div class='dropdown-content'>";
	echo "<a href='highaveragescores.php?region=all&season=$season&sort=$sort&dir=$dir'>All Leagues</a>";
	echo "<a href='highaveragescores.php?region=sw&season=$season&sort=$sort&dir=$dir'>South West</a>";
	echo "<a href='highaveragescores.php?region=m&season=$season&sort=$sort&dir=$dir'>Midlands</a>";
	echo "<a href='highaveragescores.php?region=lse&season=$season&sort=$sort&dir=$dir'>London and South East</a>";
	echo "<a href='highaveragescores.php?region=n&season=$season&sort=$sort&dir=$dir'>Northern</a>";
	echo "<a href='highaveragescores.php?region=s&season=$season&sort=$sort&dir=$dir'>Scottish</a>";
	echo "<a href='highaveragescores.php?region=i&season=$season&sort=$sort&dir=$dir'>Irish</a>";
	echo "</div></span>";
	
	echo " <span class='dropdown'>
  <h2 class='dropbtn'>$seasonName</h2>
  <div class='dropdown-content'>";
	echo "<a href='highaveragescores.php?region=$region&season=all&sort=$sort&dir=$dir'>All Seasons</a>";
	$seasonLoop=$currentseason;
	while ($seasonLoop > 0)
	{
		echo "<a href='highaveragescores.php?region=$region&season=$seasonLoop&sort=$sort&dir=$dir'>Season $seasonLoop</a>";
		$seasonLoop--;
	}

	echo "</div></span>";
	
	echo "</h2><p></p>";

	$filterClause = "";
	$tsqlParams = "";

	if ($region !== "all") {
		$filterClause = "WHERE LeagueMeet.RegionId = @RegionId"; 
		$tsqlParams = "DECLARE @RegionId INT = $regionId;"; // bit non-standard, but still fine.
	}

	if ($season !== "all") {
		if ($region !== "all") {
			$filterClause .= "\r\nAND LeagueMeet.SeasonId= @SeasonId";
			$tsqlParams .= "\r\nDECLARE @SeasonId INT = $seasonId;";
		} else {
			$filterClause = "WHERE LeagueMeet.SeasonId= @SeasonId"; 
			$tsqlParams = "DECLARE @SeasonId INT = $seasonId;";
		}
	}

	// The final filter clause is an AND 
	$finalFilterClause = str_replace("WHERE", "AND", $filterClause);

	// 
	$tsql ="
	$tsqlParams

WITH AverageScore AS
(
	SELECT 
	MachineId,
	AVG(Score.Score) AS 'Average'
	FROM Score
	INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
	$filterClause
	GROUP BY MachineId
),
GamesPlayed AS
(
	SELECT
	MachineId,
	COUNT(DISTINCT(Score.CompetitionId)) AS 'Appearances',
	COUNT(Score.MachineId) AS 'GamesPlayed'
	FROM Score
	INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
	$filterClause
	GROUP BY MachineId
),
MaxScore AS
(
	SELECT
	Score.CompetitionId,
	MachineId, 
	Score, 
	PlayerId,
    ROW_NUMBER() OVER (PARTITION BY MachineId ORDER BY Score DESC) Rank
    FROM Score
	INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
	$filterClause
)

SELECT 
MaxScore.MachineId, 
Machine.Name AS 'MachineName',
GamesPlayed.Appearances AS 'Appearances',
GamesPlayed.GamesPlayed AS 'GamesPlayed',
MaxScore.Score AS HighScore, 
AverageScore.Average AS AverageScore,
MaxScore.PlayerId AS 'PlayerId',
Player.Name AS 'PlayerName',
MaxScore.CompetitionId
FROM MaxScore
INNER JOIN AverageScore on AverageScore.MachineId = MaxScore.MachineId
INNER JOIN GamesPlayed ON GamesPlayed.MachineId = MaxScore.MachineId
INNER JOIN Machine ON Machine.Id = MaxScore.MachineId
INNER JOIN Player ON Player.Id = MaxScore.PlayerId
INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = MaxScore.CompetitionId
WHERE MaxScore.Rank = 1 
$finalFilterClause
$orderby
";

// Perform query.
$result= sqlsrv_query($sqlConnection, $tsql);
if ($result == FALSE)
{
	echo "query borken.";
	echo $tsql;
}

echo "<table class='machines'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>";

// machine sortable column header
if ($sort === "machine") {
	echo "<th><a href='highaveragescores.php?region=$region&season=$season&sort=machine&dir=$oppositesortdir' class='player-link'>Machine $sortchar</a></th>";
} else {
	echo "<th><a href='highaveragescores.php?region=$region&season=$season&sort=machine&dir=asc' class='player-link'>Machine</a></th>";
}

if ($sort === "meets") {
	echo "<th><a href='highaveragescores.php?region=$region&season=$season&sort=meets&dir=$oppositesortdir' class='player-link'>Meets $sortchar</a></th>";
} else  {
	echo "<th><a href='highaveragescores.php?region=$region&season=$season&sort=meets&dir=desc' class='player-link'>Meets</a></th>";
}

if ($sort === "plays") {
	echo "<th><a href='highaveragescores.php?region=$region&season=$season&sort=plays&dir=$oppositesortdir' class='player-link'>Plays $sortchar</a></th>";
} else  {
	echo "<th><a href='highaveragescores.php?region=$region&season=$season&sort=plays&dir=desc' class='player-link'>Plays</a></th>";
}

// Non sortable columns
echo "<th class='score'>Average Score</th>
		<th class='score'>High Score</th>";

if ($sort === "player") {
	echo "<th><a href='highaveragescores.php?region=$region&season=$season&sort=player&dir=$oppositesortdir' class='player-link'>Player $sortchar</a></th>";
} else {
	echo "<th><a href='highaveragescores.php?region=$region&season=$season&sort=player&dir=asc' class='player-link'>Player</th>";
}

	echo "</tr></thead>\n";

	$position = 0;
	$hiddenPositions = 0;
	$lastSortedValue = 0;

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
	$gamesPlayed = $row['GamesPlayed'];
	$appearances = $row['Appearances'];
	$machineName = $row['MachineName'];
	$averagescore = number_format($row['AverageScore']);
	$highscore = number_format($row['HighScore']);
	$playerName = $row['PlayerName'];
	$playerId = $row['PlayerId'];

	$sortedValue = $row[$sortColumn];

	if ($sortedValue != $lastSortedValue)
	{
		$lastSortedValue = $sortedValue;
		$position = $hiddenPositions + $position + 1;
		$hiddenPositions = 0;
	}
	else 
	{
		++$hiddenPositions;
	}

	echo "<tr>
			<td>$position</td>
			<td>$machineName</td>
			<td class='score'>$appearances</td>
			<td class='score padright'>$gamesPlayed</td>

			<td class='score'>$averagescore</td>
			<td class='score'>$highscore</td>
			<td><a href='player-info.php?playerid=$playerId' class='player-link'>$playerName</a></td>

			</tr>\n";
}
echo "</table>";

sqlsrv_free_stmt($result);
?>

	<p></p>
	<p>
	<b>'Meets'</b> is the number of league meets the game has appeared in.<br>
	<b>'Plays'</b> is the number of games played across those meets (eg if Twilight Zone appeared at one meet with 12 players, then we would expect Plays to be 12).
	</p>

</div>

<!-- Footer -->
<?php include("includes/footer.inc"); ?>

</body>
</html>