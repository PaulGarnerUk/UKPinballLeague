<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="A list of highscores and average scores for every machine played in the UK Pinball League" />
<title>UK Pinball League – High scores and average scores</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<script language="JavaScript" type="text/javascript">
<!--
function getplayer ( selectedtype )
{
  document.playerform.player.value = selectedtype ;
  document.playerform.submit() ;
}
-->
</script>

<?php include("includes/header.inc"); ?>

<form name="playerform" action="player-highscores.php" method="get">
<input type="hidden" name="player" />

<!-- Content -->

<div class="panel">

	<h1>UKPL High &amp; Average Scores</h1>

	<table class="rankings">

		<thead>
			<tr class="white">
				<th>Machine</th>
				<th>High Score</th>
                <th>Player</th>
				<th>Meet</th>
				<th>UKPL average</th>
 			</tr>
		</thead>


<?php include("includes/sql.inc");

$tsql ="
WITH AverageScore AS
(
	SELECT 
	MachineId,
	AVG(Score.Score) AS 'Average'
	FROM Score
	GROUP BY MachineId
)
SELECT 
MaxScore.MachineId, 
Machine.Name AS 'MachineName',
MaxScore.Score AS HighScore, 
AverageScore.Average AS AverageScore,
MaxScore.PlayerId,
Player.Name AS 'PlayerName',
MaxScore.CompetitionId,
Season.Year AS 'SeasonYear',
Season.SeasonNumber AS 'SeasonNumber',
Region.Name AS 'RegionName',
Region.Synonym AS 'RegionSynonym',
LeagueMeet.MeetNumber AS 'LeagueMeetNumber',
LeagueFinal.Description AS 'LeagueFinalDescription'
FROM 
(
	SELECT
	CompetitionId,
	MachineId, 
	Score, 
	PlayerId,
    ROW_NUMBER() OVER (PARTITION BY MachineId ORDER BY Score DESC) Rank
    FROM Score
) MaxScore
INNER JOIN AverageScore on AverageScore.MachineId = MaxScore.MachineId
INNER JOIN Machine ON Machine.Id = MaxScore.MachineId
INNER JOIN Player ON Player.Id = PlayerId
LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = MaxScore.CompetitionId
LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = MaxScore.CompetitionId
INNER JOIN Season ON Season.Id = (COALESCE(LeagueMeet.SeasonId, LeagueFinal.SeasonId))
LEFT OUTER JOIN Region ON Region.Id = LeagueMeet.RegionId
WHERE MaxScore.Rank = 1 
ORDER BY Machine.Name
";

// Perform query.
$result= sqlsrv_query($sqlConnection, $tsql);
if ($result == FALSE)
{
	echo "query borken.";
}

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
{
	$machine = $row['MachineName'];
	$highscore = number_format($row['HighScore']);
	$player = $row['PlayerName'];
	$avgscore = number_format($row['AverageScore']);

	$scoreSeasonNumber = $row['SeasonNumber'];
	$scoreSeasonYear = $row['SeasonYear'];
	$scoreRegion = $row['RegionName'];
	$scoreRegionSynonym = $row['RegionSynonym'];
	$scoreLeagueMeetNumber = $row['LeagueMeetNumber'];

	$leagueFinal = $row['LeagueFinalDescription'];
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


	echo "<tr>\n
		<td><span style='white-space:nowrap'>$machine</span></td>\n		
		<td><span style='white-space:nowrap'>$highscore</span></td>\n
		<td><span style='white-space:nowrap'><a href=\"javascript:getplayer('$player')\" class='player-link'>$player</a></span></td>\n
		<td><span style='white-space:nowrap'>$event</span></td>\n
		<td class='lastcolumn'><span style='white-space:nowrap'>$avgscore</span></td>\n
	</tr>\n";
}

echo "</table>\n";

sqlsrv_free_stmt($result);
?>

</div>

<!-- footer -->
<?php include("includes/footer.inc"); ?>

</body>
  </html>