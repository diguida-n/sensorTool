<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetectionController extends Controller
{
    public function getSensorsData(Request $request,$enterprise)
    {
    	$results=[];
        if($enterprise==0)
            $sites = Site::where('id',auth()->user()->site_id)->get();
        else
            $sites = Enterprise::find($enterprise)->sites;

    	foreach ($sites as $site) {
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
    
    public function dashboard()
    {
        return view('backpack::dashboard');
    }

    public function transmitSensorData(Request $request)
    {
        $data = $request->all();
        if(!isset($data['email']) || !$data['email'] || !isset($data['password']) || !$data['password'])
            return response()->json(["code"=>401,"message"=>"Unauthorized Error","description"=>"Credentials not Valid"])->setStatusCode(401);
        $user = Auth::attempt([
                                "email"=>$data['email'],
                                "password"=>$data['password']
                ]);
        if(!$user)
            return response()->json(["code"=>401,"message"=>"Unauthorized Error","description"=>"Credentials not Valid"])->setStatusCode(401);
        $user = auth()->user();
        if(!$user->site)
            return response()->json(["code"=>401,"message"=>"Unauthorized Error","description"=>"Not Authorized"])->setStatusCode(401);
        $site = $user->site;
        $sensors = $site->sensors;
        $sensorsJson=[];
        $totalDetections = 0;
        foreach ($sensors as $s) {
            


            $detections=$s->detections;
            $detectionsJson=[];
            foreach ($detections as $d) {
                $detectionsJson[]=[
                    "value"=>$d->value,
                    // "valueDecoded"=>$d->getValueDecoded(),
                    "message"=>[
                        'malfunction'=>$d->message->malfunction,
                        'exception'=>$d->message->exception,
                        'description'=>$d->message->description
                    ]
                ];

            }
            $sensorsJson[]=[
                'minimumValueAttended'=>$s->min_attended,
                'maximumValueAttended'=>$s->max_attended,
                'sensorDetails'=>[
                    "brand"=>$s->sensorCatalog->brand->name,
                    "sensorType"=>$s->sensorCatalog->sensorType->name,
                    "name"=>$s->sensorCatalog->name,
                    "description"=>$s->sensorCatalog->description,
                    "specs"=>[
                        "minimumValueDetectable"=>$s->sensorCatalog->min_detectable,
                        "maximumValueDetectable"=>$s->sensorCatalog->max_detectable,
                    ]

                ],
                "numberOfDetections"=>count($detectionsJson),
                "detections"=>$detectionsJson

            ];
            $totalDetections+=count($detectionsJson);
        }
        $response=[
            "enterpriseName"=>$site->enterprise->businessName,
            "enterpriseAddress"=>$site->enterprise->address,
            "enterpriseVat"=>$site->enterprise->vatNumber,
            "siteName"=>$site->name,
            "siteAddress"=>$site->address,
            "siteType"=>$site->siteType->name,
            "numberOfSensors"=>count($sensorsJson),
            "numberOfTotalDetections"=>$totalDetections,
            "sensors"=>$sensorsJson,
        ];

        return [    "code"=>200,
                    "message"=>"Success",
                    "description"=>"Success",
                    "response"=>$response
        ];
    }
}
