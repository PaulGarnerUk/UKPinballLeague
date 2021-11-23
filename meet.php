<?php $meet = $_GET['meet']; 
	$meet = htmlspecialchars($meet);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Results of <? echo "$meet"; ?>" />
<title>UK Pinball League – <? echo "$meet"; ?></title>
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

<?php echo "<h1>$meet</h1>"; ?>

<p>
<script>
    document.write('<a href="' + document.referrer + '" class="link">Back to League Table</a>');
</script>
</p>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);





if(preg_match("/^[CEIMNRSW1234567890\. ]+$/", $scores) == 1) {
}
else
{
echo '<h2>Sorry, could not load the results.</h2>';
exit();
}




$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet = '$scores' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet = '$scores' ORDER BY score DESC");
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
      
    
	



$query = "SELECT * FROM OverallResults1 WHERE meet='$scores' ORDER BY points DESC, player";
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
				<th class='meetfinalpoints'>Points</th>
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
	$UKPBpoints = $row['UKPBpoints'];
	
	$points = round($points,"1");
	$UKPBpoints = round($UKPBpoints,"1");
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	
	
	echo "<tr class='border'>\n
		<td class='meetposition' bgcolor='".$bgcolor."'>$position</td>\n
		<td class='meetplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
		<td class='meetfinalscore' bgcolor='".$bgcolor."'>$points</td>\n
		<td class='meetfinalpoints' bgcolor='".$bgcolor."'>$UKPBpoints</td>\n
		</tr>\n";
	
}
echo "</table>\n";
echo "</div>";



?>

<div style="clear: both;"></div>

<p>
<script>
    document.write('<a href="' + document.referrer + '" class="link">Back to League Table</a>');
</script>
</p>

</div>

<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2015-<?=date("Y");?></p>

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

</body>
  </html>