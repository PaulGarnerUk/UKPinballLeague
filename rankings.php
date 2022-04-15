<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Current UK Pinball League player rankings" />
<title>UK Pinball League – Rankings</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php include("favicons.php"); ?>

<link rel="stylesheet" type="text/css" href="css/flexdropdown.css" />
<link rel="stylesheet" type="text/css" href="css/ukpbl.css" />

<!-- SlickNav -->
<link rel="stylesheet" href="css/slicknav.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>

<!-- Flex dropdown -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="js/flexdropdown.js">
/***********************************************
* Flex Level Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19413802-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<script language="JavaScript" type="text/javascript">
<!--
function getplayer ( selectedtype )
{
  document.playerform.player.value = selectedtype ;
  document.playerform.submit() ;
}
-->
</script>

</head>

<body>

<!-- Background -->	
<div id="background">

<!-- Container -->
<div id="container">
	
<!-- Banner -->
<div id="banner"><img src="images/ukpl-banner.png" class="banner" width="107px">

<!-- Navigation -->
<div id="nav-wrapper">
<?php include("includes/responsive-menu.inc"); ?>
</div>
</div>

<div id="main-menu">

<p class="menu-text">
<a href="index.php" class="menu">HOME</a> 
<a href="schedule.php" class="menu">SCHEDULE</a> 
<a href="#" class="menu" data-flexmenu="league-results">RESULTS</a>
<a href="rankings.php" class="menu">RANKINGS</a>
<a href="#" class="menu" data-flexmenu="scores">HI SCORES</a>
  
<a href="#" class="menu" data-flexmenu="rules-faqs">RULES/FAQ</a> 
<a href="links.php" class="menu">LINKS</a> 
<a href="contacts.php" class="menu">CONTACTS</a>
</p>

<!--HTML for League Results-->
<ul id="league-results" class="flexdropdownmenu">
<li><a href="league.php?region=sw">SOUTH WEST</a></li>
<li><a href="league.php?region=m">MIDLANDS</a></li>
<li><a href="league.php?region=lse">LONDON &amp; SE</a></li>
<li><a href="league.php?region=n">NORTHERN</a></li>
<li><a href="league.php?region=s">SCOTTISH</a></li>
<li><a href="league.php?region=i">IRISH</a></li>
<li><a href="finals.php">FINALS</a></li>
<li><a href="placings.php">MEET WINNERS</a></li>
<li><a href="winners.php">LEAGUE WINNERS</a></li>
</ul>

<!--HTML for Scores-->
<ul id="scores" class="flexdropdownmenu">
<li><a href="highaveragescores.php">HI / AVG SCORES</a></li>
<li><a href="highscores10.php">HI SCORES 10</a></li>
<li><a href="highscoresperplayer.php">HI PER PLAYER</a></li>
</ul>

<!--HTML for Rules/FAQs-->
<ul id="rules-faqs" class="flexdropdownmenu">
<li><a href="rules.php">RULES</a></li>
<li><a href="faq.php">FAQs</a></li>
</ul>

</div><!--END MENU DIV-->

<div id="menu-strip"></div>

<div class="panel">

<h1>UKPL Player Rankings</h1>

<p class="firstline">Players' total ranking points are based on league points earned to date.</p>

<p>League points depreciate as follows:<br>
&bull; <strong>Season 14 (current season)</strong> – 100% of their original value<br>&bull; <strong>Season 13</strong> – 100% of their original value<br>&bull; <strong>Season 12</strong> – 80% of their original value<br>&bull; <strong>Season 11</strong> – 60% of their original value<br>&bull; <strong>Season 10</strong> – 40% of their original value<br>&bull; <strong>Season 9</strong> – 20% of their original value<br>&bull; <strong>Seasons 8, 7, 6, 5, 4, 3, 2 &amp; 1</strong> – 0% of their original value</p>

<div class="panel">

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT player,SUM(season14) AS season114,SUM(season13) AS season113,SUM(season12*0.8) AS season112,SUM(season11*0.6) AS season111,SUM(season10*0.4) AS season101,SUM(season9*0.2) AS season99,

SUM(season14)+SUM(season13)+SUM(season12*0.8)+SUM(season11*0.6)+SUM(season10*0.4)+SUM(season9*0.2) AS UKPBpoints1 FROM UKPLpoints GROUP BY player ORDER BY UKPBpoints1 DESC, player";

$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

?>
<h2>UKPL Player Rankings</h2>


<table class="rankings">

<thead>
			<tr class="white">
				<th>&nbsp;</th>
               <th>Player</th>
                <th>S1</th>
                <th>S2</th>
                <th>S3</th>
                <th>S4</th>
                <th>S5</th>
                <th>S6</th>
                <th>S7</th>
                <th>S8</th>
                <th>S9</th>
                <th>S10</th>
                <th>S11</th>
                <th>S12</th>
                <th>S13</th>
                <th>S14</th>
         		<th>Total</th>
 			</tr>
		</thead>
		
<?php
		

	$UKPBpoints1 = '';
	$position = 0;
	$hiddenPositions = 0;
	$counter = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($UKPBpoints1 != $row['UKPBpoints1'])
	
{

	$UKPBpoints1 = $row['UKPBpoints1'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$player = $row['player'];
	$season99 = $row['season99'];
	$season101 = $row['season101'];
	$season111 = $row['season111'];
	$season112 = $row['season112'];
	$season113 = $row['season113'];
	$season114 = $row['season114'];
	$UKPBpoints1 = $row['UKPBpoints1'];
	

	$UKPBpoints1 = ($UKPBpoints1 + 0);
	$season114 = ($season114 + 0);
	$season113 = ($season113 + 0);
	$season112 = ($season112 + 0);
	$season111 = ($season111 + 0);
	$season101 = ($season101 + 0);
	$season99 = ($season99 + 0);
	
	$counter++;
	
	echo "<tr>\n
		<td>$position</td>\n
		<td><span style='white-space:nowrap'><a href=\"javascript:getplayer('$player')\" class='player-link'>$player</a></span></td>\n
		<td>0</td>\n
		<td>0</td>\n
		<td>0</td>\n
		<td>0</td>\n
		<td>0</td>\n
		<td>0</td>\n
		<td>0</td>\n
		<td>0</td>\n
		<td>$season99</td>\n
		<td>$season101</td>\n
		<td>$season111</td>\n
		<td>$season112</td>\n
		<td>$season113</td>\n
		<td>$season114</td>\n
		<td class='lastcolumn'>$UKPBpoints1</td>\n
		</tr>\n";

}
echo "</table>\n";




$query = "SELECT player,SUM(season14) AS season14,SUM(season13) AS season13,SUM(season12) AS season12,SUM(season11) AS season11,SUM(season10) AS season10,SUM(season9) AS season9,SUM(season8) AS season8,SUM(season7) AS season7,SUM(season6) AS season6,SUM(season5) AS season5,SUM(season4) AS season4,SUM(season3) AS season3,SUM(season2) AS season2,SUM(season1) AS season1,

SUM(season14)+SUM(season13)+SUM(season12)+SUM(season11)+SUM(season10)+SUM(season9)+SUM(season8)+SUM(season7)+SUM(season6)+SUM(season5)+SUM(season4)+SUM(season3)+SUM(season2)+SUM(season1) AS UKPBpoints FROM UKPLpoints GROUP BY player ORDER BY UKPBpoints DESC, player";

$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

?>

</div>

<div class="panel">

<h2>UKPL player rankings without point depreciation</h2>


<table class="rankings">

<thead>
			<tr class="white">
				<th>&nbsp;</th>
                <th>Player</th>
                <th>S1</th>
                <th>S2</th>
                <th>S3</th>
                <th>S4</th>
                <th>S5</th>
                <th>S6</th>
                <th>S7</th>
                <th>S8</th>
                <th>S9</th>
                <th>S10</th>
                <th>S11</th>
                <th>S12</th>
                <th>S13</th>
                <th>S14</th>
         		<th>Total</th>
 			</tr>
		</thead>
		
<?php


$UKPBpoints = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($UKPBpoints != $row['UKPBpoints'])
	
{

	$UKPBpoints = $row['UKPBpoints'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$player = $row['player'];
	$season1 = $row['season1'];
	$season2 = $row['season2'];
	$season3 = $row['season3'];
	$season4 = $row['season4'];
	$season5 = $row['season5'];
	$season6 = $row['season6'];
	$season7 = $row['season7'];
	$season8 = $row['season8'];
	$season9 = $row['season9'];
	$season10 = $row['season10'];
	$season11 = $row['season11'];
	$season12 = $row['season12'];
	$season12 = $row['season13'];
	$season12 = $row['season14'];
	$UKPBpoints = $row['UKPBpoints'];
	

	$UKPBpoints = ($UKPBpoints + 0);
	$season12 = ($season14 + 0);
	$season12 = ($season13 + 0);
	$season12 = ($season12 + 0);
	$season11 = ($season11 + 0);
	$season10 = ($season10 + 0);
	$season9 = ($season9 + 0);
	$season8 = ($season8 + 0);
	$season7 = ($season7 + 0);
	$season6 = ($season6 + 0);
	$season5 = ($season5 + 0);
	$season4 = ($season4 + 0);
	$season3 = ($season3 + 0);
	$season2 = ($season2 + 0);
	$season1 = ($season1 + 0);
	
	$counter++;
	
	echo "<tr>\n
		<td>$position</td>\n
		<td><span style='white-space:nowrap'><a href=\"javascript:getplayer('$player')\" class='player-link'>$player</a></span></td>\n
		<td>$season1</td>\n
		<td>$season2</td>\n
		<td>$season3</td>\n
		<td>$season4</td>\n
		<td>$season5</td>\n
		<td>$season6</td>\n
		<td>$season7</td>\n
		<td>$season8</td>\n
		<td>$season9</td>\n
		<td>$season10</td>\n
		<td>$season11</td>\n
		<td>$season12</td>\n
		<td>$season13</td>\n
		<td>$season14</td>\n
		<td class='lastcolumn'>$UKPBpoints</td>\n
		</tr>\n";

}
echo "</table>\n";


?>

</div>

<div class="panel-copyright">

<!-- Footer -->
<p class="copyright">&copy; UK Pinball League 2010-<?=date("Y");?></p>

</div>

<div class="panel-bottom"></div>

</div> <!-- End Container -->


<!-- SlickNav start -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="js/jquery.slicknav.js"></script>
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