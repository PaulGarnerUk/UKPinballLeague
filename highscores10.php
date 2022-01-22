<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="A list of all current highscores in the UK Pinball League" />
<title>UK Pinball League – League Highscores</title>
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

<h1>UKPL Highscores</span></h1>

<form name="playerform" action="player-info.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$scores = mysqli_real_escape_string($cxn, $_GET['scores']);

$getmachines = mysqli_query($cxn, "SELECT DISTINCT machine FROM LeagueResults1 WHERE meet LIKE '%' ORDER BY machine");
$machines = mysqli_num_rows($getmachines);

    while ($rowmachines = mysqli_fetch_assoc($getmachines)) {

    $machine = $rowmachines['machine'];

    $getmeetresults = mysqli_query($cxn, "SELECT * FROM LeagueResults1 WHERE machine = '$machine' and meet LIKE '%' ORDER BY score DESC LIMIT 10");
    $meetresults = mysqli_num_rows($getmeetresults);

$counter = 0;
      
      	echo "<div class='meet-table-holder'>";
        
        echo "<h2>$machine</h2>";
        
        echo "<table>";
        
        echo "<thead>
			<tr class='white'>
				<th class='highscoreposition'>&nbsp;</th>
                <th class='highscoreplayer'>Player</th>
                <th class='meetscore'>Score</th>
                <th class='meetscore'>Meet</th>
 			</tr>
		</thead>";
        
        	$n = 1;
			
			
            while ($rowmeetresults = mysqli_fetch_assoc($getmeetresults)) {

            $results_player = $rowmeetresults['player'];
            $results_score = $rowmeetresults['score'];
            $results_meet = $rowmeetresults['meet'];
            // all other info you want to pull here
             
            $results_score = number_format($results_score);
            
            $results_newmeet = str_replace("Finals A 15", "F A 15", $results_meet);
            
            $results_newmeets = str_replace("Finals A 14", "F A 14", $results_newmeet);
            
            $counter++;
			$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";

            echo "<tr class='border'>\n
            		  <td class='highscoreposition' bgcolor='".$bgcolor."'>$n</td>\n
            		  <td class='highscoreplayer' bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$results_player')\" class='player-link'>$results_player</a></td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_score</td>\n
            		  <td class='meetscore' bgcolor='".$bgcolor."'>$results_newmeets</td>\n
            </tr>\n";
			
			$n = $n + 1;
            
            
            
            } // end meetresults loop
            
            echo "</table>\n";
            
            echo "</div>";
            
            } // end machines loop

?>

<div style="clear: both;"></div>

</div>

<!-- Footer -->

<div class="panel-copyright">

<p class="copyright">&copy; UK Pinball League 2007-<?=date("Y");?></p>

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