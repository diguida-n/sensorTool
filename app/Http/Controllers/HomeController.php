<?php

namespace App\Http\Controllers;

use App\Mail\FormInfoRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('home');
    }

    public function submitInfoRequest(Request $request)
    {

        $email      = $request->email;
        $name       = $request->name;
        $phone      = $request->phone;
        $message    = $request->message;

        Mail::to('sensortoolsrl@gmail.com')->send(new FormInfoRequest($email, $name, $phone, $message));

        return redirect()->back();
    }
}
