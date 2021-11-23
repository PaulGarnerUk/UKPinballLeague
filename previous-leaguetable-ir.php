<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="League tables for the Irish League region of the UK Pinball League" />
<title>UK Pinball League – Irish League</title>
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

<h1>Irish League <span style="white-space:nowrap">2019/Season 13</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php
include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT * FROM LeagueTableIR13 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2013.1&amp;meet=Meet%201,%2004.11.18\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2013.2&amp;meet=Meet%202,%2017.02.19\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2013.3&amp;meet=Meet%203,%2009.06.19\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2013.4&amp;meet=Meet%204,%2007.07.19\" class='link'>Meet 4</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2013.5&amp;meet=Meet%205,%2021.07.19\" class='link'>Meet 5</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2013.6&amp;meet=Meet%206,%2011.08.19\" class='link'>Meet 6</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 4</th>
				
				
 			</tr>
		</thead>";

		
$counter = 0;


	$total = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
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
	$meet6 = $row['meet6'];
	
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 4)?"#fec171":
	$bgcolor = ($counter > 3 && $counter < 8)?"#fee0b8":
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

<h1>Irish League <span style="white-space:nowrap">2018/Season 12</span></h1>

<?php
$query = "SELECT * FROM LeagueTableIR12 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2012.1&amp;meet=Meet%201,%2018.02.18\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2012.2&amp;meet=Meet%202,%2011.03.18\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2012.3&amp;meet=Meet%203,%2013.05.18\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2012.4&amp;meet=Meet%204,%2024.06.18\" class='link'>Meet 4</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2012.5&amp;meet=Meet%205,%2015.07.18\" class='link'>Meet 5</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2012.6&amp;meet=Meet%206,%2029.07.18\" class='link'>Meet 6</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 4</th>
				
				
 			</tr>
		</thead>";

		
$counter = 0;


	$total = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
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
	$meet6 = $row['meet6'];
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 4)?"#fec171":
	$bgcolor = ($counter > 3 && $counter < 8)?"#fee0b8":
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

<h1>Irish League <span style="white-space:nowrap">2016-17/Season 11</span></h1>

<?php

$query = "SELECT * FROM LeagueTableIR11 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2011.1&amp;meet=Meet%201,%2020.11.16\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2011.2&amp;meet=Meet%202,%2005.03.17\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2011.3&amp;meet=Meet%203,%2023.04.17\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2011.4&amp;meet=Meet%204,%2002.07.17\" class='link'>Meet 4</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2011.5&amp;meet=Meet%205,%2027.08.17\" class='link'>Meet 5</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2011.6&amp;meet=Meet%206,%2023.09.17\" class='link'>Meet 6</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 4</th>
				
				
 			</tr>
		</thead>";

		
$counter = 0;


	$total = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
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
	$meet6 = $row['meet6'];
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 4)?"#fec171":
	$bgcolor = ($counter > 3 && $counter < 7)?"#fee0b8":
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

<h1>Irish League <span style="white-space:nowrap">2015-16/Season 10</span></h1>

<?php

$query = "SELECT * FROM LeagueTableIR10 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2010.1&amp;meet=Meet%201,%2018.10.15\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2010.2&amp;meet=Meet%202,%2029.11.15\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2010.3&amp;meet=Meet%203,%2007.02.16\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2010.4&amp;meet=Meet%204,%2010.04.16\" class='link'>Meet 4</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2010.5&amp;meet=Meet%205,%2029.05.16\" class='link'>Meet 5</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%2010.6&amp;meet=Meet%206,%2031.07.16\" class='link'>Meet 6</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 4</th>
				
				
 			</tr>
		</thead>";

		
$counter = 0;


	$total = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
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
	$meet6 = $row['meet6'];
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 4)?"#fec171":
	$bgcolor = ($counter > 3 && $counter < 7)?"#fee0b8":
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

<h1>Irish League <span style="white-space:nowrap">2014-15/Season 9</span></h1>

<?php

$query = "SELECT * FROM LeagueTableIR9 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%209.1&amp;meet=Meet%201,%2030.11.14\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%209.2&amp;meet=Meet%202,%2001.03.15\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=IR%209.3&amp;meet=Meet%203,%2002.08.15\" class='link'>Meet 3</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 2</th>
				
 			</tr>
		</thead>";

		
$counter = 0;


	$total = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 4)?"#fec171":
	$bgcolor = ($counter > 3 && $counter < 7)?"#fee0b8":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>

</div>

<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2014-<?=date("Y");?></p>

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