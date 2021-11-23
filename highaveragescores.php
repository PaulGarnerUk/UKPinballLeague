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

<?php include("headerbonus.inc"); ?>

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
		

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");


$query = "SELECT f.machine, f.player, f.score, f.meet FROM (SELECT machine, MAX(score) AS maxscore FROM LeagueResults1 GROUP BY machine) AS x INNER JOIN LeagueResults1 AS f ON f.machine = x.machine AND f.score = x.maxscore GROUP BY machine";

$results = mysqli_query($cxn,$query) or die ("Couldn't execute query1");

$nbows = mysqli_num_rows($results);



$query = "SELECT machine,AVG(score) AS avgscore FROM LeagueResults1 WHERE score > 0 GROUP BY machine";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query2");

$nrows = mysqli_num_rows($result);



for ($i=0;$i<$nrows;$i++)

{
	$bow = mysqli_fetch_assoc($results);
	extract($bow);

	$row = mysqli_fetch_assoc($result);
	extract($row);
	
	$avgscore = number_format($avgscore);
	$score = number_format($score);
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'><span style='white-space:nowrap'>$machine</span></td>\n
		
		<td bgcolor='".$bgcolor."'><span style='white-space:nowrap'>$score</span></td>\n
		<td bgcolor='".$bgcolor."'><span style='white-space:nowrap'><a href=\"javascript:getplayer('$player')\" class='player-link'>$player</a></span></td>\n
		<td bgcolor='".$bgcolor."'><span style='white-space:nowrap'>$meet</span></td>\n
		<td class='lastcolumn' bgcolor='".$bgcolor."'><span style='white-space:nowrap'>$avgscore</span></td>\n
		</tr>\n";
	
}
echo "</table>\n";


?>

</div>

<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2010-<?=date("Y");?></p>

</div>

<div class="panel-bottom"></div>

</div> <!-- End Container -->

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