<?php
	require_once("includes/sql.inc");
	require_once("functions/regioninfo.inc");
    require_once("functions/seasoninfo.inc");

	$machineIdParam = htmlspecialchars($_GET["machineid"] ?? null);
    $regionParam = htmlspecialchars($_GET["region"] ?? "all"); // region synonym ('n', 'm' etc) or 'all' for all regions. Filters scores
    $seasonParam = htmlspecialchars($_GET["season"] ?? "all"); // season number, or 'all' for all seasons.
    $competitionIdParam = htmlspecialchars($_GET["competitionid"] ?? null); // Highlights score made at specified competition

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

    // Run inital query for machine info and stats
    $filterClause = "";
    $tsqlParams = "";

    if ($region->regionId > 0) {
	    $filterClause = "AND (LeagueMeet.RegionId = @RegionId OR LeagueFinal.Id > 0)\r\n"; 
	    $tsqlParams = "DECLARE @RegionId INT = $region->regionId;\r\n";
    }

    if ($season->seasonId > 0) {
	    $filterClause .= "AND (LeagueMeet.SeasonId = @SeasonId OR LeagueFinal.SeasonId = @SeasonId)\r\n";
	    $tsqlParams .= "DECLARE @SeasonId INT = $season->seasonId;\r\n";
    }

    $tsql = "
DECLARE @MachineId INT = ?;
$tsqlParams

SELECT
Machine.Name AS 'MachineName',
AVG(Score) AS 'AverageScore',
COUNT(Score) AS 'GamesPlayed',
COUNT(DISTINCT(LeagueMeet.Id)) + COUNT(DISTINCT(LeagueFinal.Id))  AS 'Appearances',
COUNT(DISTINCT(LeagueMeet.Id)) AS 'MeetsCount',
COUNT(DISTINCT(LeagueFinal.Id)) AS 'FinalsCount'
FROM Score
INNER JOIN Machine ON Machine.Id = Score.MachineId
LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
WHERE MachineId = @MachineId
$filterClause
GROUP BY Machine.Name
";

$result= sqlsrv_query($sqlConnection, $tsql, array($machineIdParam));
if ($result == FALSE)
{
	echo "query borken.";
}

$machineRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if ($machineRow != true)
{
    echo "Unexpected machine id";
}

$machineName = $machineRow['MachineName'];
$averageScore = number_format($machineRow['AverageScore']);
$gamesPlayed = number_format($machineRow['GamesPlayed']);
$meetsCount = number_format($machineRow['MeetsCount']);
$finalsCount = number_format($machineRow['FinalsCount']);
$appearances = number_format($machineRow['Appearances']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Page description" />
<title>UK Pinball League - <?=$machineName;?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<?php include("includes/header.inc"); ?>

<div class="panel">

    <h1><?=$machineName;?></h1>

    <p>Appearances : <?=$appearances?> (<?=$meetsCount?> league meets, and <?=$finalsCount?> league finals)</p>
    <p>Total games played : <?=$gamesPlayed?></p>
	<p>Average score : <?=$averageScore?></p>

</div>

<div class="panel">

    <h2>Top Scores</h2>
	
    <div class="table-holder">
        <table class="responsive">
            <thead>
                <tr class="white">
                    <th>Rank</th>
                    <th>Score</th>
                    <th>Player</th>
                    <th>Event</th>
                </tr>
            </thead>
            <tbody>

<?php
$tsql = "
DECLARE @MachineId INT = ?;
$tsqlParams

SELECT TOP(50)
RANK() OVER(ORDER BY Score.Score DESC, Player.Name ASC) AS 'Rank',
Score.Score AS 'Score',
Score.CompetitionId AS 'CompetitionId',
Competition.Name AS 'EventName',
Score.PlayerId AS 'PlayerId',
Player.Name AS 'PlayerName',
Season.SeasonNumber AS 'SeasonNumber',
Region.Synonym AS 'RegionSynonym',
LeagueMeet.MeetNumber AS 'MeetNumber'
FROM Score
INNER JOIN Machine ON Machine.Id = Score.MachineId
INNER JOIN Player ON Player.Id = Score.PlayerId
INNER JOIN Competition ON Competition.Id = Score.CompetitionId
LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
LEFT OUTER JOIN Region ON Region.Id = LeagueMeet.RegionId
LEFT OUTER JOIN Season ON Season.Id = LeagueMeet.SeasonId
WHERE MachineId = @MachineId
$filterClause
ORDER BY Score.Score DESC
";

$result= sqlsrv_query($sqlConnection, $tsql, array($machineIdParam));
if ($result == FALSE)
{
	echo "query borken.";
}

while ($scoreRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
{
    $rank = number_format($scoreRow['Rank']);
    $score = number_format($scoreRow['Score']);
    $competitionId = $scoreRow['CompetitionId'];
    $eventName = $scoreRow['EventName'];
    $playerId = $scoreRow['PlayerId'];
    $playerName = $scoreRow['PlayerName'];
    $playerLink = "player-info.php?playerid=$playerId";
    $seasonNumber = $scoreRow['SeasonNumber'];
    $regionSynonym = $scoreRow['RegionSynonym'];
    $meetNumber = $scoreRow['MeetNumber'];
    $eventLink = "leaguemeet.php?season=$seasonNumber&region=$regionSynonym&meet=$meetNumber";

    echo "<tr>
        <td>$rank</td>
        <td>$score</td>
        <td><a href=\"$playerLink\" class=\"player-link\">$playerName</a></td>";
        if ($meetNumber > 0)
        {
            echo "<td><a href=\"$eventLink\" class=\"player-link\">$eventName</a></td>";
        }
        else 
        {
	        echo "<td>$eventName</td>";
        }

        echo "</tr>\n";
}

?>
            </tbody>
        </table>
    </div>
</div>

<!-- Header and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>