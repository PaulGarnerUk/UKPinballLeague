<?php
	$playerid = htmlspecialchars($_GET["playerid"] ?? null);
	$playername = htmlspecialchars($_GET['player'] ?? null); 
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
<?php include("includes/header.inc"); ?>

<?php include("includes/sql.inc");

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

    <p><?=$playername?> has played <?=$totalGamesPlayed?> unique games across <?=$machinesPlayed?> different machines.</p>

</div>

<div class="panel">

    <h2>Machines played.</h2>
    <p>Click on any machine for a breakdown of all of <?=$playername?>'s recorded scores.</p>
	
    <div class="table-holder">
        <table class="responsive">
            <thead>
                <tr class="white">
                    <th width="400px">Machine</th>
                    <th width="50px">Played</th>
                    <th width="100px" class="paddidge" style="text-align:right">Best</th>
                    <!-- Add LeagueRank to this table too, then allow column sorting? -->
                </tr>
            </thead>
            <tbody>

<?php
$tsql ="
WITH BestScores AS
(
	SELECT
	MachineId,
	COUNT(Score.Id) AS 'GamesPlayed',
	MAX(Score) AS 'BestScore'
	FROM
	Score
	WHERE PlayerId = ? -- $playerid
	GROUP BY MachineId
)
SELECT
Machine.Id AS 'MachineId',
Machine.Name AS 'MachineName',
BestScores.GamesPlayed AS 'GamesPlayed',
BestScores.BestScore AS 'BestScore'
FROM BestScores
INNER JOIN Machine ON Machine.Id = BestScores.MachineId
ORDER BY Machine.Name
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
    $link="scores.php?playerid=$playerid&machineid=$machineId";

    			echo "<tr>
    				<td><a href=\"$link\" class=\"player-link\">$machineName</a></td>
    				<td>$gamesPlayed</td>
    				<td class='paddidge' style=\"text-align:right\">$bestScore</td>
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
