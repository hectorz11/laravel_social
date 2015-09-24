<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Logic\User\UserRepository;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Input;
use Socialite;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getLogin()
    {
        return view('auth.login');
    }
 
    public function postLogin()
    {
        $email      = Input::get('email');
        $password   = Input::get('password');
        $remember   = Input::get('remember');
 
        if($this->auth->attempt([
            'email'     => $email,
            'password'  => $password
        ], $remember == 1 ? true : false))
        {
            if( $this->auth->user()->hasRole('user'))
            {
                return redirect()->route('user.home');
            }
 
            if( $this->auth->user()->hasRole('administrator'))
            {
                return redirect()->route('admin.home');
            }
        }
        else
        {
            return redirect()->back()
                ->with('message','Incorrect email or password')
                ->with('status', 'danger')
                ->withInput();
        }
 
    }
 
    public function getLogout()
    {
        \Auth::logout();
 
        return redirect()->route('auth.login')
            ->with('status', 'success')
            ->with('message', 'Logged out');
    }

    //-----------------------------------------------------------------------------
    public function getRegister()
    {
        return view('auth.register');
    }
 
    public function postRegister()
    {
        $input = Input::all();

        $validator = Validator::make($input, User::$rules, User::$messages);
        if($validator->fails())
        {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
 
        $data = [
            'first_name'    => $input['first_name'],
            'last_name'     => $input['last_name'],
            'email'         => $input['email'],
            'password'      => $input['password']
        ];
 
        $this->userRepository->register($data);
 
        return redirect()->route('auth.login')
            ->with('status', 'success')
            ->with('message', 'You are registered successfully. Please login.');
    }
    //------------------------------------------------------------------------------
    public function getSocialRedirect( $provider )
    {
        $providerKey = \Config::get('services.' . $provider);
        if(empty($providerKey))
            return view('pages.status')
                ->with('error','No such provider');
 
        return Socialite::driver( $provider )->redirect();
 
    }
 
    public function getSocialHandle( $provider )
    {
        $user = Socialite::driver( $provider )->user();
 
        $socialUser = null;
 
        //Check is this email present
        $userCheck = User::where('email', '=', $user->email)->first();
        if(!empty($userCheck))
        {
            $socialUser = $userCheck;
        }
        else
        {
            $sameSocialId = Social::where('social_id', '=', $user->id)->where('provider', '=', $provider )->first();
 
            if(empty($sameSocialId))
            {
                //There is no combination of this social id and provider, so create new one
                $newSocialUser = new User;
                $newSocialUser->email              = $user->email;
                $name = explode(' ', $user->name);
                $newSocialUser->first_name         = $name[0];
                $newSocialUser->last_name          = $name[1];
                $newSocialUser->save();
 
                $socialData = new Social;
                $socialData->social_id = $user->id;
                $socialData->provider= $provider;
                $newSocialUser->social()->save($socialData);
 
                // Add role
                $role = Role::whereName('user')->first();
                $newSocialUser->assignRole($role);
 
                $socialUser = $newSocialUser;
            }
            else
            {
                //Load this existing social user
                $socialUser = $sameSocialId->user;
            }
 
        }
 
        $this->auth->login($socialUser, true);
 
        if( $this->auth->user()->hasRole('user'))
        {
            return redirect()->route('user.home');
        }
 
        if( $this->auth->user()->hasRole('administrator'))
        {
            return redirect()->route('admin.home');
        }
 
        return \App::abort(500);
    }
}
