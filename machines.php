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
    <p>All machines played to date in the UK Pinball League. Click on a machine for more info.</p>

<?php include("includes/sql.inc");

// At some future point this can be simplified. We only need the union'd select by name for legacy pages
$tsql ="
SELECT
Machine.Name AS 'Machine',
COUNT(MachineId) AS 'GamesPlayed'
FROM Score
INNER JOIN Machine ON Machine.Id = Score.MachineId
GROUP BY Machine.Name
ORDER BY COUNT(MachineId) DESC
";

// Perform query.
$result= sqlsrv_query($sqlConnection, $tsql);
if ($result == FALSE)
{
	echo "query borken.";
}

echo "<table class='machines'>";

echo "<thead>
			<tr class='white'><th>&nbsp;</th><th>Machine</th><th>Plays</th></tr>
		</thead>\n";

		$position = 0;
		$hiddenPositions = 0;
		$lastGamesPlayed = 0;
	
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
{
	$gamesPlayed = $row['GamesPlayed'];
	$machineName = $row['Machine'];

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
	
	echo "<tr><td>$position</td><td>$machineName</td><td>$gamesPlayed</td></tr>\n";
}
echo "</table>";

sqlsrv_free_stmt($result);
?>

</div>

<!-- Footer -->
<?php include("includes/footer.inc"); ?>

</body>
</html>