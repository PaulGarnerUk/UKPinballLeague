<?php

class SeasonInfo
{
	public $seasonId;
	public $seasonNumber;
	public $seasonYear;
	public $seasonName;
}

function ValidateSeasonNumber($seasonNumber = "all", $sqlConnection)
{
	$info = null;

	if ($seasonNumber !== "all")
	{
		$tsql="
		SELECT
		Season.Id AS 'SeasonId',
		Season.SeasonNumber AS 'SeasonNumber',
		Season.Year AS 'SeasonYear'
		FROM Season
		WHERE SeasonNumber = ? -- $seasonNumber";

		$result = sqlsrv_query($sqlConnection, $tsql, array($seasonNumber));
		if ($result == FALSE) 
		{
			echo "query borken.";
			die;
		}

		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

		if ($row == true)
		{
			$info = new SeasonInfo();
			$info->seasonId = $row['SeasonId'];
			$info->seasonNumber = $row['SeasonNumber'];
			$info->seasonYear = $row['SeasonYear'];
			$info->seasonName = "Season " . $row['SeasonNumber'];
		}
	}
	else 
	{
		// For 'all' return a blank 'all seasons' object.
		$info = new SeasonInfo();
		$info->seasonId = 0;
		$info->seasonNumber = 0;
		$info->seasonYear = 0;
		$info->seasonName = "All Seasons";
	}

	return $info;
}

?>