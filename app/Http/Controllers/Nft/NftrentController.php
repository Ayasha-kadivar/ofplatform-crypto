<?php

namespace App\Http\Controllers\Nft;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Page;
use App\Models\RentNFT;
use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Carbon\Carbon;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralSetting;
use App\Models\Transaction;

class NftrentController extends Controller
{
        
    public function interPoolTransfer()
    {
        $pageTitle = 'Internal Pool Transfer';
        return view('Pages.internal_pool_transfer', compact('pageTitle'));
    }

    public function saveInterPoolTransfer(Request $request){
        
        $messages = [
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a numeric value.',
            'amount.min' => 'The amount must be at least 1.',
            'ft.required' => 'The FT is required.',
            'ft.numeric' => 'The FT must be a numeric value.',
            'ft.min' => 'The FT must be at least 1.',
            'pool_from.in' => 'The select from value is invalid.',
            'pool_to.in' => 'The select to value is invalid.',
        ];
        
        $rules = [
            'pool_from' => 'required|in:DepositWallet,RewardsCube',
            'ft' => '',
            'amount' => '',
        ];

        if (request('pool_from') === 'DepositWallet') {
            $rules['ft'] .= 'required|numeric|min:1';
        } elseif (request('pool_from') === 'RewardsCube') {
            $rules['amount'] .= 'required|numeric|min:1';
        }

        $request->validate($rules, $messages);

        // $request->validate([
        //     'pool_from' => 'required|in:DepositWallet,RewardsCube',
        //     'pool_to' => 'required|in:RewardsCube,VouchersCube,NftsCube',
        //     'ft' => 'required_if:pool_from,DepositWallet|numeric|min:1',
        //     'amount' => 'required_if:pool_from,RewardsCube|numeric|min:1',
        // ], $messages);
        
        if ($request->pool_from == $request->pool_to) {
            $notify[] = ['error','You can not transfer in the same wallet.'];
            return back()->withNotify($notify);
        }

        $toFields = [
            'RewardsCube' => 'interest_wallet',
            'VouchersCube' => 'pool_2',
            'NftsCube' => 'pool_4',
        ];

        $fieldNames = [
            'DepositWallet' => 'Deposit Wallet',
            'RewardsCube' => 'Rewards Cube',
            'VouchersCube' => 'Vouchers Cube',
            'NftsCube' => 'Nfts Cube',
        ];
        $user = auth()->user();
        $general_setting = GeneralSetting::first();
        if($request->pool_from == 'DepositWallet') {
            $amount = ($request->ft * $general_setting->price_ft);
        } else {
            $amount = $request->amount;
        }

        switch ($request->pool_from) {
            case 'DepositWallet':
                if ($user->deposit_ft < $amount) {
                    $notify[] = ['error','You have not requested amount in deposit wallet.'];
                    return back()->withNotify($notify);
                }
                $fieldFrom = 'deposit_wallet';
                $fieldTo = $toFields[$request->pool_to];

                User::where('id', $user->id)->update([
                    'deposit_ft' => $user->deposit_ft-$request->ft,
                ]);

                break;
            case 'RewardsCube':
                if ($user->interest_wallet<$amount) {
                    $notify[] = ['error','You have not requested amount in Rewards cube.'];
                    return back()->withNotify($notify);
                }
                $fieldFrom = 'interest_wallet';
                $fieldTo = $toFields[$request->pool_to];
                break;
            case 'VouchersCube':
                if ($user->pool_2<$amount) {
                    $notify[] = ['error','You have not requested amount in Vouchers cube.'];
                    return back()->withNotify($notify);
                }
                $fieldFrom = 'pool_2';
                $fieldTo = $toFields[$request->pool_to];
                break;                
            case 'NftsCube':
                if ($user->pool_4<$amount) {
                    $notify[] = ['error','You have not requested amount in NFTs cube.'];
                    return back()->withNotify($notify);
                }
                $fieldFrom = 'pool_4';
                $fieldTo = $toFields[$request->pool_to];
                break;
        }

        // Update user balance from his respective pool
        User::where('id', $user->id)->update([
            $fieldFrom => $user->$fieldFrom - $amount,
            $fieldTo => $user->$fieldTo + $amount,
        ]);

        //Log Transaction in the system
        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->charge       = 0;
        $transaction->post_balance = $user->$fieldFrom-$amount;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx;
        $transaction->remark       = 'Inter Cube Transfer';
        $transaction->wallet_type  = $fieldNames[$request->pool_from];
        $transaction->details      = showAmount($amount) . ' ' . $general_setting->cur_text . ' transferred from '.$fieldNames[$request->pool_from].' to '.$fieldNames[$request->pool_to];
        $transaction->save(); 

        $notify[] = ['success', 'Transfer made successfully.'];
        return back()->withNotify($notify);
    }

    public function index(Request $request)
    {
        //Temp code to check if the user is logged in or not
        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $pageTitle = 'NFT E-Shop';
        
        return view('Pages.NFT.nftshop', compact('pageTitle'));
    }
    public function purchase(Request $request)
{
    $nftName = $request->input('name');
    $package = $request->input('package');
    $price = $package * 24; // 24 dollars per package

    // // Connect to the Binance Smart Chain network
    // $web3 = new Web3(new HttpProvider(new HttpRequestManager("https://restless-wider-sailboat.bsc-testnet.discover.quiknode.pro/909a45380f909abe8c3f55065b06014a0a9e69f0/")));
    // // Create an instance of the contract
    // $contractAddress = "0xea417362aa8add9a38be9b3933f47cf48d45a93e";
    // $abi = '[{"inputs":[{"internalType":"address","name":"_tokenAddress","type":"address"}],
    // "stateMutability":"nonpayable","type":"constructor"},
    // {"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"user","type":"address"},
    // {"indexed":false,"internalType":"uint256","name":"amount","type":"uint256"}],
    // "name":"Collect","type":"event"},
    // {"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},
    // {"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},
    // {"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"collect","outputs":[],"stateMutability":"nonpayable","type":"function"},
    // {"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"renounceOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},
    // {"inputs":[],"name":"tokenAddress","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"tokenBalance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"tokenHolder","type":"address"}],"name":"userBalance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"}]';
    $web3 = new Web3(new HttpProvider(new HttpRequestManager("https://bsc-mainnet.gateway.pokt.network/v1/lb/6136201a7bad1500343e248d")));
    $contractAddress = "0x439ecd2f575f84ce1587e011116b899bd0af1552";
    $abi = '[{"inputs":[],"stateMutability":"nonpayable","type":"constructor"},
    {"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},
    {"anonymous":false,"inputs":[{"indexed":false,"internalType":"uint256","name":"minTokensBeforeSwap","type":"uint256"}],"name":"MinTokensBeforeSwapUpdated","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},
    {"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"uint256","name":"tokensSwapped","type":"uint256"},{"indexed":false,"internalType":"uint256","name":"ethReceived","type":"uint256"},
    {"indexed":false,"internalType":"uint256","name":"tokensIntoLiqudity","type":"uint256"}],"name":"SwapAndLiquify","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"bool","name":"enabled","type":"bool"}],"name":"SwapAndLiquifyEnabledUpdated","type":"event"},
    {"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"inputs":[],"name":"BUSD","outputs":[{"internalType":"contract ERC20Interface","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"accounts","outputs":[{"internalType":"uint256","name":"sellTimestamp","type":"uint256"},{"internalType":"uint256","name":"sellBalance","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"owner","type":"address"},
    {"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"spender","type":"address"},
    {"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"arbitrageWallet","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},
    {"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"busdManager","outputs":[{"internalType":"contract BUSDManager","name":"","type":"address"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"buyFeeBurn","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"buyFeeCharity","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"buyFeeLiquidity","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"buyFeeMarketing","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"charityWallet","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"stateMutability":"view","type":"function"},
    {"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"excludeFromFee","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"getUnlockTime","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"inSwapAndLiquify","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"includeInFee","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"spender","type":"address"},
    {"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"isBlacklisted","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"isBlacklistedEnabled","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"isExcludedFromFee","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"view","type":"function"},
    {"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"isExcludedFromReward","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint256","name":"time","type":"uint256"}],"name":"lock","outputs":[],"stateMutability":"nonpayable","type":"function"},
    {"inputs":[],"name":"marketingWallet","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"penaltyDuration","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"penaltyFeeArbitrage","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"penaltyFeeBurn","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"penaltyFeeCharity","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"penaltyFeeLiquidity","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"penaltyFeeMarketing","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"penaltyMaxSellBalanceWithoutPenalty","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint256","name":"tAmount","type":"uint256"},
    {"internalType":"bool","name":"deductTransferFee","type":"bool"}],"name":"reflectionFromToken","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"renounceOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"sellFeeArbitrage","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"sellFeeBurn","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"sellFeeCharity","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"sellFeeLiquidity","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"sellFeeMarketing","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"newWallet","type":"address"}],"name":"setArbitrageWallet","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"account","type":"address"},{"internalType":"bool","name":"value","type":"bool"}],"name":"setBlacklistAddress","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"newWallet","type":"address"}],"name":"setCharityWallet","outputs":[],"stateMutability":"nonpayable","type":"function"},
    {"inputs":[{"internalType":"bool","name":"enabled","type":"bool"}],"name":"setIsBlacklistedEnabled","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"newWallet","type":"address"}],"name":"setMarketingWallet","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"durationInSeconds","type":"uint256"}],"name":"setPenaltyDuration","outputs":[],"stateMutability":"nonpayable","type":"function"},
    {"inputs":[{"internalType":"uint256","name":"newFee","type":"uint256"}],"name":"setPenaltyFeeArbitrage","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"newFee","type":"uint256"}],"name":"setPenaltyFeeBurn","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"newFee","type":"uint256"}],"name":"setPenaltyFeeCharity","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"newFee","type":"uint256"}],"name":"setPenaltyFeeLiquidity","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"newFee","type":"uint256"}],"name":"setPenaltyFeeMarketing","outputs":[],"stateMutability":"nonpayable","type":"function"},
    {"inputs":[{"internalType":"uint256","name":"newFee","type":"uint256"}],"name":"setPenaltyMaxSellBalanceWithoutPenalty","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"newRouter","type":"address"}],"name":"setRouterAddress","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"bool","name":"enabled","type":"bool"}],"name":"setSwapAndLiquifyEnabled","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"swapAndLiquifyEnabled","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"taxFee","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint256","name":"rAmount","type":"uint256"}],"name":"tokenFromReflection","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"totalFees","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"token","type":"address"},{"internalType":"uint256","name":"tokens","type":"uint256"}],"name":"transferAnyERC20Token","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},
    {"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"uniswapV2Pair","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},
    {"inputs":[],"name":"uniswapV2Router","outputs":[{"internalType":"contract IUniswapV2Router02","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"unlock","outputs":[],"stateMutability":"nonpayable","type":"function"},{"stateMutability":"payable","type":"receive"}]';
    $contract = new Contract($web3->provider, $abi);
    $contract->at($contractAddress);
    // Get the user's account address
    $fromAddress = $web3->eth->accounts[0];
    // dd($fromAddress);
// dd($fromAddress);
    // Get the owner's account address
    $toAddress = "0xEe2a880F58f5BE27dB79390ab7D245F0909081e9";

    // Convert the price to wei
    $weiPrice = $web3->utils->toWei($price, 'ether');

// Get the contract instance using the ABI and contract address
$contract = new Contract($web3->provider, $abi);
$contractInstance = $contract->at($contractAddress);

// Get the current user address
$userAddress = $web3->eth->accounts[0];

// Prepare the transaction to call the 'purchase' function of the contract
$transaction = $contractInstance->methods->purchase($nftName, $weiPrice)->send([
'from' => $userAddress,
'gas' => '1000000'
]);

// Check if the transaction was successful
if ($transaction->hasError()) {
return redirect()->back()->with('error', 'Transaction failed. Please check MetaMask and try again.');
} else {
return redirect()->back()->with('success', 'Transaction successful. Your transaction hash is: '.$transaction->getTransactionHash());
}

}
public function saveReceiptInfo(Request $request)
{
    $date = Carbon::now()->toDateTimeString();
    $nextProfitDate = Carbon::now()->addDays(9)->format('Y-m-d');
    $contractExpiryDate = Carbon::now()->addDays(89)->format('Y-m-d');
    $receipt = new RentNFT;
    $receipt->user_meta_mask_info = $request->user_meta_mask_info;
    $receipt->one_nft_price = $request->one_nft_price;
    $receipt->ft_price = $request->ft_price;
    $receipt->rented_nft = $request->rented_nft;
    $receipt->buying_date = $date;
    $receipt->next_profit_date = $nextProfitDate;
    $receipt->contract_expiry_date = $contractExpiryDate;
    $receipt->user_id = $request->user_id;
    $receipt->payment_method = "metamask";
    $receipt->save();

    // Sponsers $1 bonus code starts from here
    $userId = Auth::id();
    $user = User::find($userId);

    // $request->amount is equal to $request->rented_nft
    if($user->ref_by>0){
        $sponsor = User::find($user->ref_by);
        $postBalance = $sponsor->interest_wallet;
        $pool2PostBalance = $sponsor->pool_2;

        //add $1 in sponsor account (Currently it will be deposited from CryptoFamily)
        User::where('id', $user->ref_by)->update([
            'interest_wallet' => $sponsor->interest_wallet+$request->rented_nft,
            'pool_2' => $sponsor->pool_2-$request->rented_nft
        ]);
        $general            = GeneralSetting::first();

        //Deduct from pool2 of the sponsor
        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->ref_by;
        $transaction->amount       = $request->rented_nft;
        $transaction->charge       = 0;
        $transaction->post_balance = $pool2PostBalance;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx;
        $transaction->remark       = 'bonus';
        $transaction->wallet_type  = 'Vouchers Cube';
        $transaction->details      = showAmount($request->rented_nft) . ' ' . $general->cur_text . ' deducted (from Vouchers Cube) as Referred user bought nft. Username: ' . $user->username;
        $transaction->save();     

        //Log interest wallet
        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->ref_by;
        $transaction->amount       = $request->rented_nft;
        $transaction->charge       = 0;
        $transaction->post_balance = $postBalance;
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx;
        $transaction->remark       = 'interest';
        $transaction->wallet_type  = 'Reward Cube';
        $transaction->details      = showAmount($request->rented_nft) . ' ' . $general->cur_text . ' transferred (from Vouchers Cube) as Referred user bought nft. Username: ' . $user->username;
        $transaction->save();        

        // $notify[] = ['success', 'NFT rented from Deposit Wallet'];
        // return redirect()->back()->withNotify($notify);
    }

    // return response()->json([
    //     'message' => 'Receipt information saved successfully!'
    // ]);
    return redirect()->route('plan');
}

}