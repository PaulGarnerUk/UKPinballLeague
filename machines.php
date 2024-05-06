<?php
	require_once("includes/sql.inc");
	require_once("functions/regioninfo.inc");
    require_once("functions/seasoninfo.inc");

	$sort = htmlspecialchars($_GET["sort"] ?? "machine"); // sortby 'plays', 'machine', 'meets', 'player', 'score'
	$dir = htmlspecialchars($_GET["dir"] ?? "asc"); // sort direction ('asc' or 'desc')
	$regionParam = htmlspecialchars($_GET["region"] ?? "all"); // region synonym ('n', 'm' etc) or 'all' for all regions.
	$seasonParam = htmlspecialchars($_GET["season"] ?? "all"); // season number, or 'all' for all seasons.

	// Validate region
	$region = ValidateRegionSynonym($regionParam, $sqlConnection);
	if (is_null($region)) {
		echo '<p>Unexpected region.</p>';
		exit;
	}

    // Validate season
    $season = ValidateSeasonNumber($seasonParam, $sqlConnection);
    if (is_null($season)) {
        echo '<p>Unexpected season.</p>';
		exit;
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

	<h1>Machines - Full List</h1>
	<p>All machines played to date in the UK Pinball League.<br>
	Use the buttons to filter the data, and click on column headings to sort.</p>

<?php 

	echo "<h2>Filter: ";

	echo "<span class='dropdown'>
  <h2 class='dropbtn'>$region->leagueName</h2>
  <div class='dropdown-content'>";
	echo "<a href='machines.php?region=all&season=$seasonParam&sort=$sort&dir=$dir'>All Leagues</a>";
	echo "<a href='machines.php?region=sw&season=$seasonParam&sort=$sort&dir=$dir'>South West</a>";
	echo "<a href='machines.php?region=m&season=$seasonParam&sort=$sort&dir=$dir'>Midlands</a>";
	echo "<a href='machines.php?region=lse&season=$seasonParam&sort=$sort&dir=$dir'>London and South East</a>";
	echo "<a href='machines.php?region=n&season=$seasonParam&sort=$sort&dir=$dir'>Northern</a>";
	echo "<a href='machines.php?region=s&season=$seasonParam&sort=$sort&dir=$dir'>Scotland</a>";
	echo "<a href='machines.php?region=i&season=$seasonParam&sort=$sort&dir=$dir'>Ireland</a>";
	echo "<a href='machines.php?region=ea&season=$seasonParam&sort=$sort&dir=$dir'>East Anglian</a>";
	echo "<a href='machines.php?region=w&season=$seasonParam&sort=$sort&dir=$dir'>South Wales</a>";
	echo "</div></span>";
	
	echo " <span class='dropdown'>
  <h2 class='dropbtn'>$season->seasonName</h2>
  <div class='dropdown-content'>";
	echo "<a href='machines.php?region=$regionParam&season=all&sort=$sort&dir=$dir'>All Seasons</a>";
	$seasonLoop=$currentseason;
	while ($seasonLoop > 0)
	{
		echo "<a href='machines.php?region=$regionParam&season=$seasonLoop&sort=$sort&dir=$dir'>Season $seasonLoop</a>";
		$seasonLoop--;
	}

	echo "</div></span>";
	
	/* Coming soon*.
	echo "<input class='search' type='text' size='30' placeholder='Search..'>";
	*/
	echo "</h2><p></p>";

	$filterClause = "";
	$tsqlParams = "";

	if ($region->regionId > 0) {
		$filterClause = "WHERE (LeagueMeet.RegionId = @RegionId)"; 
		$tsqlParams = "DECLARE @RegionId INT = $region->regionId;";
	}

	if ($season->seasonId > 0) {
		if ($region->regionId > 0) {
			$filterClause .= "\r\nAND (LeagueMeet.SeasonId = @SeasonId OR LeagueFinal.SeasonId = @SeasonId)";
			$tsqlParams .= "\r\nDECLARE @SeasonId INT = $season->seasonId;";
		} else {
			$filterClause = "WHERE (LeagueMeet.SeasonId = @SeasonId OR LeagueFinal.SeasonId = @SeasonId)"; 
			$tsqlParams = "DECLARE @SeasonId INT = $season->seasonId;";
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
	LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
	LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
	$filterClause
	GROUP BY MachineId
),
GamesPlayed AS
(
	SELECT
	MachineId,
	COUNT(DISTINCT(Score.CompetitionId)) AS 'Appearances',
	COUNT(Score.Score) AS 'GamesPlayed'
	FROM Score
	LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
	LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
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
	LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
	LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
	$filterClause
)

SELECT 
MaxScore.MachineId AS 'MachineId', 
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
LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = MaxScore.CompetitionId
LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = MaxScore.CompetitionId
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
	echo "<th><a href='machines.php?region=$regionParam&season=$seasonParam&sort=machine&dir=$oppositesortdir' class='player-link'>Machine $sortchar</a></th>";
} else {
	echo "<th><a href='machines.php?region=$regionParam&season=$seasonParam&sort=machine&dir=asc' class='player-link'>Machine</a></th>";
}

if ($sort === "meets") {
	echo "<th><a href='machines.php?region=$regionParam&season=$seasonParam&sort=meets&dir=$oppositesortdir' class='player-link'>Appearances $sortchar</a></th>";
} else  {
	echo "<th><a href='machines.php?region=$regionParam&season=$seasonParam&sort=meets&dir=desc' class='player-link'>Appearances</a></th>";
}

if ($sort === "plays") {
	echo "<th><a href='machines.php?region=$regionParam&season=$seasonParam&sort=plays&dir=$oppositesortdir' class='player-link'>Plays $sortchar</a></th>";
} else  {
	echo "<th><a href='machines.php?region=$regionParam&season=$seasonParam&sort=plays&dir=desc' class='player-link'>Plays</a></th>";
}

// Non sortable columns
echo "<th class='score'>Average Score</th>
		<th class='score'>High Score</th>";

if ($sort === "player") {
	echo "<th><a href='machines.php?region=$regionParam&season=$seasonParam&sort=player&dir=$oppositesortdir' class='player-link'>Player $sortchar</a></th>";
} else {
	echo "<th><a href='machines.php?region=$regionParam&season=$seasonParam&sort=player&dir=asc' class='player-link'>Player</th>";
}

	echo "</tr></thead>\n";

	$position = 0;
	$hiddenPositions = 0;
	$lastSortedValue = 0;

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
	$gamesPlayed = $row['GamesPlayed'];
	$appearances = $row['Appearances'];
	$machineId = $row['MachineId'];
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
			<td><a href='machine-info.php?machineid=$machineId&region=$regionParam&season=$seasonParam' class='player-link'>$machineName</a></td>
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
	When filtered by region, only scores achieved at league meets (within specified region) are considered.<br><br>
	<b>'Appearances'</b> is the number of league meets or league finals the game has appeared in.<br>
	<b>'Plays'</b> is the number of games played across those events.<br>
	For example, if Twilight Zone appeared at one meet with 12 players, then we would expect Appearances to be 1 and Plays to be 12.
	</p>

</div>

<!-- Footer -->
<?php include("includes/footer.inc"); ?>

</body>
</html>