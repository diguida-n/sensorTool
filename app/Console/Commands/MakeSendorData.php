<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class MakeSendorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensor:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        for ($i=0; $i < 100; $i++) { 
            $s = \App\Models\Sensor::find(rand(1,11));
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
            $m = \App\Models\Message::create($messageData);
            $trueValue = $s->getTrasmissionProtocolIdentifier();
            $now = Carbon::now();
            $now = str_replace("-", "/", $now);
            $now = str_replace(" ", "*", $now);

            $trueValue.="*".$now."*".$value."*".$sensorDescription;
            echo $trueValue."\n";
            \App\Models\Detection::create([
                "value"=>$trueValue,
                "sensor_id"=>$s->id,
                "message_id"=>$m->id,
                "enterprise_id"=>1
            ]);
        }
    }
}
