<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="A list of how many highscores UK Pinball League players have" />
<title>UK Pinball League – Highscores Per Player</title>
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

<form name="playerform" action="player-highscores.php" method="get">
<input type="hidden" name="player" />

<?php

include("includes/old_menu.inc");

$cxn=mysqli_connect ($host,$user,$password,$dbname) or die ("Couldn't connect to the server");

$query = "SELECT * FROM HighscoresPerPlayer ORDER BY highscores DESC, player";  
	 
$result = mysqli_query($cxn,$query) or die ("Couldn't execute query");

$nrows = mysqli_num_rows($result);

echo "<h1>UKPL Highscores Per Player</h1>";
echo "<h2>
Please note. This table currently only shows accurate data up to the 14th Season (2020)<br>
This table will soon be relocated into a list of all league players and stats
</h2>";

echo "<table class='hi-per-player'>";

echo "<thead>
			<tr class='white'>
				<th>Player</th>
                <th class='scores'>Highscores</th>
 			</tr>
		</thead>";
		

for ($i=0;$i<$nrows;$i++)

{
	$n = $i + 1; #add 1 so numbers don't start with 0
	$row = mysqli_fetch_assoc($result);
	extract($row);
	$sumscore = number_format($sumscore);
	
	$counter++;
	$bgcolor = ($counter % 2)?"#f7f7f7":"#ffffff";
	
	echo "<tr class='border'>\n
		<td bgcolor='".$bgcolor."'><a href=\"javascript:getplayer('$player')\" class='player-link'>$player</a></td>\n
		<td class='scores' bgcolor='".$bgcolor."'>$highscores</td>\n
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
