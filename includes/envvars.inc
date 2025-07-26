<?php
	// read the APP_ENV envvar. If it has a value other than 'primary', then we'll read prefixed envvars. 
	// This allows us to failover to a second set of config.
	function getenv_prefixed($key) {
		$env = getenv('APP_ENV') ?: 'primary';
		if (strtolower($env) === 'primary') {
	        return getenv($key);
	    }
		// If the env is not 'primary', then we assume it's a prefixed env, such as 'failover'.
	    $prefixedKey = strtolower($env) . '_' . strtolower($key);
	    return getenv($prefixedKey) ?: getenv($key); 
	}

	$env = getenv('APP_ENV') ?: 'primary';

	// legacy mysql settings
	$host=getenv("sqlhost");
	$user=getenv("sqluser");
	$password=getenv("sqlpassword");
	$dbname=getenv("dbname");

	// sql server settings. Migrate everything from mysql to ms sql in time..
	$sqlserver = getenv_prefixed("mssqlservername");
	$sqldbname = getenv_prefixed("mssqldb"); 
	$sqluser = getenv_prefixed("mssqluser");
	$sqlpassword = getenv_prefixed("mssqlpassword");

	// And migrate from legacy schema to modern schema in time..
	$sqldbname_legacy = getenv("mssqldb_legacy"); // sql db with legacy (denormalised) schema

	// adhoc config settings
	$currentseason = getenv("currentseason"); // current season number (not id)
?>