<?php
	require_once("includes/sql.inc");

	$playerid = htmlspecialchars($_GET["playerid"] ?? null);
	$playername = htmlspecialchars($_GET['player'] ?? null); 

   	$sort = htmlspecialchars($_GET["sort"] ?? "machine"); // sortby 'plays', 'machine', 'rank'
	$dir = htmlspecialchars($_GET["dir"] ?? "asc"); // sort direction ('asc' or 'desc')

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
		$orderby = "ORDER BY BestScores.GamesPlayed $sortdir, Machine.Name ASC";
	} else if ($sort === "rank") {
		$orderby = "ORDER BY RankedScores.ScoreRank $sortdir, Machine.Name ASC";
    } else if ($sort === "rank_percent") {
        $orderby = "ORDER BY RankPercent $sortdir, Machine.Name ASC";
	} else {
		$sort = "machine";
		$orderby = "ORDER BY Machine.Name $sortdir";
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>UK Pinball League - Player Info.</title>
<meta name="description" content="Player information." />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!--
Paul Garner

Best finish: 13th - 2019 Northern League  (later)

Paul has played 350 games across 321 machines:

Machine		Paul's Best   Rank  % Top
AC/DC           36,123,980      23   0.1%             <-- click machine for playerscores.php
Aerobatics       1,321,330     234  12.4%             (special row highlighting for rank 1)
-->

<!-- Header and menu -->
<?php include("includes/header.inc");

// At some future point this can be simplified. We only need the union'd select by name for legacy pages
$tsql ="
SELECT
Id,
Name
FROM
Player 
WHERE Id = ? -- $playerid
UNION
SELECT
Id,
Name
FROM
Player 
WHERE Name = ? -- $playername
";

// Perform query.
$result= sqlsrv_query($sqlConnection, $tsql, array($playerid, $playername));
if ($result == FALSE)
{
	echo "query borken.";
}

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$playerid = $row['Id'];
$playername = $row['Name'];

$tsql ="
SELECT
COUNT(Id) AS 'GamesPlayed',
COUNT(DISTINCT(MachineId)) AS 'MachinesPlayed'
FROM
Score
WHERE PlayerId = ? -- $playerid
";

// Perform query.
$result= sqlsrv_query($sqlConnection, $tsql, array($playerid));
if ($result == FALSE)
{
	echo "query borken.";
}

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$totalGamesPlayed = $row['GamesPlayed'];
$machinesPlayed = $row['MachinesPlayed'];

?>

<div class="panel">

    <h1><?=$playername?></h1>

    <p><?=$playername?> has played <?=$totalGamesPlayed?> games across <?=$machinesPlayed?> different machines.</p>

</div>

<div class="panel">

    <h2>Machines played.</h2>
    <p>Click on the played count for a breakdown of all of <?=$playername?>'s recorded scores on that machine.  Click on column headings to sort.</p>
	
    <div class="table-holder">
        <table>
            <thead>
                <tr class="white">
<?php
                    // machine sortable column header
                    if ($sort === "machine") {
	                    echo "<th><a href='player-info.php?playerid=$playerid&sort=machine&dir=$oppositesortdir' class='player-link'>Machine $sortchar</a></th>";
                    } else {
	                    echo "<th><a href='player-info.php?playerid=$playerid&sort=machine&dir=asc' class='player-link'>Machine</a></th>";
                    }

                    if ($sort === "plays") {
	                    echo "<th><a href='player-info.php?playerid=$playerid&sort=plays&dir=$oppositesortdir' class='player-link'>Plays $sortchar</a></th>";
                    } else  {
	                    echo "<th><a href='player-info.php?playerid=$playerid&sort=plays&dir=desc' class='player-link'>Plays</a></th>";
                    }

                    echo "<th class='score padright'>Best</th>";

                    if ($sort === "rank") {
	                    echo "<th class='score padright'><a href='player-info.php?playerid=$playerid&sort=rank&dir=$oppositesortdir' class='player-link'>Rank $sortchar</a></th>";
                    } else  {
	                    echo "<th class='score padright'><a href='player-info.php?playerid=$playerid&sort=rank&dir=asc' class='player-link'>Rank</a></th>";
                    }

                    if ($sort === "rank_percent") {
                        echo "<th class='score padright'><a href='player-info.php?playerid=$playerid&sort=rank_percent&dir=$oppositesortdir' class='player-link'>% Top $sortchar</a></th>";
                    } else  {
                        echo "<th class='score padright'><a href='player-info.php?playerid=$playerid&sort=rank_percent&dir=asc' class='player-link'>% Top</a></th>";
                    }
?>
                </tr>
            </thead>
            <tbody>

<?php
$tsql ="
DECLARE @playerId INTEGER = ?; -- $playerid

WITH BestScores AS
(
    SELECT
        MachineId,
        COUNT(Score.Id) AS 'GamesPlayed',
        MAX(Score) AS 'BestScore'
    FROM Score
    WHERE PlayerId = @playerId
    GROUP BY MachineId
),
RankedScores AS 
(
    SELECT
        MachineId,
        Score,
        RANK() OVER (PARTITION BY MachineId ORDER BY Score DESC) AS ScoreRank
    FROM Score
),
MaxRankStats AS
(
    SELECT
        MachineId,
        MAX(ScoreRank) AS MaxRank
    FROM RankedScores
    GROUP BY MachineId
)
SELECT
    Machine.Id AS 'MachineId',
    Machine.Name AS 'MachineName',
    BestScores.GamesPlayed AS 'GamesPlayed',
    BestScores.BestScore AS 'BestScore',
    RankedScores.ScoreRank AS 'BestScoreRank',
    MaxRankStats.MaxRank As 'TotalScores',
    ROUND(CAST(RankedScores.ScoreRank  AS FLOAT) / (MaxRankStats.MaxRank ) * 100 , 2) as 'RankPercent'
FROM BestScores
INNER JOIN Machine ON Machine.Id = BestScores.MachineId
INNER JOIN RankedScores ON RankedScores.MachineId = BestScores.MachineId AND RankedScores.Score = BestScores.BestScore
INNER JOIN MaxRankStats ON MaxRankStats.MachineId = BestScores.MachineId
$orderby
";

// Perform query.
$result= sqlsrv_query($sqlConnection, $tsql, array($playerid));
if ($result == FALSE)
{
	echo "query borken.";
}

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
{
	$machineId = $row['MachineId'];
    $machineName = $row['MachineName'];
    $gamesPlayed = $row['GamesPlayed'];
    $bestScore = number_format($row['BestScore']);
    $bestScoreRank = number_format($row['BestScoreRank']);
    $totalScores = number_format($row['TotalScores']);
    $rankPercent = number_format($row['RankPercent'], 2);
    $machineLink = "machine-info.php?machineid=$machineId";
    $scoresLink = "scores.php?playerid=$playerid&machineid=$machineId";

    			echo "<tr>
    				<td><a href='$machineLink' class='player-link'>$machineName</a></td>
    				<td><a href='$scoresLink' class='player-link'>$gamesPlayed</a></td>
    				<td class='score padright'>$bestScore</td>
    				<td class='score padright'>$bestScoreRank / $totalScores</td>
                    <td class='score padright'>$rankPercent%</td>
    			</tr>\n";
}

sqlsrv_free_stmt($result);
?>

            </tbody>
        </table>
    </div>
</div>

<!-- Header and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>


<!-- Too messy now. Add this to select 'Best finish: 1st (36 times) Most recent: _Northern League 2020 Meet 4_
But do it in a function that returns an object of 'player info' or something that runs numerous queries and then returns an object to use on the page.

SELECT 
* 
FROM Result
INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Result.CompetitionId 
WHERE Position IN
(
  -- select single best position for this player in a league meet
  SELECT MIN(Position) 
  FROM Result
  INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Result.CompetitionId 
  WHERE PlayerId = 125
)
AND PlayerId = 125
ORDER BY Position ASC, LeagueMeet.SeasonId DESC, LeagueMeet.MeetNumber DESC

-->