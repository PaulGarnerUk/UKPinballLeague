<?php
	$season = htmlspecialchars($_GET["season"]);
	$region = htmlspecialchars($_GET["region"]);
	$meet = htmlspecialchars($_GET["meet"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Page description" />
<title>UK Pinball League - Meet Info</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<?php 
    // Header and menu
	include("includes/header.inc"); 
	include("includes/envvars.inc");
	include("includes/obfuscateEmail.inc");

	$connectionOptions = array(
		"Database" => $sqldbname,
		"Uid" => $sqluser,
		"PWD" => $sqlpassword
	);

	$conn = sqlsrv_connect($sqlserver, $connectionOptions);
	if( $conn === false ) 
	{
		echo "connection borken.";
		// die( print_r( sqlsrv_errors(), true));
	}

		$tsql= "
SELECT
Season.SeasonNumber,
Region.Name AS 'RegionName',
Region.Director AS 'RegionDirector',
Region.DirectorEmail AS 'RegionDirectorEmail',
LeagueMeet.CompetitionId AS 'CompetitionId',
LeagueMeet.MeetNumber AS 'MeetNumber',
LeagueMeet.Date AS 'MeetDate',
LeagueMeet.PracticeStart AS 'PracticeStart',
LeagueMeet.PracticeEnd AS 'PracticeEnd',
LeagueMeet.CompetitionStart AS 'CompetitionStart',
LeagueMeet.CompetitionEnd AS 'CompetitionEnd',
LeagueMeet.Host AS 'Host',
LeagueMeet.Address AS 'Address',
LeagueMeet.PublicVenue AS 'Public'
FROM LeagueMeet
INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
WHERE Region.Synonym = ? -- $region 
AND Season.SeasonNumber = ? -- $season 
AND LeagueMeet.MeetNumber = ? -- $meet 
";

	// Perform query with parameterised values.
	$result= sqlsrv_query($conn, $tsql, array($region, $season, $meet));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	
	if (sqlsrv_has_rows($result) === true)
	{
		$meetConfirmed = true;

		$regionName = $row['RegionName'];
		$regionDirector = $row['RegionDirector'];
		$regionDirectorEmailLink = obfuscateEmailLink($row['RegionDirectorEmail']);
		//$regionSynonym = $row['RegionSynonym'];
		$meetNumber = $row['MeetNumber'];
		$meetDate = $row['MeetDate']->format('D jS M Y');
		$practiceStart = $row['PracticeStart']->format('g:i a');
		$practiceEnd = $row['PracticeEnd']->format('g:i a');
		$competitionStart = $row['CompetitionStart']->format('g:i a');
		$competitionEnd = $row['CompetitionEnd']->format('g:i a');
		$competitionId = $row['CompetitionId'];

		$meetHost = $row['Host'];
		//$meetStatus = $row['LeagueMeetStatus'];
		$meetAddress = $row['Address'];
		$publicVenue = $row['Public'];
	}
	else 
	{
		echo "<h1>This meet is yet to be confirmed.</h1>";
		$meetConfirmed = false;
	}
?>

<?php 
	if ($meetConfirmed === true)
	{
		echo "<div class='panel'>";

		echo "<h1>$regionName League, Meet #$meet</h1>"; 
		echo "<h2>$meetDate</h2>"; 
		echo "<br>";
		echo "<p><b>Practice: </b>$practiceStart - $practiceEnd</p>";
		echo "<p><b>Competion: </b>$competitionStart - $competitionEnd</p>";
		echo "<p><b>Host: </b>$meetHost</p>";

		if ($publicVenue === 1)
		{
			$addressLink = "https://www.google.com/maps?saddr=My+Location&daddr=$meetAddress";
			echo "<p><b>Location:</b> $meetAddress <a href='$addressLink'>(Directions)</a><br>";
			echo "This is a venue open to the public.</p>";
		}
		else 
		{
			echo "<p><b>Location:</b> $meetAddress<br>";
			echo "This league meet is at a private venue. Please email the region co-ordinator $regionDirector for the full address : $regionDirectorEmailLink </p>";
		}

		echo "</div>";


		echo "<div class='panel'>
				<h2>League Games</h2>
				";

		$tsql = "
		SELECT
		Machine.Id AS 'MachineId',
		Machine.Name AS 'MachineName',
		Machine.OpdbId AS 'MachineOpdbId'
		FROM CompetitionMachine
		INNER JOIN Machine ON Machine.Id = CompetitionMachine.MachineId
		WHERE CompetitionMachine.CompetitionId = ? -- $competitionId
		";

		// Perform query with parameterised values.
		$result= sqlsrv_query($conn, $tsql, array($competitionId));
		if ($result == FALSE)
		{
			echo "query borken.";
		}

		if (sqlsrv_has_rows($result) === true)
		{
			echo "<p>Subject to change.</p>
					<div class='table-holder'>
						<table class='responsive'>
							<thead>
								<tr class='white'>
									<th>Game</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
						";
			while ($machineRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
			{
				$machineId = $machineRow['MachineId'];
				$machineName = $machineRow['MachineName'];
				$machineOpdbId = $machineRow['MachineOpdbId'];
				$tipsLink = "https://pintips.net/opdb/$machineOpdbId";

				echo "<tr>
						<td><a href='machine-info.php?machineid=$machineId' class='player-link'>$machineName</a></td>
						<td><a href=\"$tipsLink\" class='player-link'>Tips</a></td>
						</tr>";
			}

			echo "</tbody>
					</table>
					</div>
					";
		}
		else 
		{
			echo "<p>To be determined.</p>";
		}

		echo "</div>"; // end panel

	} // end if ($meetConfirmed === true)
?>


<!-- Header and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>