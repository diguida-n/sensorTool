<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        
    	DB::table('detections')->truncate();
    	DB::table('messages')->truncate();
    	DB::table('sensors')->truncate();
    	DB::table('sensor_catalogs')->truncate();
    	DB::table('brands')->truncate();
    	DB::table('sensor_types')->truncate();

    	DB::table('sites')->truncate();
    	DB::table('site_types')->truncate();
    	DB::table('enterprises')->truncate();
    	if(!App\User::where('email',"admin@sensortool.com")->first()){
	    	$u = App\User::create([
	            'name' => "Admin",
	            'email' => "admin@sensortool.com",
	            'password' => bcrypt("admin"),
	        ]);
	    	$u->assignRole('Admin');
	    }
    	DB::table('enterprises')->insert(
    		[
    		'id'=>1,
    		'businessName'=>'Società Pannelli Solari S.r.L.',
    		'address'=>'Strada Zona Industriale , Monopoli',
    		'vatNumber'=>'08012345678'
		    ]
		);
        DB::table('site_types')->insert([
        	[
        	'id'=>1,
        	'name'=>'Campagna 300 ettari'

	        ],[
	        	'id'=>2,
	        	'name'=>'Parcheggi Centro Commerciale'
	        	
	        ],[
	        	'id'=>3,
	        	'name'=>'Azienda manufatturiera'

	        ]
	        
    	]);
        DB::table('sites')->insert([
        	[
	        	'id'=>1,
	        	'name'=>'Terreno appartenente a Società Pannelli Solari S.r.L.',
	        	'address'=>'Zona Commerciale Estesa, Modugno',
	        	'map'=>null,
	        	'description'=>'Zona di produzione energia elettrica massiva',
	        	'enterprise_id'=>1,
	        	'site_type_id'=>1
	        ],[
	        	'id'=>2,
	        	'name'=>'Centro Commerciale Grande Sud',
	        	'address'=>'Zona Commerciale Estesa, Modugno',
	        	'map'=>null,
	        	'description'=>null,
	        	'enterprise_id'=>1,
	        	'site_type_id'=>2
	        	
	        ],[
	        	'id'=>3,
	        	'name'=>'Vasi e non solo S.r.L.',
	        	'address'=>'Zona artigianale PiP,Capurso',
	        	'map'=>null,
	        	'description'=>null,
	        	'enterprise_id'=>1,
	        	'site_type_id'=>3

	        ]
	   	]);
        DB::table('brands')->insert([
        	[
	        	'id'=>1,
	        	'name'=>'Tropek',
	        ],[
	        	'id'=>2,
	        	'name'=>'Sony',
	        	
	        ],[
	        	'id'=>3,
	        	'name'=>'Siemens',
	        ]
	   	]);
        DB::table('sensor_types')->insert([
        	[
	        	'id'=>1,
	        	'name'=>'Sensore di Temperatura',
	        ],[
	        	'id'=>2,
	        	'name'=>'Sensore di Pressione Atmosferica',
	        	
	        ],[
	        	'id'=>3,
	        	'name'=>'Sensore di Umidità',
	        ],[
	        	'id'=>4,
	        	'name'=>'Sensore di Anemometro',
	        ]
	   	]);
        DB::table('sensor_catalogs')->insert([
        	[
	        	'id'=>1,
	        	'name'=>'Misuratore Velocità Vento',
	        	'description'=>null,
	        	'min_detectable'=>4,
	        	'max_detectable'=>100.00,
	        	'sensor_type_id'=>4,
	        	'brand_id'=>2
	        ],[
	        	'id'=>2,
	        	'name'=>'Termometro Atmosferico',
	        	'description'=>null,
	        	'min_detectable'=>-14.0,
	        	'max_detectable'=>50.0,
	        	'sensor_type_id'=>1,
	        	'brand_id'=>1
	        ],[
	        	'id'=>3,
	        	'name'=>'Barometro Amosferico',
	        	'description'=>null,
	        	'min_detectable'=>600,
	        	'max_detectable'=>1800,
	        	'sensor_type_id'=>2,
	        	'brand_id'=>3
	        ],[
	        	'id'=>4,
	        	'name'=>'Misuratore Umidità',
	        	'description'=>null,
	        	'min_detectable'=>0,
	        	'max_detectable'=>100,
	        	'sensor_type_id'=>3,
	        	'brand_id'=>2
	        ],[
	        	'id'=>5,
	        	'name'=>'Portentoso Anemometro',
	        	'description'=>null,
	        	'name'=>'Top di gamma misuratore Velocità Vento',
	        	'min_detectable'=>0.01,
	        	'max_detectable'=>180.00,
	        	'sensor_type_id'=>4,
	        	'brand_id'=>1
	        ]
	   	]);
	   	DB::table('sensors')->insert([
        	[
	        	'id'=>1,
	        	'min_attended'=>10,
	        	'max_attended'=>80,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>1,
	        	'sensor_catalog_id'=>1,
	        	'enterprise_id'=>1
	        ],[
	        	'id'=>2,
	        	'min_attended'=>-2.0,
	        	'max_attended'=>32.0,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>1,
	        	'sensor_catalog_id'=>2,
	        	'enterprise_id'=>1
	        ],[
	        	'id'=>3,
	        	'min_attended'=>1000,
	        	'max_attended'=>1400,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>1,
	        	'sensor_catalog_id'=>3,
	        	'enterprise_id'=>1
	        ],[
	        	'id'=>4,
	        	'min_attended'=>20,
	        	'max_attended'=>60,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>1,
	        	'sensor_catalog_id'=>4,
	        	'enterprise_id'=>1
	        ],[
	        	'id'=>5,
	        	'min_attended'=>10,
	        	'max_attended'=>80,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>1,
	        	'sensor_catalog_id'=>5,
	        	'enterprise_id'=>1
	        ],[//SENSORI SITO 2 
	        	'id'=>6,
	        	'min_attended'=>5,
	        	'max_attended'=>70,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>2,
	        	'sensor_catalog_id'=>1,
	        	'enterprise_id'=>1
	        ],[
	        	'id'=>7,
	        	'min_attended'=>-8.0,
	        	'max_attended'=>38.0,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>2,
	        	'sensor_catalog_id'=>2,
	        	'enterprise_id'=>1
	        ],[//SENSORI SITO 3
	        	'id'=>8,
	        	'min_attended'=>-4.0,
	        	'max_attended'=>35.0,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>3,
	        	'sensor_catalog_id'=>2,
	        	'enterprise_id'=>1
	        ],[
	        	'id'=>9,
	        	'min_attended'=>900,
	        	'max_attended'=>1500,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>3,
	        	'sensor_catalog_id'=>3,
	        	'enterprise_id'=>1
	        ],[
	        	'id'=>10,
	        	'min_attended'=>10,
	        	'max_attended'=>85,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>3,
	        	'sensor_catalog_id'=>4,
	        	'enterprise_id'=>1
	        ],[
	        	'id'=>11,
	        	'min_attended'=>10,
	        	'max_attended'=>160.00,
	        	'longitude'=>null,
	        	'latitude'=>null,
	        	'site_id'=>3,
	        	'sensor_catalog_id'=>5,
	        	'enterprise_id'=>1
	        ]
	   	]);

	   	for ($i=0; $i < 100; $i++) { 
	   		$s = App\Models\Sensor::find(rand(1,11));
	   		$sensorType= $s->sensorCatalog->getSensorTypeName();
			$malfunction=false;
			$exception = false;
			$value=0;

	   		switch ($sensorType) {
	   			case 'Sensore di Temperatura':
	   				$value = rand(-20,60);
	   				if($value< $s->sensorCatalog->min_detectable || $value>$s->sensorCatalog->max_detectable)
	   					$malfunction=true;
	   				else 
	   					if($value < $s->min_attended || $value > $s->max_attended)
	   						$exception = true;
	   				break;
	   			
	   			case 'Sensore di Pressione Atmosferica':
	   				$value = rand(500,1900);
	   				if($value< $s->sensorCatalog->min_detectable || $value>$s->sensorCatalog->max_detectable)
	   					$malfunction=true;
	   				else
	   					if($value < $s->min_attended || $value > $s->max_attended)
	   						$exception = true;
	   				break;
	   			
	   			case 'Sensore di Umidità':
	   				$value = rand(-10,110);
	   				if($value< $s->sensorCatalog->min_detectable || $value>$s->sensorCatalog->max_detectable)
	   					$malfunction=true;
	   				else
	   					$malfunction=false;
	   					if($value < $s->min_attended || $value > $s->max_attended)
	   						$exception = true;
	   				break;
	   			
	   			case 'Sensore di Anemometro':
	   				$value = rand(-5,200);
	   				if($value< $s->sensorCatalog->min_detectable || $value>$s->sensorCatalog->max_detectable)
	   					$malfunction=true;
	   				else
	   					if($value < $s->min_attended || $value > $s->max_attended)
	   						$exception = true;
	   				break;
	   			
	   			default:
	   				$value = rand(-20,1900);
	   				if($value< $s->sensorCatalog->min_detectable || $value>$s->sensorCatalog->max_detectable)
	   					$malfunction=true;
	   				else
	   					if($value < $s->min_attended || $value > $s->max_attended)
	   						$exception = true;
	   				break;

	   		}
	   		$description = "OK";
	   		$sensorDescription = "OK";
	   		if($malfunction){
	   			$sensorDescription="Malfunction";
	   			$description = "Malfunzionamento! Valore rilevato al di sotto della soglia minima percepibile dal sensore";
	   		}
	   		if($exception){
	   			$sensorDescription="Exception";
	   			$description = "Eccezione! Valore rilevato al di sotto della soglia minima aspettata dal sensore";
	   		}
	   		$messageData = [
        		'malfunction'=>$malfunction,
        		'exception'=>$exception,
        		'description'=>$description,
        		'sensor_catalog_id'=>$s->sensor_catalog_id,
        		'enterprise_id'=>1
        	];
	   		$m = App\Models\Message::create($messageData);
	   		$trueValue = $s->getTrasmissionProtocolIdentifier();
	   		$now = Carbon::now();
	   		$now = str_replace("-", "/", $now);
	   		$now = str_replace(" ", "*", $now);

	   		$trueValue.="*".$now."*".$value."*".$sensorDescription;
	   		echo $trueValue."\n";
	   		App\Models\Detection::create([
	   			"value"=>$trueValue,
	   			"sensor_id"=>$s->id,
	   			"message_id"=>$m->id,
	   			"enterprise_id"=>1
	   		]);
	   	}


    }
}
