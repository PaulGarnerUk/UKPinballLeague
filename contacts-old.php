<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Contact the UK Pinball League" />
<title>UK Pinball League – Contact us</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php 
	include("includes/header.inc"); 
	require_once("includes/obfuscateEmail.inc")
?>

<div class="panel">
 <h1>Contact the UKPL</h1>
</div>

<?php
	require_once("includes/sql.inc"); 

	$tsql="
	SELECT
	Name as 'RegionName',
	Director as 'DirectorName',
	DirectorEmail as 'DirectorEmail'
	FROM Region ORDER BY SortOrder ASC";

	$result = sqlsrv_query($sqlConnection, $tsql);
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$regionName = $row['RegionName'];
		$directorName = $row['DirectorName'];
		$directorEmail = obfuscateEmailLink($row['DirectorEmail']);

		echo "<div class='panel'>";
		echo "<h2>$regionName Region</h2>";
		echo "<p class='lastline'>$directorName - $directorEmail</p>";
		echo "</div>";
	}
?>

<div class="panel"> 
<h2>Treasurer</h2>
<p class="lastline">Wayne Johns - <script language=Javascript type=text/javascript>
<!--
document.write('<a class="link" href="mai');
document.write('lto');
document.write(':&#119;&#97;&#121;&#110;&#101;&#106;&#111;&#104;&#110;&#115;&#49;&#57;&#55;&#51;');
document.write('@');
document.write('&#121;&#97;&#104;&#111;&#111;&#46;&#99;&#111;&#46;&#117;&#107;">');
document.write('&#119;&#97;&#121;&#110;&#101;&#106;&#111;&#104;&#110;&#115;&#49;&#57;&#55;&#51;');
document.write('@');
document.write('&#121;&#97;&#104;&#111;&#111;&#46;&#99;&#111;&#46;&#117;&#107;<\/a>');
// -->
</script><noscript>&#119;&#97;&#121;&#110;&#101;&#106;&#111;&#104;&#110;&#115;&#49;&#57;&#55;&#51; at 
&#121;&#97;&#104;&#111;&#111; dot &#99;&#111; dot &#117;&#107;</noscript>
</p>
</div>

<div class="panel"> 
<h2>Overall Coordinator</h2>
<p>Paul Garner - 
<script language=Javascript type=text/javascript>
<!--
document.write('<a class="link" href="mai');
document.write('lto');
document.write(':&#112;&#97;&#117;&#108;');
document.write('@');
document.write('&#117;&#107;&#112;&#105;&#110;&#98;&#97;&#108;&#108;&#46;&#99;&#111;&#109;">');
document.write('&#112;&#97;&#117;&#108;');
document.write('@');
document.write('&#117;&#107;&#112;&#105;&#110;&#98;&#97;&#108;&#108;&#46;&#99;&#111;&#109;<\/a>');
// -->
</script><noscript>&#112;&#97;&#117;&#108; at 
&#117;&#107;&#112;&#105;&#110;&#98;&#97;&#108;&#108;&#46;&#99;&#111;&#109;</noscript>
</p>
</div>

<div class="panel"> 
<h2>Vice-Overall Coordinator</h2>
<p class="lastline">Kate Rothwell-Jackson - <script language=Javascript type=text/javascript>
<!--
document.write('<a class="link" href="mai');
document.write('lto');
document.write(':&#104;&#111;&#111;&#99;&#104;&#116;&#104;&#101;&#115;&#101;&#97;&#108;');
document.write('@');
document.write('&#109;&#115;&#110;&#46;&#99;&#111;&#109;">');
document.write('&#104;&#111;&#111;&#99;&#104;&#116;&#104;&#101;&#115;&#101;&#97;&#108;');
document.write('@');
document.write('&#109;&#115;&#110;&#46;&#99;&#111;&#109;<\/a>');
// -->
</script><noscript>&#104;&#111;&#111;&#99;&#104;&#116;&#104;&#101;&#115;&#101;&#97;&#108; at 
&#109;&#115;&#110; dot &#99;&#111;&#109;</noscript>
</p>
</div>

<div class="panel"> 
<h2>Marketing Coordinator</h2>
<p class="lastline">Kate Rothwell-Jackson - <script language=Javascript type=text/javascript>
<!--
document.write('<a class="link" href="mai');
document.write('lto');
document.write(':&#104;&#111;&#111;&#99;&#104;&#116;&#104;&#101;&#115;&#101;&#97;&#108;');
document.write('@');
document.write('&#109;&#115;&#110;&#46;&#99;&#111;&#109;">');
document.write('&#104;&#111;&#111;&#99;&#104;&#116;&#104;&#101;&#115;&#101;&#97;&#108;');
document.write('@');
document.write('&#109;&#115;&#110;&#46;&#99;&#111;&#109;<\/a>');
// -->
</script><noscript>&#104;&#111;&#111;&#99;&#104;&#116;&#104;&#101;&#115;&#101;&#97;&#108; at 
&#109;&#115;&#110; dot &#99;&#111;&#109;</noscript>
</p>
</div>

<div class="panel"> 
<h2>Facebook</h2>
<p class="lastline"><a href="https://www.facebook.com/UKPinballLeague" class="link">UK Pinball League Facebook group</a></p>
</div>

<div class="panel"> 
<h2>Website</h2>
<p>Paul Garner - 
<script language=Javascript type=text/javascript>
<!--
document.write('<a class="link" href="mai');
document.write('lto');
document.write(':&#112;&#97;&#117;&#108;');
document.write('@');
document.write('&#117;&#107;&#112;&#105;&#110;&#98;&#97;&#108;&#108;&#46;&#99;&#111;&#109;">');
document.write('&#112;&#97;&#117;&#108;');
document.write('@');
document.write('&#117;&#107;&#112;&#105;&#110;&#98;&#97;&#108;&#108;&#46;&#99;&#111;&#109;<\/a>');
// -->
</script><noscript>&#112;&#97;&#117;&#108; at 
&#117;&#107;&#112;&#105;&#110;&#98;&#97;&#108;&#108;&#46;&#99;&#111;&#109;</noscript>
</p>
</div>

<!-- Footer -->
<?php include("includes/footer.inc"); ?>

</body>
</html>