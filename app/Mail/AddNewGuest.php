<?php

namespace App\Mail;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class AddNewGuest extends Mailable
{
    use Queueable, SerializesModels;

    protected $site;
    protected $url;
    protected $cryptedData;
    protected $role;
    protected $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Site $site,$role,$email)
    {
        $this->site = $site;
        $this->role = $role;
        $this->email = $email;
        $cryptedData = [];

        $cryptedData['role'] = $role;
        $cryptedData['site_id'] = $site->id;
        $cryptedData['expiring_date'] = Carbon::now('Europe/Rome')->addDay()->toDateTimeString();
        $cryptedData['email'] = $this->email;
        $this->cryptedData = Crypt::encryptString(json_encode($cryptedData));

        $this->url = route('registerUser',$this->cryptedData);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sensortoolsrl@gmail.com',"SensorTool srl")
            ->markdown('email.addNewGuest')
            ->with([
                'url' => $this->url,
                'site' => $this->site->name,
                'enterprise' => $this->site->enterprise->businessName,
                'role' => $this->role,
            ]);
    }
}
