<?php

//https://www.awbkoeln.de/api/calendar?building_number=28&street_code=6057&start_year=2018&end_year=2018&start_month=1&end_month=12&form=json

namespace AwbTotay;
require __DIR__ . '/vendor/autoload.php';
use JsonPath\JsonObject;


class awbView
{
    function showDay(int $day, int $month, int $year)
    {
        if (!$json = file_get_contents('assets/calendar.json')) exit("Error: Cannot read json file.");
		$json = json_decode($json);
		$jsonObject = new JsonObject($json);
		$jsonpath = "$.data[?(@.day == ".$day." and @.month == ".$month." and @.year == ".$year.")].type";
		$tons = ($jsonObject->get($jsonpath));
		return ($this->translateLabel($tons));
    }

    function translateLabel($array) {
    	foreach ($array as $item) {    		
    		if ($item == 'brown') $returnArray[] = 'Bio';
    		elseif ($item == 'blue') $returnArray[] = 'Papier';
    		elseif ($item == 'grey') $returnArray[] = 'Restmüll';
    		elseif ($item == 'wertstoff') $returnArray[] = 'Gelbe';
    		else $returnArray[] = 'unbekannt';
    	}
    	return $returnArray;
    }
}


$view = new awbView;
$return = $view->showDay(7,2,2018);
print_r($return);


?>