<?php

class RegionInfo
{
	public $regionId;
	public $leagueName;
	public $synonym;
}

function ValidateRegionSynonym($region = "all", $sqlConnection)
{
	$info = null;

	if ($region !== "all") 
	{
		$tsql="
		SELECT
		Region.Id AS 'RegionId',
		Region.Name AS 'RegionName',
		Region.Synonym AS 'RegionSynonym'
		FROM Region
		WHERE Region.Synonym = ? -- $region";

		$result = sqlsrv_query($sqlConnection, $tsql, array($region));
		if ($result == FALSE) 
		{
			echo "query borken.";
			die;
		}

		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

		if ($row == true)
		{
			$info = new RegionInfo();
			$info->regionId = $row['RegionId'];
			$info->leagueName = $row['RegionName'] . " League";
			$info->synonym = $row['RegionSynonym'];
		}
	} 
	else 
	{
		$info = new RegionInfo();
		$info->regionId = 0;
		$info->leagueName = "All Leagues";
	}

	return $info;
}

?>