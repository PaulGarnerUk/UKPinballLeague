<?php

class LeagueInfo
{
	public $note;
	public $aQualifyingPlaces;
	public $bQualifyingPlaces;
	public $aQualifyingDescription;
	public $bQualifyingDescription;
	public $numberOfQualifyingMeets;
}

function GetLeagueInfo($region, $season)
{
	$info = new LeagueInfo();

	switch ($region)
	{
		case "n":
			$info = GetNorthernLeagueInfo($season);
			break;
		case 'sw':
			$info = GetSouthWestLeagueInfo($season);
			break;
		case "m":
			$info = GetMidlandsLeagueInfo($season);
			break;
		case "lse":
			$info = GetLondonSouthEastLeagueInfo($season);
			break;
		case "i":
			$info = GetIrishLeagueInfo($season);
			break;
		case "s":
			$info = GetScottishLeagueInfo($season);
			break;
		case "ea":
			$info = GetEastAnglianLeagueInfo($season);
			break;
		case "w":
			$info = GetSouthWalesLeagueInfo($season);
			break;
	}

	return $info;
}

function GetNorthernLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;
	$info->numberOfQualifyingMeets = 4;

	switch ($season)
	{
		case 1:
			$info->numberOfQualifyingMeets = 3;
			break;

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
		case 16:
			$info->aQualifyingPlaces = 7;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 17:
			$info->bQualifyingPlaces = 8;
			$info->bQualifyingDescription = "Regional finals qualifying place.";
	}

	return $info;
}

function GetSouthWestLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;
	$info->numberOfQualifyingMeets = 4;

	switch ($season)
	{
		case 2:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 3:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 4:
		case 5:
		case 6:
		case 7:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 8:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 9:
		case 10:
		case 11:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 12:
			$info->note="There was no league in the South West region this season.";
			break;
		case 13:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 14:
			$info->note="The 2020 season was not completed due to the impact of Covid-19.";
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 15:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 16:
			$info->aQualifyingPlaces = 6;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 17:
			$info->aQualifyingPlaces = 2;
			$info->aQualifyingDescription = "National finals qualifying place.";
			break;
	}

	return $info;
}

function GetMidlandsLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;
	$info->numberOfQualifyingMeets = 4;

	switch ($season)
	{
		case 2:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 3:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 4:
		case 5:
		case 6:
		case 7:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 8:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 9:
		case 10:
		case 11:
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 12:
		case 13:
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 14:
			$info->note="The 2020 season was not completed due to the impact of Covid-19.";
			$info->aQualifyingPlaces = 4;
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
		case 16:
			$info->aQualifyingPlaces = 7;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 17:
			$info->bQualifyingPlaces = 10;
			$info->bQualifyingDescription = "Regional finals qualifying place.";
			break;
	}

	return $info;
}

function GetLondonSouthEastLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;
	$info->numberOfQualifyingMeets = 4;

	switch ($season)
	{
		case 2:
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 3:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 4:
		case 5:
		case 6:
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
			$info->aQualifyingPlaces = 6;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 12:
			$info->aQualifyingPlaces = 5;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 13:
			$info->aQualifyingPlaces = 6;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 14:
			$info->note="The 2020 season was not completed due to the impact of Covid-19.";
			$info->aQualifyingPlaces = 6;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 15:
			$info->aQualifyingPlaces = 6;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 16:
			$info->aQualifyingPlaces = 8;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 17:
			$info->bQualifyingPlaces = 8;
			$info->bQualifyingDescription = "Regional finals qualifying place.";
			break;
	}

	return $info;
}

function GetIrishLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;
	$info->numberOfQualifyingMeets = 4;

	switch ($season)
	{
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		case 7:
		case 8:
			$info->note="There was no league in the Irish region this season.";
			break;
		case 9:
			$info->numberOfQualifyingMeets = 2;
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 10:
		case 11:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 12:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 13:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 14:
			$info->note="The 2020 season was not completed due to the impact of Covid-19.";
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 15:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 16:
			$info->aQualifyingPlaces = 6;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 17:
			$info->aQualifyingPlaces = 2;
			$info->aQualifyingDescription = "National finals qualifying place.";
			break;
	}

	return $info;
}

function GetScottishLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;
	$info->numberOfQualifyingMeets = 3; // usually three for scottish league

	switch ($season)
	{
		case 1:
			$info->note="There was no league in the Scottish region this season.";
			break;
		case 2:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals qualifying place.";
			$info->numberOfQualifyingMeets = 4;
			break;
		case 3:
		case 4:
			$info->aQualifyingPlaces = 2;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 5:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 6:
		case 7:
			$info->aQualifyingPlaces = 2;
			$info->aQualifyingDescription = "League finals qualifying place.";
			break;
		case 8:
		case 9:
		case 10:
		case 11:
			$info->aQualifyingPlaces = 2;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 3;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 12:
		case 13:
			$info->aQualifyingPlaces = 4;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 14:
			$info->note="The 2020 season was not completed due to the impact of Covid-19.";
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 15:
			$info->aQualifyingPlaces = 3;
			$info->aQualifyingDescription = "League finals A qualifying place.";
			$info->bQualifyingPlaces = 4;
			$info->bQualifyingDescription = "League finals B qualifying place.";
			break;
		case 16:
			$info->aQualifyingPlaces = 6;
			$info->aQualifyingDescription = "League finals qualifying place.";
			$info->numberOfQualifyingMeets = 4; // six meets this season for the first time
			break;
		case 17:
			$info->numberOfQualifyingMeets = 4;
			$info->aQualifyingPlaces = 2;
			$info->aQualifyingDescription = "National finals qualifying place.";
			break;
	}

	return $info;
}

function GetEastAnglianLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;
	$info->numberOfQualifyingMeets = 4;

	switch ($season)
	{
		case 17:
			$info->bQualifyingPlaces = 10;
			$info->bQualifyingDescription = "Regional finals qualifying place.";
			break;
		default:
			$info->note="There was no league in the East Anglian region this season.";
			break;
	}

	return $info;
}

function GetSouthWalesLeagueInfo($season)
{
	$info = new LeagueInfo();
	$info->aQualifyingPlaces = 0;
	$info->bQualifyingPlaces = 0;
	$info->numberOfQualifyingMeets = 4;

	switch ($season)
	{
		case 17:
			$info->bQualifyingPlaces = 8;
			$info->bQualifyingDescription = "Regional finals qualifying place.";
			break;
		default:
			$info->note="There was no league in the South Wales region this season.";
			break;
	}

	return $info;
}
?>
