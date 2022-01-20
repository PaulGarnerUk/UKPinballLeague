<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="League tables for the Northern League region of the UK Pinball League" />
<title>UK Pinball League – Northern League</title>
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

<!-- 2020 -->

<h1>Northern League <span style="white-space:nowrap">2020/Season 14</span></h1>
<h3>The 2020 season was not completed due to the impact of Covid-19.</h3>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT * FROM LeagueTableN14 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%2014.1&amp;meet=Meet%201,%2012.01.20\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%2014.2&amp;meet=Meet%202,%2016.02.20\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%2014.3&amp;meet=Meet%203,%2015.03.20\" class='link'>Meet 3</a></th>
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

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>


</div>

<!-- 2019 -->

<div class="panel">

<h1>Northern League <span style="white-space:nowrap">2019/Season 13</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN13 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%2013.1&amp;meet=Meet%201,%2013.01.19\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%2013.2&amp;meet=Meet%202,%2003.02.19\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%2013.3&amp;meet=Meet%203,%2003.03.19\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%2013.4&amp;meet=Meet%204,%2007.04.19\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%2013.5&amp;meet=Meet%205,%2019.05.19\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%2013.6&amp;meet=Meet%206,%2007.07.19\" class='link'>Meet 6</a></th>
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

?>

<p class="qualifier"><span class="qual">League Finals A qualifying place</span> &nbsp; &nbsp; <span style="white-space:nowrap"><span class="qual-b">League Finals B qualifying place</span></span></p>


</div>



<div class="panel">

<h1>Northern League <span style="white-space:nowrap">2018/Season 12</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN12 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%2012.1&amp;meet=Meet%201,%2014.01.18\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%2012.2&amp;meet=Meet%202,%2025.02.18\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%2012.3&amp;meet=Meet%203,%2025.03.18\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%2012.4&amp;meet=Meet%204,%2015.04.18\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%2012.5&amp;meet=Meet%205,%2013.05.18\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%2012.6&amp;meet=Meet%206,%2003.06.18\" class='link'>Meet 6</a></th>
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
	$best4 = $row['best4'];
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 7)?"#fec171":
	$bgcolor = ($counter > 6 && $counter < 11)?"#fee0b8":
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

<h1>Northern League <span style="white-space:nowrap">2017/Season 11</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN11 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%2011.1&amp;meet=Meet%201,%2015.01.17\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%2011.2&amp;meet=Meet%202,%2006.03.17\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%2011.3&amp;meet=Meet%203,%2007.05.17\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%2011.4&amp;meet=Meet%204,%2004.06.17\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%2011.5&amp;meet=Meet%205,%2009.07.17\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%2011.6&amp;meet=Meet%206,%2006.08.17\" class='link'>Meet 6</a></th>
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
	$bgcolor = ($counter < 5)?"#fec171":
	$bgcolor = ($counter > 4 && $counter < 8)?"#fee0b8":
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

<h1>Northern League <span style="white-space:nowrap">2015-16/Season 10</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN10 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%2010.1&amp;meet=Meet%201,%2017.01.16\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%2010.2&amp;meet=Meet%202,%2028.02.16\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%2010.3&amp;meet=Meet%203,%2010.04.16\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%2010.4&amp;meet=Meet%204,%2008.05.16\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%2010.5&amp;meet=Meet%205,%2019.06.16\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%2010.6&amp;meet=Meet%206,%2017.07.16\" class='link'>Meet 6</a></th>
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
	$bgcolor = ($counter < 5)?"#fec171":
	$bgcolor = ($counter > 4 && $counter < 8)?"#fee0b8":
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

<h1>Northern League <span style="white-space:nowrap">2014-15/Season 9</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN9 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%209.1&amp;meet=Meet%201,%2030.11.14\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%209.2&amp;meet=Meet%202,%2018.01.15\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%209.3&amp;meet=Meet%203,%2001.03.15\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%209.4&amp;meet=Meet%204,%2012.04.15\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%209.5&amp;meet=Meet%205,%2010.05.15\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%209.6&amp;meet=Meet%206,%2005.07.15\" class='link'>Meet 6</a></th>
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
	$bgcolor = ($counter < 5)?"#fec171":
	$bgcolor = ($counter > 4 && $counter < 8)?"#fee0b8":
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

<h1>Northern League <span style="white-space:nowrap">2013-14/Season 8</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN8 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%208.1&amp;meet=Meet%201,%2010.11.13\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%208.2&amp;meet=Meet%202,%2005.01.14\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%208.3&amp;meet=Meet%203,%2016.02.14\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%208.4&amp;meet=Meet%204,%2030.03.14\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%208.5&amp;meet=Meet%205,%2011.05.14\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%208.6&amp;meet=Meet%206,%2029.06.14\" class='link'>Meet 6</a></th>
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
	$bgcolor = ($counter < 6)?"#fec171":
	$bgcolor = ($counter > 5 && $counter < 9)?"#fee0b8":
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

<h1>Northern League <span style="white-space:nowrap">2012-13/Season 7</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN7 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%207.1&amp;meet=Meet%201,%2020.01.13\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%207.2&amp;meet=Meet%202,%2017.02.13\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%207.3&amp;meet=Meet%203,%2017.03.13\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%207.4&amp;meet=Meet%204,%2021.04.13\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%207.5&amp;meet=Meet%205,%2019.05.13\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%207.6&amp;meet=Meet%206,%2016.06.13\" class='link'>Meet 6</a></th>
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
	$bgcolor = ($counter < 6)?"#fec171":
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

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>



<div class="panel">

<h1>Northern League <span style="white-space:nowrap">2011-12/Season 6</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN6 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%206.1&amp;meet=Meet%201,%2022.01.12\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%206.2&amp;meet=Meet%202,%2019.02.12\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%206.3&amp;meet=Meet%203,%2025.03.12\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%206.4&amp;meet=Meet%204,%2029.04.12\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%206.5&amp;meet=Meet%205,%2027.05.12\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%206.6&amp;meet=Meet%206,%2030.06.12\" class='link'>Meet 6</a></th>
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
	$bgcolor = ($counter < 5)?"#fec171":
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

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>




<div class="panel">

<h1>Northern League <span style="white-space:nowrap">2010-11/Season 5</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN5 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%205.1&amp;meet=Meet%201,%2016.01.11\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%205.2&amp;meet=Meet%202,%2027.02.11\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%205.3&amp;meet=Meet%203,%2010.04.11\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%205.4&amp;meet=Meet%204,%2015.05.11\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%205.5&amp;meet=Meet%205,%2003.07.11\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%205.6&amp;meet=Meet%206,%2017.07.11\" class='link'>Meet 6</a></th>
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

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>




<div class="panel">

<h1>Northern League <span style="white-space:nowrap">2009-10/Season 4</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN4 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%204.1&amp;meet=Meet%201,%2029.11.09\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%204.2&amp;meet=Meet%202,%2021.02.10\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%204.3&amp;meet=Meet%203,%2021.03.10\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%204.4&amp;meet=Meet%204,%2018.04.10\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%204.5&amp;meet=Meet%205,%2006.06.10\" class='link'>Meet 5</a></th>
				<th><a href=\"meet.php?scores=N%204.6&amp;meet=Meet%206,%2027.06.10\" class='link'>Meet 6</a></th>
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
	$bgcolor = ($counter < 5)?"#fec171":
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

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>




<div class="panel">

<h1>Northern League <span style="white-space:nowrap">2008-09/Season 3</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN3 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%203.1&amp;meet=Meet%201\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%203.2&amp;meet=Meet%202\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%203.3&amp;meet=Meet%203\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%203.4&amp;meet=Meet%204\" class='link'>Meet 4</a></th>
				<th><a href=\"meet.php?scores=N%203.5&amp;meet=Meet%205\" class='link'>Meet 5</a></th>
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
	$total = $row['total'];
	
	$best4 = round($best4,"1");
	$total = round($total,"1");
	
	$counter++;
	$bgcolor = ($counter < 5)?"#fec171":
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
		<td bgcolor='".$bgcolor."'>$total</td>\n
		<td bgcolor='".$bgcolor."'>$best4</td>\n
		</tr>\n";
		
}
echo "</table>\n";

?>

<p class="qualifier"><span class="qual">League Finals qualifying place</span></p>

</div>




<div class="panel">

<h1>Northern League <span style="white-space:nowrap">2007-08/Season 2</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN2 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%202.1&amp;meet=Meet%201\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%202.2&amp;meet=Meet%202\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%202.3&amp;meet=Meet%203\" class='link'>Meet 3</a></th>
				<th><a href=\"meet.php?scores=N%202.4&amp;meet=Meet%204\" class='link'>Meet 4</a></th>
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
	$bgcolor = ($counter < 5)?"#fec171":
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

<h1>Northern League <span style="white-space:nowrap">2006-07/Season 1</span></h1>

<?php

$query = "SELECT * FROM LeagueTableN1 ORDER BY best4 DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");


echo "<table>";
echo "<table class='responsive'>";

echo "<thead>
			<tr class='white'>
				<th>&nbsp;</th>
                <th>Player</th>
				<th>Played</th>
				<th><a href=\"meet.php?scores=N%201.1&amp;meet=Meet%201\" class='link'>Meet 1</a></th>
				<th><a href=\"meet.php?scores=N%201.2&amp;meet=Meet%202\" class='link'>Meet 2</a></th>
				<th><a href=\"meet.php?scores=N%201.3&amp;meet=Meet%203\" class='link'>Meet 3</a></th>
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