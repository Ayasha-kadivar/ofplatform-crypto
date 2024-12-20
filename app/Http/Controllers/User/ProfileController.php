<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\ProfileUpdate;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = auth()->user();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
        ]);

        $user = auth()->user();

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;

        $user->address = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];

        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $passwordValidation = Password::min(6);
        $general = gs();
        if ($general->secure_password) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$passwordValidation]
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changes successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }


    public function profileUpdate()
    {
        $user = auth()->user();
        $pageTitle = 'Profile Update';
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'user.profile_update', compact('pageTitle', 'user','mobileCode','countries'));
    }

    // public function updateProfile(Request $request)
    // {
    //     // validate the form data
    //     $validatedData = $request->validate([
    //         'email'     => 'required|email|unique:users,email',
    //         'username' => 'required|unique:users|min:6|max:25',
    //         'mobile'    => 'required|unique:users,mobile',
    //     ]);

    //     // $user->country_code  = $request->country_code;
    //             // $user->mobile  = $request->mobile_code.$request->mobile;


    //     // store the updated fields in the session
    //     Session::put('profile_updates', $request->all());

    //     // generate OTP
    //     $otp = rand(100000, 999999);
    //     Session::put('otp', $otp);

    //     // send the OTP to the user via email
    //     $user = User::findOrFail(Auth::user()->id);
    //     // Mail::to($user->email)->send(new OtpVerificationMail($otp));
    //     notify($user, 'EVER_CODE', [
    //         'code' => $otp
    //     ], ['email']);

    //     // redirect to the OTP verification screen
    //     return redirect()->route('user.profile.verify');
    // }
    public function updateProfile(Request $request)
    {
        // validate the form data
        $validatedData = $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', Auth::user()->id);
                })->ignore(Auth::user()->email, 'email'),
            ],
            'username' => [
                'required',
                'min:6',
                'max:25',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', Auth::user()->id);
                })->ignore(Auth::user()->username, 'username'),
            ],
            'mobile' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', Auth::user()->id);
                })->ignore(Auth::user()->mobile, 'mobile'),
            ],
        ], [
            'email.unique' => 'The email address has already been taken!',
            'username.unique' => 'The username has already been taken!',
            'mobile.unique' => 'The mobile number has already been taken!',
        ]);    


        // get the user's IP address
        $userIp = getRealIP();

        // get the user's browser and operating system
        $agent = osBrowser();
        $browser = @$agent['browser'];
        $os = @$agent['os_platform'];

        // insert the updated fields into the profile_updates table
        $profileUpdate = new ProfileUpdate;
        $profileUpdate->user_id = Auth::user()->id;
        $profileUpdate->username = $request->username;
        $profileUpdate->email = $request->email;
        $profileUpdate->country_code = $request->country_code;
        $profileUpdate->mobile = $request->mobile_code.$request->mobile;
        $profileUpdate->ver_code = rand(100000, 999999);
        $profileUpdate->ver_code_send_at = now();
        $profileUpdate->status = 0;
        $profileUpdate->user_ip = $userIp;
        $profileUpdate->browser = $browser;
        $profileUpdate->os = $os;
        $profileUpdate->save();
        $user_table = auth()->user();
        $user_table->email = $request->email;
        $user_table->save();
        
        // dd($profileUpdate);

        // send the OTP to the user via email
        // $user = ProfileUpdate::findOrFail(Auth::user()->id);
        // $user = ProfileUpdate::where('user_id', auth()->id())->firstOrFail();
        $user = User::findOrFail(Auth::user()->id);

        // Mail::to($user->email)->send(new OtpVerificationMail($otp));
        notify($user, 'EVER_CODE', [
            'code' => $profileUpdate->ver_code
        ], ['email']);

        // redirect to the OTP verification screen
        return redirect()->route('user.profile.verify');
    }

    public function showOtpVerificationForm() 
    {
        $pageTitle = 'Verify Email';
        $user = auth()->user();
        return view($this->activeTemplate.'user.profile_verify', compact('user', 'pageTitle'));
    }

    public function verifyOtp(Request $request)
    {
        // validate the form data
        $validatedData = $request->validate([
            'code' => ['required', 'numeric', 'digits:6'],
            // 'email' => ['required', 'string', 'email', 'max:50'],
        ]);

        // check if the OTP matches the one in the profile_updates table
        $profileUpdate = ProfileUpdate::where('ver_code', $validatedData['code'])->where('status', 0)->first();
        if ($profileUpdate) {
            // get the updated profile fields from the profile_updates table
            $username = $profileUpdate->username;
            $email = $profileUpdate->email;
            $countryCode = $profileUpdate->country_code;
            $mobile = $profileUpdate->mobile;

            // update the user's profile
            $user = User::findOrFail(Auth::user()->id);
            $user->username = $username;
            $user->email = $email;
            $user->country_code = $countryCode;
            $user->is_verify_email = now();
            $user->mobile = $mobile;
            $user->save();

            // update the profile_updates table with status = 1
            $profileUpdate->status = 1;
            $profileUpdate->save();

            // redirect back to the profile page
            $notify[] = ['success', 'Profile Updated successfully'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        } else {
            // if the OTP is invalid, redirect back to the OTP verification screen with an error message
            $notify[] = ['success', 'The OTP doesn\'t match!'];
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }

    // public function verifyOtp(Request $request)
    // {
    //     // validate the form data
    //     $validatedData = $request->validate([
    //         'code' => ['required', 'numeric', 'digits:6'],
    //         // 'email' => ['required', 'string', 'email', 'max:50'],
    //     ]);

    //     // check if the OTP matches the one in the session
    //     $otp = Session::get('otp');
    //     // dd($otp);
    //     if ($validatedData['code'] == $otp) {

    //         // get the updated profile fields from the session
    //         $profileUpdates = Session::get('profile_updates');

    //         // dd($profileUpdates);

    //         // update the user's profile
    //         $user = User::findOrFail(Auth::user()->id);
    //         $user->username = $profileUpdates['username'];
    //         $user->email = $profileUpdates['email'];
    //         // $user->country = $profileUpdates['country'];
    //         $user->country_code  = $profileUpdates['country_code'];
    //         $user->mobile  = $profileUpdates['mobile_code'].$profileUpdates['mobile'];
    //         $user->save();

    //         // clear the session
    //         Session::forget('profile_updates');
    //         Session::forget('otp');

    //         // redirect back to the profile page
    //         $notify[] = ['success', 'Profile Updated successfully'];
    //         return redirect()->route('user.profile.setting')->withNotify($notify);
    //     } else {
    //         // if the OTP is invalid, redirect back to the OTP verification screen with an error message
    //         $notify[] = ['success', 'The OTP doesn\'t match!'];
    //         return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
    //     }
    // }

}
