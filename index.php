<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Page description" />
<title>UK Pinball League - Home</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php include("includes/header.inc"); ?>

<div class="panel-image"><img src="images/homepage-image1.jpg" width="100%" /></div>


<div class="homepage-flex-container">

<div class='homepage-flex-panel'>

	<h1>What's it all about?</h1>

	<p>The UK Pinball League was set up in 2006 as the first pinball league in the UK. It is currently divided into six separate regions - South West, Midlands, London &amp; South East, Northern, Scottish and Irish.</p>

	<p>The UK Pinball League offers you a chance to play a wide variety of pinball machines against  players of varying standards in a friendly and sociable atmosphere.</p>

	<p>Anyone is welcome to join the UK Pinball League at any time of the season. Please <a href="contacts.php" class="link">contact</a> the co-ordinator of your region for more information.</p>

	<p>Playing in the UK Pinball League earns you points in your regional league. At the end of the season, the top players from each regional league qualify for the national league final for the chance to win the UK Pinball League.</p>

	<p>We have a list of common <a href="faq.php" class="link">FAQs</a> which should answer any questions that you have. Please <a href="contacts.php" class="link">contact</a> the co-ordinator of your region with any other queries.</p>

	<p>Competing in the UK Pinball League also gains you World Pinball Player Ranking (<a href="https://www.ifpapinball.com/rankings/country.php" class="link" target="_blank">WPPR</a>) points that rank you against all other players worldwide.</p>

	<p><b>What are you waiting for?  Join the UK Pinball League now!</b></p>

</div>


<?php

include("includes/sql.inc");

$tsql = "
SELECT TOP(20)
Score.MachineId AS 'MachineId',
Machine.Name AS 'MachineName',
COUNT(Score.Score) AS 'GamesPlayed'
FROM Score
INNER JOIN Machine ON Machine.Id = Score.MachineId
GROUP BY MachineId, Machine.Name
ORDER BY COUNT(Score.Score) DESC, Machine.Name ASC
";

// Perform query.
$result= sqlsrv_query($sqlConnection, $tsql);
if ($result == FALSE)
{
	echo "query borken.";
}

echo "<div class='homepage-flex-panel'>";

echo "<h2 class='homepage'>Popular Machines</h2>";
echo "<table class='homepage'>";

echo "<thead>
			<tr class='white'>
				<th class='homepage-table'>#</th>
                <th class='homepage-table'>Machine</th>
         		<th class='homepage-table'>Plays</th>
 			</tr>
		</thead>";


$position = 0;
$hiddenPositions = 0;
$lastGamesPlayed = 0;
	
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
	$gamesPlayed = $row['GamesPlayed'];
	$machineId = $row['MachineId'];
	$machineName = $row['MachineName'];

	if ($gamesPlayed != $lastGamesPlayed) 
	{
		$lastGamesPlayed = $gamesPlayed;
		$position = $hiddenPositions + $position + 1;
		$hiddenPositions = 0;
	}
	else
	{
		++$hiddenPositions;
	}
	
	echo "<tr><td class='homepage-table'>$position</td>
	<td class='homepage-table'><a href='machine-info.php?machineid=$machineId' class='player-link'>$machineName</a></td>
	<td class='homepage-table'>$gamesPlayed</td></tr>\n";
}
echo "</table>\n";

echo "<p><a href='machines.php?region=all&season=all&sort=plays&dir=desc' class='homepagesmall'>See full list</a></p>";

echo "</div>";

echo "</div>"; // flex container
?>

<?php

$tsql="
SELECT 
COUNT(DISTINCT(Score.PlayerId)) AS 'PlayerCount',
COUNT(DISTINCT(Score.MachineId)) AS 'MachineCount',
SUM(Score) AS 'TotalScore'
FROM Score
";

// Perform query.
$result= sqlsrv_query($sqlConnection, $tsql);
if ($result == FALSE) { echo "query borken."; }

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$playerCount = number_format($row['PlayerCount']);
$machineCount = number_format($row['MachineCount']);
$totalScore = number_format($row['TotalScore']);

echo "<div class='panel-homepage-stats'>";
echo "<center><h2>$playerCount league players - $machineCount pinball machines - $totalScore points scored so far...</h2></center>";
echo "</div>";

?>

<!-- Header and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>