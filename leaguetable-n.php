<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="League tables for the Northern League region of the UK Pinball League" />
<title>UK Pinball League – Northern League 2019/Season 13</title>
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

<?php include("header.inc"); ?>

<!-- Content -->

<div class="panel">

<h1>Northern League <span style="white-space:nowrap">2020/Season 14</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

    $connectionOptions = array(
        "Database" => $sqldbname,
        "Uid" => $sqluser,
        "PWD" => $sqlpassword
    );

    $conn = sqlsrv_connect($sqlserver, $connectionOptions);
	if( $conn === false ) 
	{
		echo "connection bork: ";
		die( print_r( sqlsrv_errors(), true));
	}

	// This is a little experimental. Could make a sql function out of this so long as it all works..
    $tsql= "WITH SeasonPlayers (PlayerId, PlayerName) AS
(
	SELECT DISTINCT
	Player.Id,
	Player.Name
	FROM Result 
	INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Result.CompetitionId
	INNER JOIN Season on Season.Id = LeagueMeet.SeasonId
	INNER JOIN Region on Region.Id = LeagueMeet.RegionId
	INNER JOIN Player ON Player.Id = Result.PlayerId
	WHERE Season.SeasonNumber = $currentseason
	AND Region.Synonym = 'n'
),
PlayerResults (PlayerId, MeetNumber, Points, Rnk) AS
(
	SELECT 
	Player.Id AS PlayerId,
	--LeagueMeet.Id AS LeagueMeetId,
	LeagueMeet.MeetNumber,
	Result.Points,
	ROW_NUMBER() OVER (PARTITION BY Player.Id ORDER BY Result.Points DESC) AS Rnk
	FROM Result 
	INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Result.CompetitionId
	INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
	INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
	INNER JOIN Player ON Player.Id = Result.PlayerId
	WHERE Season.SeasonNumber = $currentseason
	AND Region.Synonym = 'n' --@regionSynonym
)
SELECT 
SeasonPlayers.PlayerId,
SeasonPlayers.PlayerName as player,
(
	SELECT COUNT(*) FROM PlayerResults WHERE PlayerResults.PlayerId = SeasonPlayers.PlayerId
) as played,
COALESCE(CONVERT(varchar, MeetOne.Points), '-') as meet1,
COALESCE(CONVERT(varchar, MeetTwo.Points), '-') as meet2,
COALESCE(CONVERT(varchar, MeetThree.Points), '-') as meet3,
COALESCE(CONVERT(varchar, MeetFour.Points), '-') as meet4,
COALESCE(CONVERT(varchar, MeetFive.Points), '-') as meet5,
MeetSix.Points as meet6,
COALESCE(MeetOne.Points,0) + COALESCE(MeetTwo.Points,0) + COALESCE(MeetThree.Points,0) + COALESCE(MeetFour.Points,0) + COALESCE(MeetFive.Points,0) + COALESCE(MeetSix.Points,0) AS total,
(
	SELECT 
	SUM(PlayerResults.Points)
	FROM PlayerResults
	WHERE PlayerResults.PlayerId = SeasonPlayers.PlayerId AND PlayerResults.Rnk <= 4
) AS best4
FROM SeasonPlayers 
LEFT OUTER JOIN PlayerResults AS MeetOne ON MeetOne.PlayerId = SeasonPlayers.PlayerId AND MeetOne.MeetNumber = 1
LEFT OUTER JOIN PlayerResults AS MeetTwo ON MeetTwo.PlayerId = SeasonPlayers.PlayerId AND MeetTwo.MeetNumber = 2
LEFT OUTER JOIN PlayerResults AS MeetThree ON MeetThree.PlayerId = SeasonPlayers.PlayerId AND MeetThree.MeetNumber = 3
LEFT OUTER JOIN PlayerResults AS MeetFour ON MeetFour.PlayerId = SeasonPlayers.PlayerId AND MeetFour.MeetNumber = 4
LEFT OUTER JOIN PlayerResults AS MeetFive ON MeetFive.PlayerId = SeasonPlayers.PlayerId AND MeetFive.MeetNumber = 5
LEFT OUTER JOIN PlayerResults AS MeetSix ON MeetSix.PlayerId = SeasonPlayers.PlayerId AND MeetSix.MeetNumber = 6
ORDER BY best4 DESC, played ASC
";

    $result= sqlsrv_query($conn, $tsql);

    if ($result == FALSE)
	{
		echo "query borken.";
        //echo (sqlsrv_errors());
	}

//$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");
//$query = "SELECT * FROM LeagueTableN14 ORDER BY best4 DESC, player";  
//$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%2015.1&amp;meet=Meet%201,%2012.01.20\" class='link'>Meet 1</a></th>
				<th>Meet 2</th>
				<th>Meet 3</th>
				<th>Meet 4</th>
				<th>Meet 5</th>
				<th>Meet 6</th>
				<th>Total</th>
				<th class='paddidge'>Best 4</th>
 			</tr>
		</thead>";

		
	$counter = 0;

	$total = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{

		if ($best4 != $row['best4']) 
		{
			$best4 = $row['best4'];
			$position = $hiddenPositions + $position + 1;
			$hiddenPositions = 0;
		}
		else
		{
			++$hiddenPositions;
		}
	
		$player = $row['player'];
		$played = $row['played'];
		$meet1 = $row['meet1'];
		$meet2 = $row['meet2'];
		$meet3 = $row['meet3'];
		$meet4 = $row['meet4'];
		$meet5 = $row['meet5'];
		//$meet6 = $row['meet6'];
		$meet6 = is_null($row['meet6']) ? "-", (float)$row['meet6']
		$best4 = $row['best4'];
		$total = $row['total'];
	
		$best4 = round($best4,"1");
		$total = round($total,"1");
	
		$counter++;
		$bgcolor = ($counter < 6)?"#fec171":
		$bgcolor = ($counter > 5 && $counter < 10)?"#fee0b8":
		$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
		echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$meet5</td>\n
		<td bgcolor='".$bgcolor."'>$meet6</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
	}

	echo "</table>\n";

	sqlsrv_free_stmt($result);

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>


</div>

<div class="panel">

<h1>Previous seasons</h1>

<p><a href="previous-leaguetable-n.php" class="link">View league tables from previous seasons</a>.</p>

</div>


<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2006-<?=date("Y");?></p>

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

<!-- Responsive tables -->
<link type="text/css" media="screen" rel="stylesheet" href="responsive-league.css" />
<script type="text/javascript" src="responsive-league.js"></script>

</body>
  </html>