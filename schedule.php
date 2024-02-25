<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Schedule of league meets." />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>UK Pinball League - Schedule</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php 
include("includes/header.inc");
include("includes/envvars.inc");
?>


<div class="panel">

	<h1>UKPL Schedule - Season <?=$currentseason?> (2024)</h1>

	<p class="firstline">The UK Pinball League season period is based on a calendar year.  Each region usually consists of up to six meets. Meets for the <?=$currentseason?>th season are currently being arranged and will be updated here as they are confirmed.</p>
	<p>At the end of the league season (and after any regional finals event) the top two players from each region are invited to compete in a national finals tournament, held over a single day during the <a href="http://www.ukpinfest.com/" class="link">UK Pinfest</a> show.</p> 
	<p>For a full calendar of other pinball events see Lecari's excellent <a href="https://www.pinballinfo.com/community/threads/uk-events-diary-2024.54814/" class="link" target="_blank">2024 Events</a> page on the <a href="http://www.pinballinfo.com" class="link" target="_blank">Pinball Info forums.</a></p>

</div>

<?php
	

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

	// Schedule 
	$tsql= "
DECLARE @CurrentSeason INT = ? -- $currentseason

SELECT
Region.SortOrder,
Region.Id AS 'RegionId',
Region.Name AS 'RegionName',
Region.Synonym AS 'RegionSynonym',
0 AS 'CompetitionType', -- LeagueMeet
LeagueMeet.Id AS 'LeagueMeetId',
LeagueMeet.MeetNumber AS 'LeagueMeetNumber',
LeagueMeet.Status AS 'Status',
LeagueMeet.Date AS 'Date',
LeagueMeet.Host AS 'Host',
COALESCE(LeagueMeet.Location, LeagueMeet.Address) AS 'Location',
NULL AS 'LeagueRegionalFinalId'

FROM Region 
LEFT OUTER JOIN LeagueMeet ON 
  LeagueMeet.RegionId = Region.Id 
  AND LeagueMeet.SeasonId = @Currentseason
  AND (LeagueMeet.Status <> 4 OR LeagueMeet.Date > GETDATE()) -- ignore cancelled events (unless they are in the future)

UNION ALL

SELECT
Region.SortOrder,
Region.Id AS 'RegionId',
Region.Name AS 'RegionName',
Region.Synonym AS 'RegionSynonym',
2 AS 'CompetitionType', -- RegionalFinal
NULL AS 'LeagueMeetId',  -- Placeholder for LeagueMeet columns
NULL AS 'LeagueMeetNumber',  -- Placeholder for LeagueMeet columns
LeagueRegionalFinal.Status AS 'Status',
LeagueRegionalFinal.Date AS 'Date',
LeagueRegionalFinal.Host AS 'Host',  -- Placeholder for LeagueMeet columns
COALESCE(LeagueRegionalFinal.Location, LeagueRegionalFinal.Address) AS 'Location',
LeagueRegionalFinal.Id AS 'LeagueRegionalFinalId'

FROM Region
INNER JOIN
    LeagueRegionalFinal ON LeagueRegionalFinal.RegionId = Region.Id
	AND LeagueRegionalFinal.SeasonId = @Currentseason

ORDER BY
    Region.SortOrder,
	Date,
    LeagueRegionalFinalId ASC

";

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
		$regionalFinalId = $row['LeagueRegionalFinalId'];

		if ($leagueMeetNumber > 0)
		{
			$date = $row['Date']->format('D jS M Y');
			$leagueMeetStatus = $row['Status'];
			$leagueMeetLocation = $row['Location'];
			$leagueMeetHost = $row['Host'];

			$resultsLink = "leaguemeet.php?season=$currentseason&region=$regionSynonym&meet=$leagueMeetNumber";
			$infoLink = "schedule-info.php?season=$currentseason&region=$regionSynonym&meet=$leagueMeetNumber";
		}

		if ($regionalFinalId > 0)
		{
			$date = $row['Date']->format('D jS M Y');
			$leagueMeetLocation = 'By Invitation';
			$leagueMeetHost = 'Regional Finals';
			
			$infoLink = null; // still figuring this out
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
		if ($leagueMeetNumber > 0 || $regionalFinalId > 0)
		{
			if ($leagueMeetStatus == 4)
			{
				echo "<tr class='strikeout'>";
			}
			else
			{
				echo "<tr>";
			}

			echo "<td>$date</td>";

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
			else if ($infoLink !== null)
			{
				echo "<td><a href='$infoLink' class='player-link padright'>Info</a></td>";
			}
			else
			{
				echo "<td>&nbsp;</td>";
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