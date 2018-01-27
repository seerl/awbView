<?php

// Get your URL with Street from https://www.awbkoeln.de/abfuhrkalender/ and 
// look for calendar? URL Request (Chrome F12 :)

// STREET_ID = StreetID from https://wiki.openstreetmap.org/wiki/Cologne/Stra%C3%9Fenverzeichnis
// HNR = Hausnummer :)
//https://www.awbkoeln.de/api/calendar?building_number=HNR&street_code=STREET_ID&start_year=2018&end_year=2018&start_month=1&end_month=12&form=json

namespace AwbToday;
require __DIR__ . '/vendor/autoload.php';
use JsonPath\JsonObject;

setlocale(LC_ALL, "de_DE");

class awbView
{
    function showDay(int $day, int $month, int $year)
    {
    	$this->day = $day; 
    	$this->month = $month;
    	$this->year = $year;

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
    	$this->tons = $returnArray;
    	return $returnArray;
    }

    function renderView() {
    	$date = $this->day.'.'.$this->month.'.'.$this->year;
    	$dayOfWeek = date('l', strtotime($date));
    	$html = '<span class="heading">Müll: '.$dayOfWeek." (".$date.")".'</span>';
    	$html .= '<span class="tonne">';
    	if ($this->tons)
    		foreach ($this->tons as $ton) {
    			$html .= $ton."<br/>";
    		} else {
    			//do nothing, leave empty
    		}
    		$html .= "</span>";
    	echo '<html><head><link rel="stylesheet" type="text/css" media="all" href="assets/style.css"></head><body><div class="box-content dummy">'.$html.'</div></body></html>';
    }
}


$view = new awbView;
$tomorrow = time() + 60 * 60 * 24;
//$return = $view->showDay(date('d',$tomorrow),date('m',$tomorrow),date('Y',$tomorrow));
$return = $view->showDay(7,2,date('Y',$tomorrow));
$view->renderView();


?>