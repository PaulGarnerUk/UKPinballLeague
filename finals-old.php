<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Results of the league finals of the UK Pinball League" />
<title>UK Pinball League – League Finals results</title>
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

<?php include("includes/header.inc"); ?>

<!-- Content -->

<div class="panel">

<h1>League Finals A, <span style="white-space:nowrap">2019 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals A 19' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals A 19' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='A Semi-Final 19' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>A Semi-Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

$query = "SELECT * FROM OverallResults1 WHERE meet='A Final 19' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>A Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>

<div class="panel">

<h1>League Finals B, <span style="white-space:nowrap">2019 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals B 19' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals B 19' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='B Semi-Final 19' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>B Semi-Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

$query = "SELECT * FROM OverallResults1 WHERE meet='B Final 19' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>B Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>

<div class="panel">

<h1>League Finals A, <span style="white-space:nowrap">2018 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals A 18' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals A 18' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='A Semi-Final 18' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>A Semi-Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

$query = "SELECT * FROM OverallResults1 WHERE meet='A Final 18' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>A Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>

<div class="panel">

<h1>League Finals B, <span style="white-space:nowrap">2018 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals B 18' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals B 18' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='B Semi-Final 18' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>B Semi-Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

$query = "SELECT * FROM OverallResults1 WHERE meet='B Final 18' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>B Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>























<div class="panel">

<h1>League Finals A, <span style="white-space:nowrap">2017 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals A 17' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals A 17' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='A Semi-Final 17' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>A Semi-Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

$query = "SELECT * FROM OverallResults1 WHERE meet='A Final 17' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>A Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>

<div class="panel">

<h1>League Finals B, <span style="white-space:nowrap">2017 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals B 17' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals B 17' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='B Semi-Final 17' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>B Semi-Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

$query = "SELECT * FROM OverallResults1 WHERE meet='B Final 17' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>B Final Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>





















<div class="panel">

<h1>League Finals A, <span style="white-space:nowrap">2015-16 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals A 16' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals A 16' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals A 16' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>


<div class="panel">

<h1>League Finals B, <span style="white-space:nowrap">2015-16 Season</span></h1>

<?php

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals B 16' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals B 16' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals B 16' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
                <th class='meetfinalscore'>Final</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	$finalplace = $row['finalplace'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$finalplace</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>



<div class="panel">

<h1>League Finals A, <span style="white-space:nowrap">2014-15 Season</span></h1>

<?php

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals A 15' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals A 15' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals A 15' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>




<div class="panel">

<h1>League Finals B, <span style="white-space:nowrap">2014-15 Season</span></h1>

<?php

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals B 15' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals B 15' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals B 15' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>




<div class="panel">

<h1>League Finals A, <span style="white-space:nowrap">2013-14 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals A 14' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals A 14' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals A 14' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>




<div class="panel">

<h1>League Finals B, <span style="white-space:nowrap">2013-14 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals B 14' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals B 14' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals B 14' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>




<div class="panel">

<h1>League Finals, <span style="white-space:nowrap">2012-13 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals 13' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals 13' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals 13' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>




<div class="panel">

<h1>League Finals, <span style="white-space:nowrap">2011-12 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals 12' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals 12' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals 12' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>




<div class="panel">

<h1>League Finals, <span style="white-space:nowrap">2010-11 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals 11' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals 11' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals 11' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>




<div class="panel">

<h1>League Finals, <span style="white-space:nowrap">2009-10 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals 10' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals 10' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals 10' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>



<div class="panel">

<h1>League Finals, <span style="white-space:nowrap">2008-09 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals 9' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals 9' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals 9' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>




<div class="panel">

<h1>League Finals, <span style="white-space:nowrap">2007-08 Season</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = 'Finals 8' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = 'Finals 8' ORDER BY score DESC");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetscore'>Score</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='meetposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='Finals 8' ORDER BY points DESC, player";
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

$counter = 0;

echo "<div class='meet-table-holder'>";

echo "<h2>Results</h2>";
echo "<table>";

echo "<thead>
			<tr class='white'>
				<th class='meetposition'>&nbsp;</th>
                <th class='meetplayer'>Player</th>
                <th class='meetfinalscore'>Score</th>
 			</tr>
		</thead>";
		


$points = '';
	$position = 0;
	$hiddenPositions = 0;
	
	while ($row = mysqli_fetch_assoc($result))
	
{

	if ($points != $row['points']) 
	
{

	$points = $row['points'];
	$position = $hiddenPositions + $position + 1;
	$hiddenPositions = 0;
	
}

else

{
	
	++$hiddenPositions;
	
}
	
	$results_player = $row['player'];
	$points = $row['points'];
	
	$points = round($points,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";

?>

<div style="clear: both;"></div>

</div>


<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2008-<?=date("Y");?></p>

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

</body>
  </html>