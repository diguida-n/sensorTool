<?php

namespace App\Http\Controllers\Auth;

use Backpack\Base\app\Http\Controllers\Auth;
use Backpack\Base\app\Http\Controllers\Auth\RegisterController as BackpackRegisterController;
use Backpack\Base\app\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Validator;

class RegisterUserController extends BackpackRegisterController
{
    protected $data = []; // the information we send to the view

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        // Where to redirect users after login / registration.
        $this->redirectTo = property_exists($this, 'redirectTo') ? $this->redirectTo
            : config('backpack.base.route_prefix', 'dashboard');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $user_model_fqn = config('backpack.base.user_model_fqn');
        $user = new $user_model_fqn();
        $users_table = $user->getTable();
        if(array_key_exists('cryptedData',$data)){
            $now = Carbon::now('Europe/Rome');
            $data['expired'] = $data['expiring_date']->lte($now);
            if(isset($data['site_id']))
                return Validator::make($data, [
                    'name'     => 'required|max:255',
                    'email'    => 'required|email|max:255|unique:'.$users_table,
                    'password' => 'required|min:6|confirmed',
                    'expiring_date' => 'required|after_or_equal:'.$now->format('Y-m-d H:i:s'),
                    'site_id' => 'required|numeric',
                    'role' => 'required|string'
                ]);
            else
                return Validator::make($data, [
                    'name'     => 'required|max:255',
                    'email'    => 'required|email|max:255|unique:'.$users_table,
                    'password' => 'required|min:6|confirmed',
                    'expiring_date' => 'required|after_or_equal:'.$now->format('Y-m-d H:i:s'),
                    'enterprise_id' => 'required|numeric',
                    'role' => 'required|string'
                ]);

        }else {
            return Validator::make($data, [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255|unique:'.$users_table,
                'password' => 'required|min:6|confirmed',
            ]);
        }
        
        
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user_model_fqn = config('backpack.base.user_model_fqn');
        $user = new $user_model_fqn();

        
        if(array_key_exists('role',$data)){
            
            if(isset($data['site_id']))
                return $user->create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => bcrypt($data['password']),
                    'site_id' => $data['site_id'],
                ])->assignRole($data['role']);
            else
                return $user->create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => bcrypt($data['password']),
                    'enterprise_id' => $data['enterprise_id'],
                ])->assignRole($data['role']);
        }

        return $user->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm($cryptedData = null)
    {
        // if registration is closed, deny access
        if (!config('backpack.base.registration_open') || is_null($cryptedData)) {
            abort(403, trans('backpack::base.registration_closed'));
        }

        $this->data['title'] = trans('backpack::base.register'); // set the page title

        $this->data['cryptedData'] = $cryptedData;
        $data['email'] = null;
        try {
            $decrypted = Crypt::decryptString($cryptedData);
            $decrypted = json_decode($decrypted,true);
            $this->data['email'] = $decrypted['email'];

        } catch (DecryptException $e) {
            abort(403, trans('backpack::base.registration_closed'));
        }
        
        
        return view('backpack::auth.register', $this->data);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // if registration is closed, deny access
        if (!config('backpack.base.registration_open')) {
            abort(403, trans('backpack::base.registration_closed'));
        }
        $requestData = $request->all();
        if(array_key_exists('cryptedData',$requestData)){
            try {
                $decrypted = Crypt::decryptString($requestData['cryptedData']);
                $decrypted = json_decode($decrypted,true);
                unset($requestData->cryptedData);
                if(isset($decrypted['site_id']))
                    $requestData['site_id'] = $decrypted['site_id'];
                else
                    $requestData['enterprise_id'] = $decrypted['enterprise_id'];
                $requestData['role'] = $decrypted['role'];
                $requestData['expiring_date'] = Carbon::createFromFormat('Y-m-d H:i:s',$decrypted['expiring_date']);

            } catch (DecryptException $e) {
                    return redirect()->to($this->getRedirectUrl())
                        ->withInput($request->input())
                        ->withErrors(['cryptedData' => 'Token non valido contattare un amministratore'], $this->errorBag());
            }
           
        }
            
        $this->validator($requestData)->validate();

        $this->guard()->login($this->create($requestData));

        return redirect($this->redirectPath());
        
    }
}
