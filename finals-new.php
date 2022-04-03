<?php
	// First, validate region and season# 
	include("includes/sql.inc"); 

	$season = htmlspecialchars($_GET['season'] ?? $currentseason); // default to current season if not specified.

	$tsql="
	SELECT
	Season.Year AS 'SeasonYear'
	FROM Season
	WHERE Season.SeasonNumber = ? -- $season";

	$result = sqlsrv_query($sqlConnection, $tsql, array($region, $season));
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$regionName = $row['RegionName'];
	$seasonYear = $row['SeasonYear'];

	if (is_null($regionName))
	{
		echo '<p>Unexpected region or season number.</p>';
		exit;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Page description" />
<title>UK Pinball League - Finals Results</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Header and menu -->
<?php include("includes/header.inc"); ?>


<div class="panel">

    <h1>Page Title</h1>

    <p class="firstline">First line of content.</p>
    <p>Additional text.</p>

</div>

<div class="panel">

    <h2>Panel title</h2>
    <p>Content within panel</p>
	
    <div class="table-holder">
        <table class="responsive">
            <thead>
                <tr class="white">
                    <th scope="col" width="150px">Date</th>
                    <th scope="col" width="100px">Practice</th>
                    <th scope="col" width="100px">Competition</th>
                    <th scope="col" width="230px">Location</th>
                    <th scope="col" width="170px">Host</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>TBC</td>
                    <td>TBC</td>
                    <td>TBC</td>
                    <td>TBC</td>
                    <td class="paddidge">TBC</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Header and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>