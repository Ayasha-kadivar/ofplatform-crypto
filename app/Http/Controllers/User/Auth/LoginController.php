<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Html;
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

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function showLoginForm()
    {
        $pageTitle = "Login";
        return view($this->activeTemplate . 'user.auth.login', compact('pageTitle'));
    }

  public function login(Request $request)
{
    $this->validateLogin($request);

    $request->session()->regenerateToken();

    if (!verifyCaptcha()) {
        $notify[] = ['error', 'Invalid captcha provided'];
        return back()->withNotify($notify);
    }

    if ($this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);
        return $this->sendLockoutResponse($request);
    }

    $login = request()->input('username');

    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $field = 'email';
    } else {
        $field = 'username';
    }

    $password = html_entity_decode($request->input('password'));

    $credentials = [
        $field => $login,
        'password' => $password,
    ];

    // Log the provided password
    Log::info('Provided password: ' . $password);

    // Fetch the user by the provided field (username or email)
    $user = User::where($field, $login)->first();

    // Log the user's hashed password from the database
    if ($user) {
        Log::info('Stored hashed password: ' . $user->password);
        // if($user->is_block == 1){
        //     $notify[] = ['error', 'Your account has been deactivated as Maintenance fee has not been paid on time. Kindly reach to issue@ourfamily.support for more information!'];
        //     return back()->withNotify($notify);
        // }
    } else {
        Log::info('User not found');
    }

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $user = $this->guard()->user();
        if ($user->ev != 1) {
            //$user->ev = '1';
            // $user->email = $request->input('email');
            $user->sv = '1';
            $user->save();
        }

        return $this->sendLoginResponse($request);
    }

    $this->incrementLoginAttempts($request);
    return $this->sendFailedLoginResponse($request);
}


    public function findUsername()
    {
        $login = request()->input('username');
    
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }
    

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
{
    $request->validate([
        'username' => [
            'required', function ($attribute, $value, $fail) {
                $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
                if (!preg_match('/^([a-zA-Z0-9._-]{6,25})$/', $value) && !preg_match($pattern, $value)) {
                    $fail("The $attribute field must be a valid username or email.");
                }
            }
        ],
        'password' => [ 'string'],
    ], [
        'username.required' => 'The username or email field is required.',
        'password.required' => 'The password field is required.',
    ]);
}


    // public function findUsername()
    // {
    //     $login = request()->input('username');

    //     $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    //     request()->merge([$fieldType => $login]);
    //     return $fieldType;
    // }

    // public function username()
    // {
    //     return $this->username;
    // }

    // protected function validateLogin(Request $request)
    // {

    //     $request->validate([
    //         $this->username() => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    // }

    public function logout()
    {
        $this->guard()->logout();

        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return to_route('user.login')->withNotify($notify);
    }





    public function authenticated(Request $request, $user)
    {
        $user->tv = $user->ts == 1 ? 0 : 1;
        $user->save();
        $ip = getRealIP();
        $exist = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',', $info['long']);
            $userLogin->latitude =  @implode(',', $info['lat']);
            $userLogin->city =  @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();

        return to_route('user.home');
    }
}
