<?php

namespace App\Mail;

use App\Models\Enterprise;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class AddNewUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $enterprise;
    protected $url;
    protected $cryptedData;
    protected $role;
    protected $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Enterprise $enterprise,$role,$email)
    {
        $this->enterprise = $enterprise;
        $this->role = $role;
        $cryptedData = [];
        $this->email = $email;

        $cryptedData['role'] = $role;
        $cryptedData['enterprise_id'] = $enterprise->id;
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
            ->markdown('email.addNewCompanyManager')
            ->with([
                'url' => $this->url,
                'enterprise' => $this->enterprise->businessName,
                'role' => $this->role,
            ]);
    }
}
