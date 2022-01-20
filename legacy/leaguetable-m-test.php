<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="League table for the Midlands League region of the UK Pinball League" />
<title>UK Pinball League – Midlands League 2019/Season 13</title>
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

<h1>Midlands League <span style="white-space:nowrap">2019/Season 13</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php


include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT player,UKPBpoints (AS meetresult1 FROM OverallResults1 WHERE meet='M 13.1'), (AS meetresult2 FROM OverallResults1 WHERE meet='M 13.2')";

$meetresult1 = mysqli_query($cxn,$query) or die ("Couldn't execute meetresult1 query");



echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=M%2013.1&amp;meet=Meet%201,%2020.01.19\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=M%2013.2&amp;meet=Meet%202,%2017.02.19\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=M%2013.3&amp;meet=Meet%203,%2017.03.19\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=M%2013.4&amp;meet=Meet%204,%2028.04.19\" class='link'>Meet 4</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=M%2013.5&amp;meet=Meet%205,%2019.05.19\" class='link'>Meet 5</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=M%2013.6&amp;meet=Meet%206,%2023.06.19\" class='link'>Meet 6</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 4</th>
				
 			</tr>
		</thead>";

		
$counter = 0;


	$totalresult = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($meetresult1))
		
	
{

	if ($best4result != $row['best4result']) 
	
{

	$best4result = $row['best4result'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$player = $row['player'];
	$played = $row['played'];
	$meet1 = $row['meetresult1'];
	$meet2 = $row['meetresult2'];
	$meet3 = $row['meet3'];
	$meet4 = $row['meet4'];
	$meet5 = $row['meet5'];
	$meet6 = $row['meet6'];
	$total = $row['totalresult'];
	
	$best4 = round($best4result,"1");
	$total = round($totalresult,"1");
	
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

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>

</div>

<div class="panel">

<h1>Previous seasons</h1>

<p><a href="previous-leaguetable-m.php" class="link">View league tables from previous seasons</a>.</p>

</div>


<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2018</p>

</div>

<div class="panel-bottom"></div>

</div> <!-- Root container -->

<!-- SlickNav start -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
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