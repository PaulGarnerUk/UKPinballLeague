<?php

class LeagueInfo
{
	public $notes;
	public $aQualifyingPlaces;
	public $bQualifyingPlaces;
	public $aQualifyingDescription;
	public $bQualifyingDescription;
}

function GetLeagueInfo($region, $season)
{
	$info = new LeagueInfo();

	switch ($region)
	{
		case "n":
			$info = GetNorthernLeagueInfo($season);
			break;

		case "m":
			break;
	}

	return $info;
}

function GetNorthernLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;

	switch ($season)
	{
		case 2:
		case 3:
		case 4:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 5:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 6:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 7:
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 8:
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 9:
		case 10:
		case 11:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 12:
			$info->aQualifyingPlaces = 6;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 13:
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 14:
			$info->note="The 2020 season was not completed due to the impact of Covid-19.";
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 15:
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
	}

	return $info;
}

?>