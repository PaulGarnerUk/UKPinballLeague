<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Information about regional meetings, results, how to join and the annual national finals" />
<title>UK Pinball League – Home</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script language="JavaScript" type="text/javascript">
<!--
function getplayer ( selectedtype )
{
  document.playerform.player.value = selectedtype ;
  document.playerform.submit() ;
}
-->
</script>

<?php include("favicons.php"); ?>

<link rel="stylesheet" type="text/css" href="flexdropdown.css" />
<link rel="stylesheet" type="text/css" href="ukpbl.css" />

<!-- SlickNav stuff -->
<link rel="stylesheet" href="slicknav.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>

<!-- SlickNav stuff end -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="flexdropdown.js">
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

<!-- todo: reintroduce menu-current using techniques from : https://css-tricks.com/id-your-body-for-greater-css-control-and-specificity/ -->
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
 
<!-- Content -->

<div class="panel-image"><img src="images/homepage-image1.jpg" width="100%" /></div>


<div class="panel-homepage-left">

<h1>What's it all about?</h1>

<p>The UK Pinball League was set up in 2006 as the first pinball league in the UK. It is currently divided into six separate regions – South West, Midlands, London &amp; South East, Northern, Scottish and Irish.</p>

<p>The UK Pinball League offers you a chance to play a wide variety of pinball machines against  players of varying standards in a friendly and sociable atmosphere.</p>

<p>Anyone is welcome to join the UK Pinball League at any time of the season. Please <a href="contacts.php" class="link">contact</a> the co-ordinator of your region for more information.</p>

<p>Playing in the UK Pinball League earns you points in your regional league. At the end of the season, the top players from each regional league qualify for the national league final for the chance to win the UK Pinball League.</p>

<p>We have a list of common <a href="faq.php" class="link">FAQs</a> which should answer any questions that you have. Please <a href="contacts.php" class="link">contact</a> the co-ordinator of your region with any other queries.</p>

<p>Competing in the UK Pinball League also gains you World Pinball Player Ranking (<a href="http://www.pinballrankings.com/country.php" class="link" target="_blank">WPPR</a>) points that rank you against all other players worldwide.</p>

<p class="lastline-homepage">What are you waiting for?  Join the UK Pinball League now!</p>

</div>

<div class="panel-homepage-right">

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn= mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT player,SUM(season14)+SUM(season13)+SUM(season12*0.8)+SUM(season11*0.6)+SUM(season10*0.4)+SUM(season9*0.2) AS UKPBpoints FROM UKPLpoints GROUP BY player ORDER BY UKPBpoints DESC LIMIT 20";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

echo "<div id='homepagetablesleft'>";
echo "<h2 class='homepage'>Player Rankings</h2>";
echo "<table class='homepage'>";

echo "<thead>
			<tr class='white'>
				<th class='homepage-table'>&nbsp;</th>
                <th class='homepage-table'>Player</th>
         		<th class='homepage-table'>Points</th>
 			</tr>
		</thead>";
		


	$UKPBpoints = '';
	$position = 0;
	$hiddenPositions = 0;
	$counter = 0;
	
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
	$UKPBpoints = $row['UKPBpoints'];
	

	$UKPBpoints = number_format($UKPBpoints, 1);
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr class='border'>\n
		<td class='homepage-table' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='homepage-table' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$player')\" class='player-link'>$player</a></td>\n
		<td class='homepage-table' bgcolor='".$bgcolor."'>$UKPBpoints</td>\n
		</tr>\n";

}
echo "</table>\n";

echo "<p class='homepagesmall'><a href='rankings.php' class='homepagesmall'>See full list</a></p>";

echo "</div>";






$query = "SELECT machine, COUNT(machine) AS machines FROM LeagueResults1 GROUP BY machine ORDER BY machines DESC LIMIT 20";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$row = mysqli_num_rows($result);

$counter = 0;

echo "<div id='homepagetablesright'>";
echo "<h2 class='homepage'>Popular Machines</h2>";
echo "<table class='homepage'>";

echo "<thead>
			<tr class='white'>
				<th class='homepage-table'>&nbsp;</th>
                <th class='homepage-table'>Machine</th>
         		<th class='homepage-table'>Plays</th>
 			</tr>
		</thead>";
		
		

$machines = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($machines != $row['machines']) 
	
{

	$machines = $row['machines'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$machine = $row['machine'];
	$machines = $row['machines'];
	

	$machines = round($machines,"1");
	
	$newmachine = str_replace("Creature from the Black Lagoon", "Creature f/t Black Lagoon", $machine);
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr class='border'>\n
		<td class='homepage-table' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='homepage-table' bgcolor='".$bgcolor."'>$newmachine</td>\n
		<td class='homepage-table' bgcolor='".$bgcolor."'>$machines</td>\n
		</tr>\n";

}
echo "</table>\n";

echo "<p><a href='machines.php' class='homepagesmall'>See full list</a></p>";

echo "</div>";

?>

<div style="clear: both;"></div>

</div>

<div style="clear: both;"></div>

<?php

echo "<div class='panel-homepage-stats'>";

$query = "SELECT COUNT(DISTINCT player) AS player FROM OverallResults1";  
	 
$result1 = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result1);



$query = "SELECT COUNT(DISTINCT machine) AS machine FROM LeagueResults1";

$result2 = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$ncows = mysqli_num_rows($result2);



$query = "SELECT SUM(score) AS score FROM LeagueResults1";

$result3 = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nbows = mysqli_num_rows($result3);

{

	$rows = mysqli_fetch_assoc($result1);
	extract($rows);

	$cow = mysqli_fetch_assoc($result2);
	extract($cow);

	$bow = mysqli_fetch_assoc($result3);
	extract($bow);
	

	$score = number_format($score);
	
	echo "<center><h2>$player league players &nbsp;&nbsp;–&nbsp;&nbsp;  $machine pinball machines  &nbsp;&nbsp;–&nbsp;&nbsp;  $score points scored so far...</h2></center>\n";
		  
}	  

echo "</div>";

?>


<div class="panel-copyright">
<!-- Footer -->

<p class="copyright">&copy; UK Pinball League 2010-<?=date("Y");?></p>
</div>

<div class="panel-bottom"></div>

</div> <!-- End container -->

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
