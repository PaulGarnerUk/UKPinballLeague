<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="League tables for the Scottish League region of the UK Pinball League" />
<title>UK Pinball League – Scottish League</title>
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

<h1>Scottish League <span style="white-space:nowrap">2019/Season 13</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php
include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT * FROM LeagueTableSC13 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2013.1&amp;meet=Meet%201,%2011.11.18\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2013.2&amp;meet=Meet%202,%2027.01.19\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2013.3&amp;meet=Meet%203,%2015.05.19\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2013.4&amp;meet=Meet%204,%2007.07.19\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 5)?"#fec171":
	$bgcolor = ($counter > 4 && $counter < 9)?"#fee0b8":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
	<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>

</div>

<div class="panel">

<h1>Scottish League 2018 / <span style="white-space:nowrap">Season 12</span></h1>


<?php

$query = "SELECT * FROM LeagueTableSC12 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2012.1&amp;meet=Meet%201,%2021.01.18\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2012.2&amp;meet=Meet%202,%2025.02.18\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2012.3&amp;meet=Meet%203,%2027.05.18\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2012.4&amp;meet=Meet%204,%2023.06.18\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 5)?"#fec171":
	$bgcolor = ($counter > 4 && $counter < 9)?"#fee0b8":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
	<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>

</div>

<div class="panel">

<h1>Scottish League 2016-17 / <span style="white-space:nowrap">Season 11</span></h1>

<?php

$query = "SELECT * FROM LeagueTableSC11 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2011.1&amp;meet=Meet%201,%2011.09.16\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2011.2&amp;meet=Meet%202,%2022.01.17\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2011.3&amp;meet=Meet%203,%2021.05.17\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2011.4&amp;meet=Meet%204,%2024.09.17\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 3)?"#fec171":
	$bgcolor = ($counter > 2 && $counter < 6)?"#fee0b8":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
	<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>

</div>

<div class="panel">

<h1>Scottish League 2015-16 / <span style="white-space:nowrap">Season 10</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php
include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT * FROM LeagueTableSC10 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2010.1&amp;meet=Meet%201,%2022.11.15\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2010.2&amp;meet=Meet%202,%2017.01.16\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2010.3&amp;meet=Meet%203,%2006.03.16\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%2010.4&amp;meet=Meet%204,%2002.05.16\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 3)?"#fec171":
	$bgcolor = ($counter > 2 && $counter < 6)?"#fee0b8":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>

</div>

<div class="panel">

<h1>Scottish League <span style="white-space:nowrap">2014-15/Season 9</span></h1>

<p>There was no Scottish League in the 9th season.</p>

</div>

<div class="panel">

<h1>Scottish League <span style="white-space:nowrap">2013-14/Season 8</span></h1>

<?php

$query = "SELECT * FROM LeagueTableSC8 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%208.1&amp;meet=Meet%201,%2030.12.13\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%208.2&amp;meet=Meet%202,%2020.07.14\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%208.3&amp;meet=Meet%203,%2017.08.14\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%208.4&amp;meet=Meet%204,%2024.08.14\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 3)?"#fec171":
	$bgcolor = ($counter > 2 && $counter < 6)?"#fee0b8":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>

</div>



<div class="panel">

<h1>Scottish League <span style="white-space:nowrap">2012-13/Season 7</span></h1>

<?php

$query = "SELECT * FROM LeagueTableSC7 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%207.1&amp;meet=Meet%201,%2024.03.13\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%207.2&amp;meet=Meet%202,%2007.04.13\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%207.3&amp;meet=Meet%203,%2002.06.13\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%207.4&amp;meet=Meet%204,%2028.07.13\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 3)?"#fec171":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>



<div class="panel">

<h1>Scottish League <span style="white-space:nowrap">2011-12/Season 6</span></h1>

<?php

$query = "SELECT * FROM LeagueTableSC6 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%206.1&amp;meet=Meet%201,%2004.12.11\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%206.2&amp;meet=Meet%202,%2020.05.12\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%206.3&amp;meet=Meet%203,%2008.07.12\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%206.4&amp;meet=Meet%204,%2005.08.12\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 3)?"#fec171":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>




<div class="panel">

<h1>Scottish League <span style="white-space:nowrap">2010-11/Season 5</span></h1>

<?php

$query = "SELECT * FROM LeagueTableSC5 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%205.1&amp;meet=Meet%201,%2013.02.11\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%205.2&amp;meet=Meet%202,%2029.05.11\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%205.3&amp;meet=Meet%203,%2003.07.11\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%205.4&amp;meet=Meet%204,%2031.07.11\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 4)?"#fec171":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>




<div class="panel">

<h1>Scottish League <span style="white-space:nowrap">2009-10/Season 4</span></h1>

<?php

$query = "SELECT * FROM LeagueTableSC4 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%204.1&amp;meet=Meet%201,%2007.02.10\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%204.2&amp;meet=Meet%202,%2030.05.10\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%204.3&amp;meet=Meet%203,%2018.07.10\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%204.4&amp;meet=Meet%204,%2008.08.10\" class='link'>Meet 4</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 3)?"#fec171":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>




<div class="panel">

<h1>Scottish League <span style="white-space:nowrap">2008-09/Season 3</span></h1>

<?php

$query = "SELECT * FROM LeagueTableSC3 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%203.1&amp;meet=Meet%201\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%203.2&amp;meet=Meet%202\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%203.3&amp;meet=Meet%203\" class='link'>Meet 3</a></th>
				<th>Total</th>
				<th class='paddidge'>Best 3</th>
				
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
	$bgcolor = ($counter < 3)?"#fec171":
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

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>




<div class="panel">

<h1>Scottish League <span style="white-space:nowrap">2007-08/Season 2</span></h1>

<?php

$query = "SELECT * FROM LeagueTableSC2 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%202.1&amp;meet=Meet%201\" class='link'>Meet 1</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%202.2&amp;meet=Meet%202\" class='link'>Meet 2</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%202.3&amp;meet=Meet%203\" class='link'>Meet 3</a></th>
				<th><a href=\"http://ukpinballleague.co.uk/meet.php?scores=SC%202.4&amp;meet=Meet%204\" class='link'>Meet 4</a></th>
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 4)?"#fec171":
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr>\n
		<td bgcolor='".$bgcolor."'>$position</td>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer(`$player`)\" class='player-link'>$player</a></td>\n
		<td bgcolor='".$bgcolor."'>$played</td>\n
		<td bgcolor='".$bgcolor."'>$meet1</td>\n
		<td bgcolor='".$bgcolor."'>$meet2</td>\n
		<td bgcolor='".$bgcolor."'>$meet3</td>\n
		<td bgcolor='".$bgcolor."'>$meet4</td>\n
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>

<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2006-<?=date("Y");?></p>

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