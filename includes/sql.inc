<?php
	// legacy mysql settings
	$host=getenv("sqlhost");
	$user=getenv("sqluser");
	$password=getenv("sqlpassword");
	$dbname=getenv("dbname");

	// sql server settings. Migrate everything from mysql to ms sql in time..
	$sqlserver = getenv("mssqlservername");
	$sqluser = getenv("mssqluser");
	$sqlpassword = getenv("mssqlpassword");

	// And migrate from legacy schema to modern schema in time..
	$sqldbname_legacy = getenv("mssqldb_legacy"); // sql db with legacy (denormalised) schema
	$sqldbname = getenv("mssqldb"); // sql db with normalised scheme

	// adhoc config settings
	$currentseason = getenv("currentseason"); // current season number (not id)


    $sqlConnectionOptions = array(
        "Database" => $sqldbname,
        "Uid" => $sqluser,
        "PWD" => $sqlpassword
    );

    $sqlConnection = sqlsrv_connect($sqlserver, $sqlConnectionOptions);
	if($sqlConnection === false ) 
	{
		echo "connection borken.";
		// die( print_r( sqlsrv_errors(), true));
	}

?>