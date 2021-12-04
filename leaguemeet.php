<?php
	$season = htmlspecialchars($_GET["season"]);
	$region = htmlspecialchars($_GET["region"]);
	$meet = htmlspecialchars($_GET["meet"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>UK Pinball League - League Meet Results.</title>
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

<!-- Content -->
<div class="panel">

	<?php 
		include("header.inc"); 
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

		 $tsql= "
	SELECT
	Season.SeasonNumber,
	Region.Name AS RegionName,
	LeagueMeet.MeetNumber,
	CONVERT(varchar, LeagueMeet.Date, 103) AS Date,
	LeagueMeet.Host
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

		$leagueMeetRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		$leagueMeetRegionName = $leagueMeetRow['RegionName'];
		$leagueMeetHostName = $leagueMeetRow['Host'];
		$leagueMeetDate = $leagueMeetRow['Date'];
	?>

	<?php 
		echo "<h1>$leagueMeetRegionName League, Season $season, Meet #$meet</h1>"; 
	?>

	<p>
		<script>
			document.write('<a href="' + document.referrer + '" class="link">Back to League Table</a>');
		</script>
	</p>

	<!--
	<form name="playerform" action="player-info.php" method="get">
	<input type="hidden" name="player" />
	-->
<?php

	// Get all scores at meet
	$tsql="
SELECT
RANK() OVER (PARTITION BY MachineId ORDER BY Score DESC) AS Rank,
Score.PlayerId AS PlayerId,
Player.Name AS PlayerName,
Score.MachineId AS MachineId,
Machine.Name AS MachineName,
Score.CompetitionId AS CompetitionId,
Score.Score AS GameScore
FROM Score 
INNER JOIN Player ON Player.Id = Score.PlayerId
INNER JOIN Machine ON Machine.Id = Score.MachineId
INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
WHERE Region.Synonym = ? -- $region 
AND Season.SeasonNumber = ? -- $season 
AND LeagueMeet.MeetNumber = ? -- $meet 
ORDER BY Machine.Name, GameScore desc, PlayerName
	";

	// Perform query with parameterised values.
	$result= sqlsrv_query($conn, $tsql, array($region, $season, $meet));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$lastMachineName = "";

	while ($scoreRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$scorePlayerId = $scoreRow['PlayerId'];
		$scorePlayerName = $scoreRow['PlayerName'];
		$scoreMachineId = $scoreRow['MachineId'];
		$scoreMachineName = $scoreRow['MachineName'];
		$scoreRank = $scoreRow['Rank'];
		$scoreCompetitionId = $scoreRow['CompetitionId'];
		$scoreGameScore = number_format($scoreRow['GameScore']);

		// write new table header if this is a new machine
		if ($scoreMachineName !== $lastMachineName)
		{
			// Close off the last table (unless this was the first table)
			if ($lastMachineName !== "")
			{
				echo "</table>";
				echo "</div>";
			}

			echo "<div class='meet-table-holder'>";
        
			echo "<h2>$scoreMachineName</h2>";
        
			echo "<table>";
        
			echo "<thead>
				<tr class='white'>
					<th class='meetposition'>&nbsp;</th>
					<th class='meetplayer'>Player</th>
					<th class='meetscore'>Score</th>
 				</tr>
			</thead>";

			$lastMachineName = $scoreMachineName;
			$counter = 0;
		}

		$counter++;
		$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

		$scoreLink = "scores.php?playerid=$scorePlayerId&machineid=$scoreMachineId&competitionid=$scoreCompetitionId";

        echo "<tr class='border'>\n
			<td class='meetposition' bgcolor='".$bgcolor."'>$scoreRank</td>\n
			<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"$scoreLink\" class='player-link'>$scorePlayerName</a></td>\n
			<td class='meetscore' bgcolor='".$bgcolor."'>$scoreGameScore</td>\n
        </tr>\n";
	}

	// Close off the last table
	echo "</table>";
	echo "</div>";


	// Overall results
	$tsql="
SELECT
Result.Position AS 'Rank',
Player.Name AS 'PlayerName',
Result.Score AS 'Score',
Result.Points AS 'Points'
from Result
INNER JOIN LeagueMeet on LeagueMeet.CompetitionId = Result.CompetitionId
INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
INNER JOIN Player ON Player.Id = Result.PlayerId
WHERE Region.Synonym = ? -- $region 
AND Season.SeasonNumber = ? -- $season 
AND LeagueMeet.MeetNumber = ? -- $meet 
ORDER BY Rank, PlayerName
";
     
    // Perform query with parameterised values.
	$result= sqlsrv_query($conn, $tsql, array($region, $season, $meet));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$counter = 0;

	echo "<div class='meet-table-holder'>";

	echo "<h2>Results</h2>";
	echo "<table>";

	echo "<thead>
				<tr class='white'>
					<th class='meetposition'>&nbsp;</th>
					<th class='meetplayer'>Player</th>
					<th class='meetfinalscore'>Score</th>
					<th class='meetfinalpoints'>Points</th>
 				</tr>
			</thead>";

	while ($resultsRow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$resultRank = $resultsRow['Rank'];
		$resultPlayerName = $resultsRow['PlayerName'];
		$resultScore = (float)$resultsRow['Score'];
		$resultPoints = (float)$resultsRow['Points'];

		$counter++;
		$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

		echo "<tr class='border'>\n
			<td class='meetposition' bgcolor='".$bgcolor."'>$resultRank</td>\n
			<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$resultPlayerName')\" class='player-link'>$resultPlayerName</a></td>\n
			<td class='meetfinalscore' bgcolor='".$bgcolor."'>$resultScore</td>\n
			<td class='meetfinalpoints' bgcolor='".$bgcolor."'>$resultPoints</td>\n
		</tr>\n";
	}

	echo "</table>\n";
	echo "</div>";

	sqlsrv_free_stmt($result);
?>

<div style="clear: both;"></div>

<p>
<script>
    document.write('<a href="' + document.referrer + '" class="link">Back to League Table</a>');
</script>
</p>

</div>

<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2015-<?=date("Y");?></p>

</div>

<div class="panel-bottom"></div>

</div> <!-- Root container -->

<!-- SlickNav start -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="jquery.slicknav.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#menu').slicknav({
	prependTo:'#nav-wrapper',
    closeOnClick:'true' // Close menu when a link is clicked.	
	});
});
</script>
<!-- SlickNav end -->

</body>
  </html>