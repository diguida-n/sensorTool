<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use Illuminate\Http\Request;

class DetectionController extends Controller
{
    public function getSensorsData(Request $request,$enterprise)
    {
    	$results=[];
    	$e = Enterprise::find($enterprise);

    	foreach ($e->sites as $site) {
    		$siteSensors = [];
    		foreach ($site->sensors as $sensor) {
    			// $sensor->getSensorType()->id
				// 1: Sensore di Temperatura
	        	// 2: Sensore di Pressione Atmosferica
	        	// 3: Sensore di Umidità
	        	// 4: Sensore di Anemometro
    			$siteSensors[$sensor->id]["graphicType"]=$sensor->getSensorType()->id;
    			foreach ($sensor->detections as $d) {

    				switch($sensor->getSensorType()->id){
                        case 1:
                        case 2:
							$siteSensors[$sensor->id]['data'][]=[
		    					"date"=>$d->created_at->format('d/m/Y H:i:s'),
		    					"value"=>$d->getValueDecoded(),
		    				];
                        	break;
                        case 3:
							$siteSensors[$sensor->id]['data'][]=[
		    					"date"=>$d->created_at->format('d/m/Y H:i:s'),
		    					"value"=>$d->getValueDecoded(),
		    					"color"=>"#67b7dc",
		    					"bullet"=>'/img/drop.png'
		    				];
                        break;
                        case 4:
							$siteSensors[$sensor->id]['data'][]=[
		    					"date"=>$d->created_at->format('d/m/Y H:i:s'),
		    					"value"=>$d->getValueDecoded(),
		    					"color"=>"#7F8DA9",
		    					"bullet"=>'/img/fan.png'
		    				];
                        break;
                    }
    				
    			}
    		}
    		$results[$site->id]=$siteSensors;
    	}
    	return $results;
    }
}
