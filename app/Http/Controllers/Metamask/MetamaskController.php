<?php

namespace App\Http\Controllers\Metamask;

use App\Http\Controllers\Controller;
use App\Models\Metamask_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Page;


class MetamaskController extends Controller
{
    /**
     * Metamask Payment Page
     *
     * @return void
     */
    public function index()
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }
        $pageTitle = 'Metamask';
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', '/')->first();
        $response['transactions'] = Metamask_transaction::all();
        // dd($response['transactions']);
        return view('Pages.Metamask.metamask', compact('pageTitle', 'sections'))->with($response);
    }
    /**
     * create New Transaction
     *
     * @param  mixed $request
     * @return void
     */
    public function create(Request $request)
    {
        // return  Metamask_transaction::create([
        //     "txHash" => $request->txHash,
        //     "amount" => $request->amount,
        // ]);
    }
}
