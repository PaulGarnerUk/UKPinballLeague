<?php
	require_once __DIR__ . '/envvars.inc';

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