<?php
	include("../includes/envvars.inc");
	include("../includes/sql.inc");

	$output = htmlspecialchars($_GET["output"] ?? 'json'); // expect json or csv

	$tsql="
SELECT
Id AS 'Id',
Name AS 'Name',
Synonym AS 'Synonym',
Director AS 'Director'
FROM Region
";

	$result = sqlsrv_query($sqlConnection, $tsql);
	if ($result == FALSE)
	{
		echo "query borken.";
		die(print_r(sqlsrv_errors(), true));
	}

	$regions = array();
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$regions[] = array(
			'id' => $row['Id'],
			'name' => $row['Name'],
			'synonym' => $row['Synonym'],
			'director' => $row['Director'],
		);
	}

	if ($output == 'csv')
	{
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="regions.csv"');
		
		// Open output stream, writing directly to php output stream (same as echo)
	    $outputStream = fopen('php://output', 'w');

		// Write the CSV header based on the keys of the first element in the array, then write csv lines for each element in the array
        fputcsv($outputStream, array_keys($regions[0]));
		foreach ($regions as $row) 
		{
			fputcsv($outputStream, $row);
		}

		// Close the output stream
		fclose($outputStream);
	}
	else 
	{
		header('Content-Type: application/json');
		echo json_encode($regions);
	}

?>