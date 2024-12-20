<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Lib\HyipLab;
use App\Models\GatewayCurrency;
use App\Models\Invest;
use App\Models\RentNFT;
use App\Models\MinerNft;
use App\Models\RenewNFT;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\GoldMinerExcavatorNFT;
use App\Models\GoldMinerShovelNFT;
use App\Models\GoldMinerLandNFT;
use App\Models\CardPurchase;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use App\Models\GeneralSetting;
use Exception;
use Illuminate\Support\Facades\Log;
class InvestController extends Controller {
   
    public function invest(Request $request) {
        $request->validate(['amount' => 'required|min:0', 'plan_id' => 'required', 'wallet_type' => 'required', ]);
        $user = auth()->user();
        $plan = Plan::where('status', 1)->findOrFail($request->plan_id);
        $amount = $request->amount;
        //Check limit
        if ($plan->fixed_amount > 0) {
            if ($amount != $plan->fixed_amount) {
                $notify[] = ['error', 'Please check the investment limit'];
                return back()->withNotify($notify);
            }
        } else {
            if ($request->amount < $plan->minimum || $request->amount > $plan->maximum) {
                $notify[] = ['error', 'Please check the investment limit'];
                return back()->withNotify($notify);
            }
        }
        $wallet = $request->wallet_type;
        //Direct checkout
        if ($wallet != 'deposit_wallet' && $wallet != 'interest_wallet') {
            $gate = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', 1);
            })->find($request->wallet_type);
            if (!$gate) {
                $notify[] = ['error', 'Invalid gateway'];
                return back()->withNotify($notify);
            }
            if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
                $notify[] = ['error', 'Please follow deposit limit'];
                return back()->withNotify($notify);
            }
            $data = PaymentController::insertDeposit($gate, $request->amount, $plan);
            session()->put('Track', $data->trx);
            return to_route('user.deposit.confirm');
        }

        if($wallet == 'deposit_wallet') {
            if ($request->amount > $user->deposit_ft) {
                $notify[] = ['error', 'Your balance is not sufficient'];
                return back()->withNotify($notify);
            }
            $hyip = new HyipLab($user, $plan);
            $hyip->invest($amount, 'deposit_ft');
        } else {
            if ($request->amount > $user->$wallet) {
                $notify[] = ['error', 'Your balance is not sufficient'];
                return back()->withNotify($notify);
                $hyip = new HyipLab($user, $plan);
                $hyip->invest($amount, $wallet);
            }
        }

        $notify[] = ['success', 'Invested to plan successfully'];
        return back()->withNotify($notify);
    }
    public function statistics() {
        $pageTitle = 'Invest Statistics';
        //$invests = RentNFT::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate(10));
        $invests = RentNFT::where('user_id', auth()->id())->orderBy('id', 'desc')->withCount(['renewal','renewal as renewalsum'=> function($query) {
            $query->select(DB::raw('SUM(amount)'));
        }])->with('lastRenewal')->get();
        // dd($invests->toArray());
        $activePlan = RentNFT::where('user_id', auth()->id())->where('contract_expiry_date', '>', date("Y-m-d"))->sum('rented_nft');
        $expiredPlan = RentNFT::where('user_id', auth()->id())->where('contract_expiry_date', '<=', date("Y-m-d"))->sum('rented_nft');
        $total = RentNFT::where('user_id', auth()->id())->sum('rented_nft');
        //->where('contract_expiry_date', '>', date("Y-m-d"))
        // $investChart = Invest::where('user_id',auth()->id())->with('plan')->groupBy('plan_id')->select('plan_id')->selectRaw("SUM(amount) as investAmount")->orderBy('investAmount', 'desc')->get();
        return view($this->activeTemplate . 'user.invest_statistics', compact('pageTitle', 'invests', 'activePlan','expiredPlan','total'));
    }
    public function minedNftStatistics() {
        $pageTitle = 'Mined NFT Statistics';
        $invests = MinerNft::where('user_id', auth()->id())
        // ->where('contract_expiry_date', '>', date("Y-m-d"))
        ->orderBy('id', 'desc')->paginate(getPaginate(10));
        $activePlan = MinerNft::where('user_id', auth()->id())->where('contract_expiry_date', '>', date("Y-m-d"))->count();
        return view($this->activeTemplate . 'user.mined_nft_statistics', compact('pageTitle', 'invests', 'activePlan'));
    }
    public function minernftremove($id) {
        $user = auth()->user(); 
        $invest = MinerNft::where('user_id', auth()->id())
        ->where('id', $id)
        ->first();
        if($invest){
            if($invest->total_profit > 0){
                $notify[] = ['error', "You can't delete this minerNFT because payment is continues."];
                return back()->withNotify($notify);
            }
            $totalAmount = ($invest->mine_nft * ($invest->mine_quantity_type=='partial' ? $invest->partial_nft_price : $invest->one_nft_price));
            $walletBalance = $user->pool_4;
            User::where('id', $user->id)->update([
                'pool_4' => $user->pool_4+$totalAmount,
            ]);

            $invest->delete();
            $paymentMethod = 'NFTs Cube';
            //Log deposit wallet
            $trx = getTrx();
            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $totalAmount;
            $transaction->charge       = 0;
            $transaction->post_balance = $walletBalance;
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'MinerNFT';
            $transaction->wallet_type  = $paymentMethod;
            $transaction->details      = 'Refund for MinetNFT Remove.';
            $transaction->save();
            $notify[] = ['success', 'minerNFT Removed successfully.'];
            return back()->withNotify($notify);    
        }
        $notify[] = ['error', 'Invalid minerNFT.'];
        return back()->withNotify($notify);
    }
    public function log() {
        $pageTitle = 'Invest Logs';
        $invests = Invest::where('user_id', auth()->id())->orderBy('id', 'desc')->with('plan')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.invests', compact('pageTitle', 'invests'));
    }
    public function goldMinedNftStatistics() {
        $pageTitle = 'Gold Mined NFT Statistics';
        $general = GeneralSetting::first();
        $goldnfts = GoldMinerExcavatorNFT::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate(10));
        return view($this->activeTemplate . 'user.goldmine', compact('pageTitle', 'goldnfts', 'general'));
    }
    public function goldMinedNftShovel() {
        $pageTitle = 'Gold Mined NFT Shovel Statistics';
        $general = GeneralSetting::first();
        $shovel_gold_mine = GoldMinerShovelNFT::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate(10));
        return view($this->activeTemplate . 'user.goldmineshovel', compact('pageTitle', 'shovel_gold_mine', 'general'));
    }
    public function goldMinedNftLand() {
        $pageTitle = 'Gold Mined NFT Land Statistics';
        $general = GeneralSetting::first();
        $land_gold_mines = GoldMinerLandNFT::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate(10));
        return view($this->activeTemplate . 'user.goldmineland', compact('pageTitle', 'land_gold_mines', 'general'));
    }
    public function store(Request $request) {
        $quantity = $request->input('quantity');
        $payment_method = $request->input('payment_method');
        $price_per_gold = 27000;
        $general_setting = GeneralSetting::first();
        $price_ft = $general_setting->price_ft;
        $gold_market_price = $this->fetch_gold_market_price();
        if (!$gold_market_price) {
            $notify[] = ['error', 'Failed to fetch gold market price. Please try again later.'];
            return back()->withNotify($notify);
        }
        $request->validate(['quantity' => 'required|numeric|integer|min:1', 'payment_method' => ['required', 'in:deposit_wallet,reward_cubes,nft_cube', function ($attribute, $value, $fail) use ($quantity, $price_per_gold, $payment_method,$price_ft) {
            $quantity = (int)$quantity;
            $total_cost_gold_buying = ($quantity * ($price_per_gold / $price_ft));
            $user = auth()->user();
            if ($payment_method === 'deposit_wallet' && $user->deposit_ft < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your deposit wallet.");
            } elseif ($payment_method === 'reward_cubes' && $user->interest_wallet < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your reward cube.");
            } elseif ($payment_method === 'nft_cube' && $user->pool_4 < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your NFT cube.");
            }
        }, ], ]);
        // Deduct the gold amount from the appropriate user column.
        $user = auth()->user();
        switch ($payment_method) {
            case 'deposit_wallet':
                $previous_balance = $user->deposit_ft;
                $user->deposit_ft-= ($quantity * ($price_per_gold / $price_ft));
            break;
            case 'reward_cubes':
                $previous_balance = $user->interest_wallet;
                $user->interest_wallet-= $quantity * $price_per_gold;
            break;
            case 'nft_cube':
                $previous_balance = $user->pool_4;
                $user->pool_4-= $quantity * $price_per_gold;
            break;
        }
        //logs_generating
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->amount = $quantity * $price_per_gold;
        $transaction->charge = 0; // Set this value according to your requirement
        $transaction->post_balance = $previous_balance; // Use the previous balance before the deduction
        $transaction->trx_type = '-';
        $transaction->trx = $trx;
        $transaction->remark = 'GoldMinerNft';
        $transaction->wallet_type = $payment_method;
        $transaction->details = showAmount($quantity * $price_per_gold) . ' ' . $general_setting->cur_text . ' deducted from ' . ucwords(str_replace('_', ' ', $payment_method)) . ' for GoldMinerNft';
        $user->save();
        $transaction->save();
        // Store the purchase data in the database.
        GoldMinerExcavatorNFT::create(['user_id' => auth()->id(), 'quantity' => $quantity, 'payment_method' => $payment_method, 'gold_market_price' => $gold_market_price, 'ft_price' => $price_ft, 'gold_amount' => $price_per_gold, 'discount' => 5, 'buying_date' => Carbon::now(), 'maturity_date' => Carbon::now()->addMonths(6) ]);
        $notify[] = ['success', 'Gold purchased successfully'];
        return back()->withNotify($notify);
    }
    public function goldMinedNftShovelStore(Request $request) {
        $quantity = $request->input('quantity');
        $payment_method = $request->input('payment_method');
        $price_per_gold_shovel = 21;
        $general_setting = GeneralSetting::first();
        $price_ft = $general_setting->price_ft;
        $gold_market_price = $this->fetch_gold_market_price();
        if (!$gold_market_price) {
            $notify[] = ['error', 'Failed to fetch gold market price. Please try again later.'];
            return back()->withNotify($notify);
        }
        $request->validate(['quantity' => 'required|numeric|integer|min:1', 'payment_method' => ['required', 'in:deposit_wallet,reward_cubes,nft_cube', function ($attribute, $value, $fail) use ($quantity, $price_per_gold_shovel, $payment_method,$price_ft) {
            $quantity = (int)$quantity;
            $total_cost_gold_buying = ($quantity * ($price_per_gold_shovel / $price_ft));
            $user = auth()->user();
            if ($payment_method === 'deposit_wallet' && $user->deposit_ft < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your deposit wallet.");
            } elseif ($payment_method === 'reward_cubes' && $user->interest_wallet < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your reward cube.");
            } elseif ($payment_method === 'nft_cube' && $user->pool_4 < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your NFT cube.");
            }
        }, ], ]);
        // Deduct the gold amount from the appropriate user column.
        $user = auth()->user();
        switch ($payment_method) {
            case 'deposit_wallet':
                $previous_balance = $user->deposit_ft;
                $user->deposit_ft-= ($quantity * ($price_per_gold_shovel / $price_ft));
            break;
            case 'reward_cubes':
                $previous_balance = $user->interest_wallet;
                $user->interest_wallet-= $quantity * $price_per_gold_shovel;
            break;
            case 'nft_cube':
                $previous_balance = $user->pool_4;
                $user->pool_4-= $quantity * $price_per_gold_shovel;
            break;
        }
        //logs_generating
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->amount = $quantity * $price_per_gold_shovel;
        $transaction->charge = 0; // Set this value according to your requirement
        $transaction->post_balance = $previous_balance; // Use the previous balance before the deduction
        $transaction->trx_type = '-';
        $transaction->trx = $trx;
        $transaction->remark = 'GoldMinerShovelNFT';
        $transaction->wallet_type = $payment_method;
        $transaction->details = showAmount($quantity * $price_per_gold_shovel) . ' ' . $general_setting->cur_text . ' deducted from ' . ucwords(str_replace('_', ' ', $payment_method)) . ' for GoldMinerShovelNFT';
        $user->save();
        $transaction->save();
        // Store the purchase data in the database.
        GoldMinerShovelNFT::create(['user_id' => auth()->id(), 'quantity' => $quantity, 'payment_method' => $payment_method, 'gold_market_price' => $gold_market_price, 'ft_price' => $price_ft, 'gold_amount' => $price_per_gold_shovel, 'discount' => 5, 'buying_date' => Carbon::now(), 'maturity_date' => Carbon::now()->addMonths(6) ]);
        $notify[] = ['success', 'Shovel Gold purchased successfully'];
        return back()->withNotify($notify);
    }
    public function goldMinedNftLandStore(Request $request) {
        $quantity = $request->input('quantity');
        $payment_method = $request->input('payment_method');
        $price_per_gold_land = 2400;
        $general_setting = GeneralSetting::first();
        $price_ft = $general_setting->price_ft;
        $gold_market_price = $this->fetch_gold_market_price();
        if (!$gold_market_price) {
            $notify[] = ['error', 'Failed to fetch gold market price. Please try again later.'];
            return back()->withNotify($notify);
        }
        $request->validate(['quantity' => 'required|numeric|integer|min:1', 'payment_method' => ['required', 'in:deposit_wallet,reward_cubes,nft_cube', function ($attribute, $value, $fail) use ($quantity, $price_per_gold_land, $payment_method,$price_ft) {
            $quantity = (int)$quantity;
            $total_cost_gold_buying = ($quantity * ($price_per_gold_land / $price_ft));
            $user = auth()->user();
            if ($payment_method === 'deposit_wallet' && $user->deposit_ft < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your deposit wallet.");
            } elseif ($payment_method === 'reward_cubes' && $user->interest_wallet < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your reward cube.");
            } elseif ($payment_method === 'nft_cube' && $user->pool_4 < $total_cost_gold_buying) {
                $fail("You don't have enough balance in your NFT cube.");
            }
        }, ], ]);
        // Deduct the gold amount from the appropriate user column.
        $user = auth()->user();
        switch ($payment_method) {
            case 'deposit_wallet':
                $previous_balance = $user->deposit_ft;
                $user->deposit_ft-= ($quantity * ($price_per_gold_land / $price_ft));
            break;
            case 'reward_cubes':
                $previous_balance = $user->interest_wallet;
                $user->interest_wallet-= $quantity * $price_per_gold_land;
            break;
            case 'nft_cube':
                $previous_balance = $user->pool_4;
                $user->pool_4-= $quantity * $price_per_gold_land;
            break;
        }
        //logs_generating
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->amount = $quantity * $price_per_gold_land;
        $transaction->charge = 0; // Set this value according to your requirement
        $transaction->post_balance = $previous_balance; // Use the previous balance before the deduction
        $transaction->trx_type = '-';
        $transaction->trx = $trx;
        $transaction->remark = 'GoldMinerLandNFT';
        $transaction->wallet_type = $payment_method;
        $transaction->details = showAmount($quantity * $price_per_gold_land) . ' ' . $general_setting->cur_text . ' deducted from ' . ucwords(str_replace('_', ' ', $payment_method)) . ' for GoldMinerLandNFT';
        $user->save();
        $transaction->save();
        // Store the purchase data in the database.
        GoldMinerLandNFT::create(['user_id' => auth()->id(), 'quantity' => $quantity, 'payment_method' => $payment_method, 'gold_market_price' => $gold_market_price, 'ft_price' => $price_ft, 'gold_amount' => $price_per_gold_land, 'discount' => 5, 'buying_date' => Carbon::now(), 'maturity_date' => Carbon::now()->addMonths(6) ]);
        $notify[] = ['success', 'Land Gold purchased successfully'];
        return back()->withNotify($notify);
    }
   private function fetch_gold_market_price() {
    $apiKey = env('METALS_API_KEY');
    $apiUrl = env('METALS_API_URL');
    try {
        $response = Http::get($apiUrl, ['api_key' => $apiKey, 'base' => 'USD', 'symbols' => 'XAU', ]);
        $data = $response->json();

        if (isset($data['success']) && !$data['success']) {
            if (isset($data['error']['statusCode']) && $data['error']['statusCode'] == 102) {
                Log::error('Error fetching gold market price: ' . $data['error']['message']);
            } else {
                Log::error('Error fetching gold market price: ' . $data['error']['info']);
            }
            return null;
        }

        if ($response->successful()) {
            if (isset($data['rates']['XAU'])) {
                $gold_rate = $data['rates']['XAU'];
                $gold_rate_in_usd = 1 / $gold_rate;
                return $gold_rate_in_usd;
            } else {
                Log::error('Error fetching gold market price: unexpected response format.');
                return null;
            }
        }
        Log::error('Error fetching gold market price: API call unsuccessful.');
        return null;
    }
    catch(Throwable $e) {
        Log::error('Error fetching gold market price: ' . $e->getMessage());
        return null;
    }
}

    public function connectionCardStore(Request $request) {
        $payment_method = $request->input('payment_method');
        $general_setting = GeneralSetting::first();
        $price_ft = $general_setting->price_ft;
        $card_price = 490;
        $card_name = "Connection Card";
        $request->validate(['payment_method' => ['required', 'in:deposit_wallet,reward_cubes,nft_cube', function ($attribute, $value, $fail) use ($card_price, $payment_method, $price_ft) {
            
            $card_price = (int)$card_price;
            $user = auth()->user();
            if ($payment_method === 'deposit_wallet' && $user->deposit_ft < ($card_price / $price_ft)) {
                $fail("You don't have enough balance in your deposit wallet.");
            } elseif ($payment_method === 'reward_cubes' && $user->interest_wallet < $card_price) {
                $fail("You don't have enough balance in your reward cube.");
            } elseif ($payment_method === 'nft_cube' && $user->pool_4 < $card_price) {
                $fail("You don't have enough balance in your NFT cube.");
            }
        }, ], ]);

        $user = auth()->user();
        switch ($payment_method) {
            case 'deposit_wallet':
                $previous_balance = $user->deposit_ft;
                $user->deposit_ft-= ($card_price / $price_ft);
            break;
            case 'reward_cubes':
                $previous_balance = $user->interest_wallet;
                $user->interest_wallet-= $card_price;
            break;
            case 'nft_cube':
                $previous_balance = $user->pool_4;
                $user->pool_4-= $card_price;
            break;
        }

        //logs_generating
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->amount = $card_price;
        $transaction->charge = 0; // Set this value according to your requirement
        $transaction->post_balance = $previous_balance; // Use the previous balance before the deduction
        $transaction->trx_type = '-';
        $transaction->trx = $trx;
        $transaction->remark = 'Connection Card';
        $transaction->wallet_type = $payment_method;
        $transaction->details = showAmount($card_price) . ' ' . $general_setting->cur_text . ' deducted from ' . ucwords(str_replace('_', ' ', $payment_method)) . ' for Connection Card';
        $user->save();
        $transaction->save();
        // Store the purchase data in the database.
        CardPurchase::create(['user_id' => auth()->id(), 'card_price' => $card_price, 'payment_method' => $payment_method, 'card_name' => $card_name, 'price_ft' => $price_ft, 'buying_date' => Carbon::now() ]);
        $notify[] = ['success', 'Connection Card purchased successfully'];
        return back()->withNotify($notify);

    }
    public function flourishCardStore(Request $request) {
        $payment_method = $request->input('payment_method');
        $general_setting = GeneralSetting::first();
        $price_ft = $general_setting->price_ft;
        $card_price = 2000;
        $card_name = "Flourish Card";
        $request->validate(['payment_method' => ['required', 'in:deposit_wallet,reward_cubes,nft_cube', function ($attribute, $value, $fail) use ($card_price, $payment_method,$price_ft) {
            
            $card_price = (int)$card_price;
            $user = auth()->user();
            if ($payment_method === 'deposit_wallet' && $user->deposit_ft < ($card_price / $price_ft)) {
                $fail("You don't have enough balance in your deposit wallet.");
            } elseif ($payment_method === 'reward_cubes' && $user->interest_wallet < $card_price) {
                $fail("You don't have enough balance in your reward cube.");
            } elseif ($payment_method === 'nft_cube' && $user->pool_4 < $card_price) {
                $fail("You don't have enough balance in your NFT cube.");
            }
        }, ], ]);

        $user = auth()->user();
        switch ($payment_method) {
            case 'deposit_wallet':
                $previous_balance = $user->deposit_ft;
                $user->deposit_ft-= ($card_price / $price_ft);
            break;
            case 'reward_cubes':
                $previous_balance = $user->interest_wallet;
                $user->interest_wallet-= $card_price;
            break;
            case 'nft_cube':
                $previous_balance = $user->pool_4;
                $user->pool_4-= $card_price;
            break;
        }

        //logs_generating
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->amount = $card_price;
        $transaction->charge = 0; // Set this value according to your requirement
        $transaction->post_balance = $previous_balance; // Use the previous balance before the deduction
        $transaction->trx_type = '-';
        $transaction->trx = $trx;
        $transaction->remark = 'Flourish Card';
        $transaction->wallet_type = $payment_method;
        $transaction->details = showAmount($card_price) . ' ' . $general_setting->cur_text . ' deducted from ' . ucwords(str_replace('_', ' ', $payment_method)) . ' for Flourish Card';
        $user->save();
        $transaction->save();
        // Store the purchase data in the database.
        CardPurchase::create(['user_id' => auth()->id(), 'card_price' => $card_price, 'payment_method' => $payment_method, 'card_name' => $card_name, 'price_ft' => $price_ft, 'buying_date' => Carbon::now() ]);
        $notify[] = ['success', 'Flourish Card purchased successfully'];
        return back()->withNotify($notify);

    }
    public function pinnacleCardStore(Request $request) {
        $payment_method = $request->input('payment_method');
        $general_setting = GeneralSetting::first();
        $price_ft = $general_setting->price_ft;
        $card_price = 10000;
        $card_name = "Pinnacle Card";
        $request->validate(['payment_method' => ['required', 'in:deposit_wallet,reward_cubes,nft_cube', function ($attribute, $value, $fail) use ($card_price, $payment_method,$price_ft) {
            
            $card_price = (int)$card_price;
            $user = auth()->user();
            if ($payment_method === 'deposit_wallet' && $user->deposit_ft < ($card_price / $price_ft)) {
                $fail("You don't have enough balance in your deposit wallet.");
            } elseif ($payment_method === 'reward_cubes' && $user->interest_wallet < $card_price) {
                $fail("You don't have enough balance in your reward cube.");
            } elseif ($payment_method === 'nft_cube' && $user->pool_4 < $card_price) {
                $fail("You don't have enough balance in your NFT cube.");
            }
        }, ], ]);

        $user = auth()->user();
        switch ($payment_method) {
            case 'deposit_wallet':
                $previous_balance = $user->deposit_ft;
                $user->deposit_ft-= ($card_price / $price_ft);
            break;
            case 'reward_cubes':
                $previous_balance = $user->interest_wallet;
                $user->interest_wallet-= $card_price;
            break;
            case 'nft_cube':
                $previous_balance = $user->pool_4;
                $user->pool_4-= $card_price;
            break;
        }

        //logs_generating
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->amount = $card_price;
        $transaction->charge = 0; // Set this value according to your requirement
        $transaction->post_balance = $previous_balance; // Use the previous balance before the deduction
        $transaction->trx_type = '-';
        $transaction->trx = $trx;
        $transaction->remark = 'Pinnacle Card';
        $transaction->wallet_type = $payment_method;
        $transaction->details = showAmount($card_price) . ' ' . $general_setting->cur_text . ' deducted from ' . ucwords(str_replace('_', ' ', $payment_method)) . ' for Pinnacle Card';
        $user->save();
        $transaction->save();
        // Store the purchase data in the database.
        CardPurchase::create(['user_id' => auth()->id(), 'card_price' => $card_price, 'payment_method' => $payment_method, 'card_name' => $card_name, 'price_ft' => $price_ft, 'buying_date' => Carbon::now() ]);
        $notify[] = ['success', 'Pinnacle Card purchased successfully'];
        return back()->withNotify($notify);

    }


    public function updateAutoRenewal(Request $request) {
        $user = auth()->user();
        RentNFT::where('user_id', auth()->id())->where('id', $request->input('nftid'))->update(['auto_renewal' => $request->input('auto_renewal')]);
        return true;
    }


    public function updatemanualRenewal(Request $request) {
        
        $vitem = RentNFT::find($request->input('mnftid'));

        $general = gs();

        if($vitem && $vitem->contract_expiry_date < date('Y-m-d', strtotime('+10 days'))){
            $paymentMethod = 'Deposit Wallet';
            $ft_rate = ($general->price_ft)?$general->price_ft:1;
            $nft_amount = ($vitem->rented_nft * 12);  // 12$ for renewal not vip
            $nft_amount = ($nft_amount / $ft_rate);

            $user = User::find($vitem->user_id);

            $rent_id = $vitem->id;

            if($nft_amount <= $user->deposit_ft){
                $walletBalance = $user->deposit_ft;
                $user->deposit_ft= ($user->deposit_ft - $nft_amount);
                $user->save();
                // if($vitem->contract_expiry_date < date('Y-m-d')){
                    $plus90days = date('Y-m-d', strtotime('+90 days'));
                    $currentdays = date('Y-m-d');
                    $vitem->last_renew_date = $currentdays;
                    $vitem->next_profit_date = $currentdays;
                // }else{
                //     $date_c = Carbon::createFromFormat('Y-m-d', $vitem->contract_expiry_date);
                //     $plus90days = $date_c->addDays(89);
                // }
                $vitem->contract_expiry_date = $plus90days;
                $vitem->expired_mail = NULL;
                $vitem->update();
                $trx = getTrx();
                // $transaction               = new Transaction();
                // $transaction->user_id      = $user->id;
                // $transaction->amount       = $nft_amount;
                // $transaction->charge       = 0;
                // $transaction->post_balance = $walletBalance;
                // $transaction->trx_type     = '-';
                // $transaction->trx          = $trx;
                // $transaction->remark       = 'FamilyNFT';
                // $transaction->wallet_type  = $paymentMethod;
                // $transaction->details      = showAmount($nft_amount) . ' ' . $general->cur_text . ' deducted from '.$paymentMethod.' for Renewal FamilyNFT.';
                // $transaction->save();

                
                
                RenewNFT::create([
                    'user_id'     => $user->id,
                    'amount'      => $nft_amount,
                    'wallet_type' => $paymentMethod,
                    'rent_id'     => $rent_id,
                    'renew_date'  => $currentdays
                ]);

                Transaction::create([
                    'user_id'      => $user->id,
                    'amount'       => $nft_amount,
                    'charge'       => 0,
                    'post_balance' => $walletBalance,
                    'trx_type'     => '-',
                    'trx'          => $trx,
                    'remark'       => 'FamilyNFT',
                    'wallet_type'  => $paymentMethod,
                    'details'      => showAmount($nft_amount) . ' ' . $general->cur_text . ' deducted  from '.$paymentMethod.' for manual Renewal FamilyNFT.'
                ]);


                $parentUser = [];
                $reward_amt = $vitem->rented_nft;
                if(isset($user->ref_by) && !empty($user->ref_by)){
                    $parentUser = User::find($user->ref_by);
                }
                
                if($parentUser && !empty($parentUser)){

                    //REWARD $1 to the sponsor
                    User::where('id', $parentUser['id'])->update([
                        'interest_wallet' => ($parentUser['interest_wallet']+($reward_amt)),
                    ]);

                    //Added 1$ to sponser  // paid by company 
                    // $transaction               = new Transaction();
                    // $transaction->user_id      = $parentUser['id'];
                    // $transaction->amount       = $reward_amt;
                    // $transaction->charge       = 0;
                    // $transaction->post_balance = ($parentUser['interest_wallet']);
                    // $transaction->trx_type     = '+';
                    // $transaction->trx          = getTrx();
                    // $transaction->remark       = 'referral renewal bonus';
                    // $transaction->wallet_type  = 'interest_wallet';
                    // $transaction->details      = showAmount($reward_amt) . ' ' . $general->cur_text . ' referral renewal bonus transferred Contract#' . $vitem->id;
                    // $transaction->save();
                    
                    $trx2 = getTrx();
                    Transaction::create([
                        'user_id'      => $parentUser['id'],
                        'amount'       => $reward_amt,
                        'charge'       => 0,
                        'post_balance' => ($parentUser['interest_wallet']),
                        'trx_type'     => '+',
                        'trx'          => $trx2,
                        'remark'       => 'referral renewal bonus',
                        'wallet_type'  => 'interest_wallet',
                        'details'      => showAmount($reward_amt) . ' ' . $general->cur_text . ' referral renewal bonus transferred Manual Renewal FamilyNFT Contract#' . $vitem->id
                    ]);

                }
                return response(['status' => true, 'message'=>"Renewed successfully."]);
            }else{
                return response(['status' => false, 'message'=>"Insufficient Deposit Balance!"]);
            }
        }else{
            return response(['status' => false, 'message'=>"Something went to wrong!"]);
        }
    }


}