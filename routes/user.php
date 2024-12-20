<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Metamask\MetamaskController;
use App\Http\Controllers\Nft\NftrentController;

use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use Illuminate\Http\Request;
use App\Http\Csp\CustomPolicy;
use Spatie\Csp\AddCspHeaders;
use App\Http\Controllers\GoldMinerExcavatorNFTController;
// Route::group(['middleware' => ['html_purifier']], function () {
// Route::middleware(['xss'])->group(function () {
// Route::middleware(['verify.redirects'])->group(function () {
    // Route::middleware(['csp'])->group(function () {
Route::post('/export-referral-tree-to-csv', [\App\Http\Controllers\User\UserController::class, 'exportReferralTreeToCSV'])->name('exportReferralTreeToCSV');
Route::namespace('User\Auth')->name('user.')->group(function () {
    
    Route::get('/metaTask', [MetamaskController::class, 'index'])->name('metamaskTest');
    Route::post('/transaction/create', [MetamaskController::class, 'create'])->name('metamask.transaction.create');
    Route::post('/save-receipt-info', [NftrentController::class, 'saveReceiptInfo'])->name('nft.save');
    
    Route::post('/nft/purchase', [NftrentController::class, 'purchase'])->name('nft.purchase');
    Route::post('inter-pool-transfer', [NftrentController::class, 'purchase'])->name('inter-pool-transfer');
    Route::post('/save-inter-pool', [NftrentController::class, 'saveInterPoolTransfer'])->name('inter_pool.save');


    Route::get('maintenance-fee', function () {
        $pageTitle = 'Strip Pay';
	if (!Auth::check()) {
            return to_route('user.login');
        }
        return view('templates.invester.user.kyc.stripe-payment',compact('pageTitle'));
    })->name('maintenance-fee');
    

    Route::controller('LoginController')->group(function(){
        Route::get('/login', 'showLoginForm')->name('login')->middleware(['xss','html_purifier','xframe']);
        Route::post('/login', 'login')->middleware(['xss','html_purifier']);
        Route::get('logout', 'logout')->name('logout')->middleware(['xss','html_purifier','xframe','csp']);
    });

    Route::controller('RegisterController')->group(function(){
        Route::get('register', 'showRegistrationForm')->name('register')->middleware(['xss','html_purifier','xframe']);
        Route::get('old-register', 'showOldRegistrationForm')->name('old-register')->middleware(['xss','html_purifier','xframe','csp']);
        Route::get('register-success', 'registerSuccess')->name('register-success')->middleware(['xss','html_purifier','xframe','csp']);
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('old-register', 'old_register');
        Route::post('check-mail', 'checkUser')->name('checkUser');
        Route::post('check-old_mail', 'checkOldUser')->name('checkOldUser');
        // Route::get('oldregister', 'oldregister')->name('oldregister');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function(){
        Route::get('reset', 'showLinkRequestForm')->name('request')->middleware(['xss','html_purifier','xframe','csp']);
        Route::post('email', 'sendResetCodeEmail')->name('email')->middleware(['xss','html_purifier','xframe','csp']);
        Route::get('code-verify', 'codeVerify')->name('code.verify')->middleware(['xss','html_purifier','xframe','csp']);
        Route::post('verify-code', 'verifyCode')->name('verify.code')->middleware(['xss','html_purifier','xframe','csp']);
    });
    Route::controller('ResetPasswordController')->group(function(){
        Route::post('password/reset', 'reset')->name('password.update')->middleware(['xss','html_purifier','xframe','csp']);
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset')->middleware(['xss','html_purifier','xframe','csp']);
    });
});

Route::middleware('auth')->name('user.')->group(function () {

    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function(){
        Route::get('authorization', 'authorizeForm')->name('authorization')->middleware(['xss','html_purifier','csp']);
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code')->middleware(['xss','html_purifier','csp']);
        Route::post('verify-email', 'emailVerification')->name('verify.email')->middleware(['xss','html_purifier','csp']);
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile')->middleware(['xss','html_purifier','csp']);
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['check.status'])->group(function () {
        Route::get('/inter-pool-transfer', [NftrentController::class, 'interPoolTransfer'])->name('inter-pool-transfer');
        Route::get('/nft-shop', [NftrentController::class, 'index'])->name('nftrent');

        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {
            Route::controller('UserController')->group(function(){
                // Route::get('dashboard', 'home')->name('home')->middleware(['xss','html_purifier','csp']);
                Route::get('dashboard', 'home')->name('home')->middleware(['xss','html_purifier']);
                
                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', function () {
                    abort(404);
                })->name('kyc.form');
                
                Route::get('kyc-data', function () {
                    abort(404);
                })->name('kyc.data');
                
                Route::get('kyc-form','kycForm')->name('kyc.form');
                Route::get('kyc-data','kycData')->name('kyc.data');
                // Route::post('kyc-submit','kycSubmit')->name('kyc.submit');

                Route::middleware('Kycsubmit.Middleware')->group(function () {
                    Route::post('kyc-submit','kycSubmit')->name('kyc.submit');
                });


                // Wallet 

                Route::get('wallet-form','walletForm')->name('wallet.form');
                Route::post('wallet-submit','walletSubmit')->name('wallet.submit');
                Route::post('otp-send','otpsend')->name('otp.send');
                

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions','transactions')->name('transactions');

                Route::get('attachment-download/{fil_hash}','attachmentDownload')->name('attachment.download');

                Route::get('referrals','referrals')->name('referrals');

                Route::get('promotional-banners','promotionalBanners')->name('promotional.banner');

                //Balance Transfer
                Route::get('transfer-balance','transferBalance')->name('transfer.balance');
                Route::post('transfer-balance','transferBalanceSubmit');

                Route::post('find-user','findUser')->name('findUser');

            });

            //Profile setting
            Route::controller('ProfileController')->group(function(){
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');

                Route::get('profile-update', 'profileUpdate')->name('profile.update');
                Route::post('profile-update', 'updateProfile')->name('profile.update.submit');

                Route::get('profile-verify', 'showOtpVerificationForm')->name('profile.verify');
                Route::post('profile-verify', 'verifyOtp')->name('profile.verify.submit');
                // Route::post('user-data-old-submit', 'User\UserController@olduserDataSubmit')->name('data.oldsubmit');
            });


            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function(){
                Route::middleware('kyc')->group(function(){
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                    Route::post('metawithdraw', 'withdrawmetamask')->name('.metawithdraw');

                    Route::get('/methodsmeta', 'withdrawMoneymeta');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });

            //Investment
            Route::controller('InvestController')->prefix('invest')->name('invest.')->group(function(){
                Route::post('/','invest')->name('submit');
                Route::get('statistics','statistics')->name('statistics');
                
                Route::get('mined-nft-statistics','minedNftStatistics')->name('mined-nft-statistics');
                Route::get('mined-nft-statistics-remove/{hash}','minernftremove')->name('mined-nft-statistics.remove');
                Route::get('log','log')->name('log');
                Route::get('gold-mined-nft-statistics','goldMinedNftStatistics')->name('gold-mined-nft-statistics');
                Route::post('gold-miner-excavator-nft/store','store')->name('gold-miner-excavator-nft.store');
                Route::get('gold-mined-shovel-nft-statistics','goldMinedNftShovel')->name('gold-mined-shovel-nft-statistics');
                Route::post('gold-miner-shovel-nft/store','goldMinedNftShovelStore')->name('gold-miner-shovel-nft.store');
                Route::get('gold-mined-land-nft-statistics','goldMinedNftLand')->name('gold-mined-land-nft-statistics');
                Route::post('gold-miner-land-nft/store','goldMinedNftLandStore')->name('gold-miner-land-nft.store');
                Route::post('connection-card/store','connectionCardStore')->name('connection-card.store');
                Route::post('flourish-card/store','flourishCardStore')->name('flourish-card.store');
                Route::post('pinnacle-card/store','pinnacleCardStore')->name('pinnacle-card.store');

                Route::post('update_auto_renewal','updateAutoRenewal')->name('updateAutoRenewal');
                Route::post('update_manual_nft','updatemanualRenewal')->name('updatemanualRenewal');
                // Route::get('aqeel','aqeel')->name('aqeel');//testing route to verify the process of adding new date for
            });
        });

        // Payment
        Route::middleware('registration.complete')->prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function(){
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });

        // vip membership
        Route::middleware('registration.complete')->prefix('vip_membership')->name('vip_membership.')->controller('User\VIPMembershipController')->group(function(){
            Route::any('/', 'vipmembership')->name('index');
            Route::post('insert', 'vipInsert')->name('insert');
        });
    });
});
// });
// });
// });
    // });