<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Lists of all pinball machines played in the UK Pinball League" />
<title>UK Pinball League – Machines</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">

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

<!-- Container-->
<div id="container">

<!-- Banner -->
<div id="banner"><img src="images/ukpl-banner.png" class="banner" width="107px">

<!-- Navigation -->
<div id="nav-wrapper">
<?php include("responsive-menu.inc"); ?>
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
<li><a href="leaguetable-sw.php">SOUTH WEST</a></li>
<li><a href="leaguetable-m.php">MIDLANDS</a></li>
<li><a href="leaguetable-se.php">LONDON &amp; SE</a></li>
<li><a href="leaguetable-n.php">NORTHERN</a></li>
<li><a href="leaguetable-sc.php">SCOTTISH</a></li>
<li><a href="leaguetable-ir.php">IRISH</a></li>
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

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT machine, COUNT(machine) AS machines FROM LeagueResults1 GROUP BY machine ORDER BY machines DESC, machine";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$row = mysqli_num_rows($result);

$counter = 0;

echo "<h1>Machines – Full List</h1>"; ?>

<p>
<script>
    document.write('<a href="' + document.referrer + '" class="link">Back to previous page</a>');
</script>
</p>

<?php echo "<table class='machines'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Machine</th>
         		<th>Plays</th>
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
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr class='border'>\n
		<td class='firstcolumn' bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'>$machine</td>\n
		<td bgcolor='".$bgcolor."'>$machines</td>\n
		</tr>\n";

}
echo "</table>\n";

?>

<p>&nbsp;<br>
<script>
    document.write('<a href="' + document.referrer + '" class="link">Back to previous page</a>');
</script>
</p>


</div>

<div class="panel-copyright">
<!-- Footer -->
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