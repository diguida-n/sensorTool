<?php

namespace App\Mail;

use App\Models\Enterprise;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddNewCompanyManager extends Mailable
{
    use Queueable, SerializesModels;

    protected $enterprise;
    protected $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Enterprise $enterprise)
    {
        $this->enterprise = $enterprise;
        $this->url = url('admin/register');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = $this->url;
        $enterprise = $this->enterprise;
        return $this->from('sensortoolsrl@gmail.com',"SensorTool srl")
            ->markdown('email.addNewCompanyManager')
            ->with([
                'url' => $this->url,
                'enterprise' => $this->enterprise->businessName
            ]);
        // return $this->view('email.addNewCompanyManager',compact('url','enterprise'));
    }
}
