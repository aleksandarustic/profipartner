<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\Storage;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


      /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();

        $authUser = $this->findOrCreateUser($user,$provider);
        Auth::login($authUser);
        return redirect($this->redirectTo);

    }

    private function findOrCreateUser($user,$provider){
        $authUser = User::where('provider_id',$user->id)->first();
        if($authUser){
            return $authUser;
        }
        else{
            if(isset($user->avatar)){
                $fileContents = file_get_contents($user->avatar);
                $file_name_to_store = 'avatar_' . time() . '.png';
                Storage::put('public/profile_images/'.$file_name_to_store, $fileContents);
                $user->photo = $file_name_to_store;
            }
            else{
                $user->photo="profile.png";
            }
            if(empty($user->password)){
                $user->password = 'password';
            }
            return User::create([
                'name' => $user->name,
                'email' => $user->email,
                'provider' => strtoupper($provider),
                'provider_id' => $user->id,
                'photo' => $user->photo,
                'password' => Hash::make($user->password)
            ])->attachRole('user');
        }
    }
}
