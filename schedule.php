<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Schedule of league meets." />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>UK Pinball League - Schedule</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php include("includes/header.inc"); ?>


<div class="panel">

	<h1>UKPL Schedule - Season 16 (2023)</h1>

	<p class="firstline">The UK Pinball League season period is based on a calendar year.  Each region usually consists of up to six meets. Meets for the 16th season are currently being arranged and will be updated here as they are confirmed.</p>
<!--	<p>At the end of the league season the top players from each region are invited to compete in a finals tournament, usually held over a single day during the <a href="http://www.ukpinfest.com/" class="link">UK Pinfest</a> show. For the 2022 season, finals will be played at Pinfest on <b>Saturday 27th August</b>. </p> -->
	<p>For a full calendar of other pinball events see the excellent <a href="http://www.pinballnews.com/diary/index.html" class="link" target="_blank">Show Diary</a> page on the <a href="http://www.pinballnews.com" class="link" target="_blank">Pinball News</a> website.</p>

</div>

<?php
	include("includes/envvars.inc");

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

	// Schedule info 
	$tsql= "
SELECT
Region.Id AS 'RegionId',
Region.Name AS 'RegionName',
Region.Synonym AS 'RegionSynonym',
LeagueMeet.Id AS 'LeagueMeetId',
LeagueMeet.MeetNumber AS 'LeagueMeetNumber',
LeagueMeet.Status AS 'LeagueMeetStatus',
LeagueMeet.Date AS 'LeagueMeetDate',
LeagueMeet.PracticeStart AS 'LeagueMeetPracticeStart',
LeagueMeet.PracticeEnd AS 'LeagueMeetPracticeEnd',
LeagueMeet.CompetitionStart AS 'LeagueMeetCompetitionStart',
LeagueMeet.CompetitionEnd AS 'LeagueMeetCompetitionEnd',
LeagueMeet.Host AS 'LeagueMeetHost',
LeagueMeet.PublicVenue AS 'LeagueMeetPublicVenue',
COALESCE(LeagueMeet.Location, LeagueMeet.Address) AS 'LeagueMeetLocation'
FROM Region --LeagueMeet 
LEFT OUTER JOIN LeagueMeet ON 
  LeagueMeet.RegionId = Region.Id 
  AND LeagueMeet.SeasonId = 16
  AND (LeagueMeet.Status <> 4 OR LeagueMeet.Date > GETDATE()) -- ignore cancelled events (unless they are in the future)
ORDER BY Region.SortOrder, MeetNumber
";
/*
SELECT
Region.Id AS 'RegionId',
Region.Name AS 'RegionName',
Region.Synonym AS 'RegionSynonym',
LeagueMeet.Id AS 'LeagueMeetId',
LeagueMeet.MeetNumber AS 'LeagueMeetNumber',
LeagueMeet.Status AS 'LeagueMeetStatus',
LeagueMeet.Date AS 'LeagueMeetDate',
LeagueMeet.PracticeStart AS 'LeagueMeetPracticeStart',
LeagueMeet.PracticeEnd AS 'LeagueMeetPracticeEnd',
LeagueMeet.CompetitionStart AS 'LeagueMeetCompetitionStart',
LeagueMeet.CompetitionEnd AS 'LeagueMeetCompetitionEnd',
LeagueMeet.Host AS 'LeagueMeetHost',
LeagueMeet.PublicVenue AS 'LeagueMeetPublicVenue',
COALESCE(LeagueMeet.Location, LeagueMeet.Address) AS 'LeagueMeetLocation'
FROM LeagueMeet 
INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
WHERE Season.SeasonNumber = ? -- $currentseason
AND LeagueMeet.Status <> 4 OR LeagueMeet.Date > GETDATE() -- ignore cancelled events (unless they are in the future)
ORDER BY Region.SortOrder, MeetNumber
";*/
	// Perform query with parameterised values.
	$result= sqlsrv_query($conn, $tsql, array($currentseason));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$lastRegionName = "";

	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$regionName = $row['RegionName'];
		$regionSynonym = $row['RegionSynonym'];
		$leagueMeetNumber = $row['LeagueMeetNumber'];

		if ($leagueMeetNumber > 0)
		{
			$leagueMeetDate = $row['LeagueMeetDate']->format('D jS M Y');
			$leagueMeetHost = $row['LeagueMeetHost'];
			$leagueMeetStatus = $row['LeagueMeetStatus'];
			$leagueMeetLocation = $row['LeagueMeetLocation'];

			$resultsLink = "leaguemeet.php?season=$currentseason&region=$regionSynonym&meet=$leagueMeetNumber";
			$infoLink = "schedule-info.php?season=$currentseason&region=$regionSynonym&meet=$leagueMeetNumber";
		}

		// If region changed..
		if ($regionName !== $lastRegionName)
		{
			if ($lastRegionName !== "")
			{
				// Close table, and table-holder div.
				echo "</tbody></table></div>";

				// Close the panel for the last region
				echo "</div>";
			}

			// Start a new panel around the region
			echo "<div class='panel'>";
			echo "<h2 class='schedule'>$regionName League</h2>";

			// Start a table and write header
			echo "<div class='table-holder'>";
			echo "<table class='schedule-table'>";

			if ($leagueMeetNumber > 0)
			{
				echo "<col style='width:15%'>";
				echo "<col style='width:10%'>"; // 
				echo "<col style='width:40%' class='mobilehide'>"; // location
				echo "<col style='width:20%'>";
				echo "<col style='width:15%'>";
				echo "<thead>";
				echo "<tr class='white'>";
				echo "<th>Date</th>";
				echo "<th></th>"; // 'Rescheduled' or 'Cancelled' where applicable.
				echo "<th class='mobilehide'>Location</th>"; // Does not appear on mobile
				echo "<th>Host</th>";
				echo "<th class='padright'>&nbsp;</th>"; // 'More Info' or 'Results'
				echo "</tr>";
				echo "</thead>";
			}
			else 
			{
				echo "<p>Awaiting schedule.</p>";
			}

			// Start body
			echo "<tbody>";
		}

		// Meet info
		if ($leagueMeetNumber > 0)
		{
			if ($leagueMeetStatus == 4)
			{
				echo "<tr class='strikeout'>";
			}
			else
			{
				echo "<tr>";
			}

			echo "<td>$leagueMeetDate</td>";

			if ($leagueMeetStatus == 5)
			{
				echo "<td>(R)</td>";
			}
			else
			{
				echo "<td></td>";
			}
			echo "<td class='mobilehide'>$leagueMeetLocation</td>";
			echo "<td>$leagueMeetHost</td>";

			if ($leagueMeetStatus == 3)
			{
				echo "<td><a href='$resultsLink' class='player-link padright'>Results</a></td>";
			}
			else if ($leagueMeetStatus == 4)
			{
				echo "<td><b>Cancelled</b></td>";
			}
			else
			{
				echo "<td><a href='$infoLink' class='player-link padright'>Info</a></td>";
			}

			echo "</tr>";
		}
		$lastRegionName = $regionName;
	}

	// Close table, and table-holder div.
	echo "</tbody></table></div>";
	// Close the panel for the last region
	echo "</div>";
?>


<!-- Footer and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>