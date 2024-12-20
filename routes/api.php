<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::namespace ('Api')->name('api.')->group(function () {
    Route::get('get-countries', function () {
        $c        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $notify[] = 'General setting data';
        foreach ($c as $k => $country) {
            $countries[] = [
                'country'      => $country->country,
                'dial_code'    => $country->dial_code,
                'country_code' => $k,
            ];
        }
        return response()->json([
            'remark'  => 'country_data',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'countries' => $countries,
            ],
        ]);
    });

    Route::namespace ('Auth')->group(function () {
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController@register');

        Route::controller('ForgotPasswordController')->group(function () {
            Route::post('password/email', 'sendResetCodeEmail')->name('password.email');
            Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
            Route::post('password/reset', 'reset')->name('password.update');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        //authorization
        Route::controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorization')->name('authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
            Route::post('verify-email', 'emailVerification')->name('verify.email');
            Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
            Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
        });

        Route::middleware(['check.status'])->group(function () {
            Route::post('user-data-submit', 'UserController@userDataSubmit')->name('data.submit');
            Route::post('save/device/token', 'UserController@getDeviceToken')->name('add.device.token');

            Route::middleware('registration.complete')->group(function () {
                Route::controller('UserController')->group(function () {
                    Route::get('dashboard', 'dashboard');
                    Route::get('user-info', 'userInfo');

                    Route::post('profile-setting', 'submitProfile');
                    Route::post('change-password', 'submitPassword');

                    //KYC
                    Route::get('kyc-form', 'kycForm')->name('kyc.form');
                    Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                    //Report
                    Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                    Route::get('transactions', 'transactions')->name('transactions');

                    Route::get('my-referrals', 'myReferrals');
                    Route::post('balance-transfer', 'balanceTransfer');

                });

                // Withdraw
                Route::controller('WithdrawController')->group(function () {
                    Route::get('withdraw-method', 'withdrawMethod')->name('withdraw.method')->middleware('kyc');
                    Route::post('withdraw-request', 'withdrawStore')->name('withdraw.money')->middleware('kyc');
                    Route::post('withdraw-request/confirm', 'withdrawSubmit')->name('withdraw.submit')->middleware('kyc');
                    Route::get('withdraw/history', 'withdrawLog')->name('withdraw.history');
                });

                // Payment
                Route::controller('PaymentController')->group(function () {
                    Route::get('deposit/methods', 'methods')->name('deposit');
                    Route::post('deposit/insert', 'depositInsert')->name('deposit.insert');
                    Route::get('deposit/confirm', 'depositConfirm')->name('deposit.confirm');
                    Route::get('deposit/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');
                    Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');
                });

                Route::controller('InvestController')->prefix('invest')->group(function () {
                    Route::get('/', 'invest');
                    Route::get('plans', 'allPlans');
                    Route::post('store', 'storeInvest');
                });

            });
        });

        Route::get('logout', 'Auth\LoginController@logout');
    });

    Route::controller('FrontendController')->group(function () {
        Route::get('logo-favicon', 'logoFavicon');
        Route::get('language/{code}', 'language');
        Route::get('general-setting', 'generalSetting');
        Route::get('policy', 'policy');
        Route::get('faq', 'faq');
    });

});
