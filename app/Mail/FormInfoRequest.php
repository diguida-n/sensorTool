<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormInfoRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $name, $phone, $message)
    {
        $this->email = $email;
        $this->name = $name;
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sensortoolsrl@gmail.com',"SensorTool srl")
            ->markdown('email.formInfoRequest')
            ->with([
                'email' => $this->email,
                'name' => $this->name,
                'phone' => $this->phone,
                'message' => $this->message,
            ]);
    }
}
