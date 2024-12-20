@extends($activeTemplate.'layouts.master')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/web3@1.3.0/dist/web3.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<style>
.hitesh-hide-button {
    pointer-events: none;
    opacity: 0;
    display:none;
}
</style>
<div>
    <div class="dashboard-inner container pt-120 pb-120">
        <div class="mb-4">
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h3 class="mb-2 new--color--theme">@lang('NFT E-Shop')</h3>
                </div>
            </div>

            @php
            $currentDay = date('j');
            $price_ft = App\Models\GeneralSetting::first();
            @endphp

            <div class="row gy-4">
                <div class="col-sm-4">
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body">
                        <img src="{{asset('assets/images/family-nft.jpeg')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">FamilyNFT</h5>
                            <p class="card-text">
                                Starts From
                                <span id="start-date"></span>
                                till
                                <span id="end-date"></span>
                            </p>
                            <p class="card-text">
                                Profit will start from <span id="profit-date"></span>
                            </p>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Price</span>
                                </div>
                                <div class="col-sm-6">
                                    $<span id="packagePrice">24</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>1 FT</span>
                                </div>
                                @php
                                $price_ft = App\Models\GeneralSetting::first();
                                @endphp
                                <div class="col-sm-6">
                                    $<span id="priceft">{{$price_ft->price_ft}}</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3" style="display: none;" id="total_in_ft">
                                <div class="col-sm-6">
                                    <span>Price for NFT in FT</span>
                                </div>
                                <div class="col-sm-6">
                                    <span id="newftPrice">-</span> FT
                                </div>
                            </div>
                            <div class="row mt-3 mb-3" style="display: none;" id="total_in_usd">
                                <div class="col-sm-6">
                                    <span>Price for NFT in USD</span>
                                </div>
                                <div class="col-sm-6">
                                    <span id="totalPrice">-</span> $
                                </div>
                            </div>


                            <div class="mt-2">
                                <form action="{{ route('rent.deposit')}}" method="POST">
                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-check-input" required type="checkbox" value="" id="serviceCheckbox">
                                            <label class="form-check-label" for="serviceCheckbox">
                                                <a href="#serviceModal" data-bs-toggle="modal">FamilyNFT/Game Rules</a>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <select class="form-select mt-2" name="rentOption" id="rentOption">
                                                <option value="">Select Payment Method</option>
                                                {{-- <option value="Metamask">Pay with Metamask</option> --}}
                                                <option value="DepositWallet">Pay with Deposit Wallet</option>

                                                @php
                                                $currentDay = date('j');
                                                $currentMonth = date('n');
                                                $currentYear = date('Y');
                                                $user = auth()->user();
                                                @endphp
                                                @if($currentDay == 1 || $currentDay == 15)
                                                {{-- <option value="RewardsCube">Pay with Rewards Cube</option> --}}
                                                @endif
                                                {{-- <option value="RewardsCube">Pay with Rewards Cube</option> --}}
                                                {{-- <option value="NftsCube">Pay with NFTs Cube</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="mt-2">
                                        <span>Rent Amount</span>
                                        <input type="number" class="form-control" id="nftsRented" name="amount"
                                            placeholder="Amount" value="" step="any" min="1" required>
                                        <div id="nftsRentedError" style="color: red; display: none;">Please enter only
                                            whole numbers</div>
                                    </div>

                                    <div class="mt-2" id="dataA" style="display:none;">
                                        <button class="cmn--btn plan-btn mt-2" style="display: block;"
                                            id="conntWallet">Approve</button>
                                        <button class="btn btn--base btn--smd mt-2"
                                            style="display: none; width:100% !important;" id="rent">Rent</button>
                                    </div>
                                    <div class="mt-2" id="dataB" style="display:none;">
                                        <button type="submit" class="cmn--btn plan-btn mt-2"
                                            style="display: block;">Rent</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- <div class="coming-soon-overlay"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                            <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                        </div> -->
                    </div>
                </div>
                {{-- <div class="col-sm-4">
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body" style="height:668px">
                        <img src="{{asset('assets/images/validator-nft.jpeg')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">ValidatorNFT</h5>
                            <p class="card-text">
                                Starts From
                                <span id="start-date"></span>
                                till
                                <span id="end-date"></span>
                            </p>
                            <p class="card-text">
                                Profit will start from <span id="profit-date"></span>
                            </p>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Price</span>
                                </div>
                                <div class="col-sm-6">
                                    $<span id="packagePrice">10</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>1 FT</span>
                                </div>
                                <div class="col-sm-6">
                                    $<span id="priceft">{{$price_ft->price_ft}}</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3" style="display: none;" id="total_in_ft">
                                <div class="col-sm-6">
                                    <span>Price for NFT in FT</span>
                                </div>
                                <div class="col-sm-6">
                                    <span id="newftPrice">-</span> FT
                                </div>
                            </div>
                            <div class="row mt-3 mb-3" style="display: none;" id="total_in_usd">
                                <div class="col-sm-6">
                                    <span>Price for NFT in USD</span>
                                </div>
                                <div class="col-sm-6">
                                    <span id="totalPrice">-</span> $
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <select class="form-select mt-2" id="rentOption2">
                                        <option value="">Select Payment Method</option>
                                        <option value="Metamask">Pay with Metamask</option>
                                        <option value="DepositWallet">Pay with Deposit Wallet</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="coming-soon-overlay"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                            <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                        </div>
                    </div>
                </div> --}}
                <div class="col-sm-4" style="display: none;">
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body" style="height: 668px">
                        <img src="{{asset('assets/images/nft3.jpeg')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">MinerNFT</h5>
                            <p class="card-text">
                                Starts From
                                <span id="start-date"></span>
                                till
                                <span id="end-date"></span>
                            </p>
                            <p class="card-text">
                                Profit will start from <span id="profit-date"></span>
                            </p>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Price</span>
                                </div>
                                <div class="col-sm-6">
                                    $<span id="packagePrice">24</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>1 FT</span>
                                </div>
                                <div class="col-sm-6">
                                    $<span>1</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3" style="display: none;" id="total_in_ft">
                                <div class="col-sm-6">
                                    <span>Price for NFT in FT</span>
                                </div>
                                <div class="col-sm-6">
                                    <span id="newftPrice">-</span> FT
                                </div>
                            </div>
                            <div class="row mt-3 mb-3" style="display: none;" id="total_in_usd">
                                <div class="col-sm-6">
                                    <span>Price for NFT in USD</span>
                                </div>
                                <div class="col-sm-6">
                                    <span id="totalPrice">-</span> $
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <select class="form-select mt-2" id="rentOption3">
                                        <option value="">Select Payment Method</option>
                                        <!-- <option value="Metamask">Pay with Metamask</option> -->
                                        <option value="DepositWallet">Pay with Deposit Wallet</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" style="display: none;">
                    <form action="{{ route('miner.nft')}}" method="POST">
                        @csrf
                        <div class="card rounded-3 shadow p-2 mb-5 bg-body" style="height:668px">
                            <img src="{{asset('assets/images/new_MinerNFT.JPG')}}" class="card-img-top p-2" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Miner NFT</h5>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <span>1 Whole FT</span>
                                    </div>
                                    <div class="col-sm-6">
                                        $<span>2000</span>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <span>Fractions </span>
                                    </div>
                                    <div class="col-sm-6">
                                        $<span>20 (1 Fractions)</span>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="serviceCheckbox"
                                        required>
                                    <label class="form-check-label" for="serviceCheckbox">
                                        <a href="#MinerNft" data-bs-toggle="modal">MinerNft Agreement/Game Rules</a>
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <select class="form-select mt-2" id="mineOption" name="mineOption">
                                            <option value="">Select Quantity</option>
                                            <option value="whole">Buy Whole NFT</option>
                                            <option value="partial">Buy Fractions</option>
                                        </select>
                                        @error('mineOption')
                                        <div class="text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="mt-2">

                                    <div class="mt-2" style="display: none;" id="mineRentAmt">
                                        <span id="qtyLabel">Fractions</span>
                                        <input type="number" min="1" class="form-control" id="nftsRented" required
                                            name="amount" placeholder="Amount" value="" step="any">
                                        @error('amount')
                                        <div class="text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mt-2" style="display: none;" id="minePaymentMethodDiv">
                                        <select class="form-select mt-2" id="minePaymentMethod"
                                            name="minePaymentMethod">
                                            <option value="">Select Payment Method</option>
                                            {{-- <option value="metamask" style="display: none;">Pay with Metamask</option> --}}
                                            <option value="deposit">Pay with Deposit Wallet</option>

                                            @php
                                                $currentDay = date('j');
                                                $currentMonth = date('n');
                                                $currentYear = date('Y');
                                                $user = auth()->user();
                                            @endphp
                                            @if($currentDay == 1 || $currentDay == 15)
                                            {{-- <option value="RewardsCube">Pay with Rewards Cube</option> --}}
                                            <option value="NftsCube">Pay with NFTs Cube</option>
                                            @endif
                                            {{-- <option value="RewardsCube">Pay with Rewards Cube</option> --}}
                                            {{-- @if ($currentDay == 1 || $currentDay == 15 )
                                            <option value="RewardsCube">Pay with Rewards Cube</option>
                                            @endif
                                            <option value="NftsCube">Pay with NFTs Cube</option> --}}
                                        </select>
                                    </div>

                                </div>
                                <div class="row">
                                        <button type="submit" class="cmn--btn plan-btn btn mt-2" style="display: none;" id="btnDepositWallet">Rent NFT</button>
                                </div>
                            </div>
                            <!-- <div class="coming-soon-overlay"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                            <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                            </div> -->
                        </div>
                    </form>
                </div>
                {{-- <div class="col-sm-4">
                    <form action="{{ route('user.invest.gold-miner-excavator-nft.store')}}" method="POST">
                @csrf
                <div class="card rounded-3 shadow p-2 mb-5 bg-body" style="height:668px">
                    <img src="{{asset('assets/images/nft3.jpeg')}}" class="card-img-top p-2" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">GoldMinerExcavatorNFT</h5>
                        <div class="row mt-3 mb-3">
                            <div class="col-sm-6">
                                <span>1 NFT</span>
                            </div>
                            <div class="col-sm-6">
                                $<span>2500 </span>
                            </div>
                        </div>


                        <div class="mt-2">
                            <div class="mt-2" style="display" id="minePaymentMethodDiv">
                                <select class="form-select mt-2" id="goldMinePaymentMethod" name="payment_method">
                                    <option value="">Select Payment Method</option>
                                    <option value="deposit_wallet">Pay with Deposit Wallet</option>
                                    <option value="reward_cubes">Pay with Rewards Cube</option>
                                    <option value="nft_cube">Pay with NFTs Cube</option>
                                </select>
                            </div>
                            <div class="mt-2" style="display" id="mineRentAmt">
                                <span id="qtyLabel">Amount</span>
                                <input type="number" min="1" class="form-control" required name="quantity"
                                    placeholder="Amount">
                                @error('amount')
                                <div class="text-red-500">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="submit" class="cmn--btn plan-btn btn mt-2">

                        </div>
                    </div>
                </div>
                </form>
            </div> --}}
            <div id="error"></div>


        </div>
        <div class="row">
            <h3 class="mb-2">@lang('GoldMinerNFTs')</h3>
            <div class="col-sm-4">
                <form action="{{ route('user.invest.gold-miner-excavator-nft.store')}}" method="POST">
                    @csrf
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body" style="height:668px">
                        <img src="{{asset('assets/images/gold_esc.jpg')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">GoldMinerExcavatorNFT</h5>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>1 NFT</span>
                                </div>
                                <div class="col-sm-6">
                                    $<span>27000 </span>
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" required value="" id="serviceCheckbox">
                                <label class="form-check-label" for="serviceCheckbox">
                                    <a href="#GoldMinerNft" data-bs-toggle="modal">GoldMinerNFT/Game Rules</a>
                                </label>
                            </div>


                            <div class="mt-2">
                                <div class="mt-2" style="display" id="minePaymentMethodDiv">
                                    <select class="form-select mt-2" id="goldMinePaymentMethod" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="deposit_wallet">Pay with Deposit Wallet</option>
                                        {{-- <option value="reward_cubes">Pay with Rewards Cube</option>
                                        @if ($currentDay == 1 || $currentDay == 15 )
                                        <option value="reward_cubes">Pay with Rewards Cube</option>
                                        @endif --}}
                                    </select>
                                </div>
                                <div class="mt-2" style="display" id="mineRentAmt">
                                    <span id="qtyLabel">Amount</span>
                                    <input type="number" min="1" class="form-control" required name="quantity"
                                        placeholder="Amount">
                                    @error('amount')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button disabled type="submit" class="cmn--btn plan-btn btn mt-2">Rent</button>

                            </div>
                        </div>
                        <div class="coming-soon-overlay"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                        <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-sm-4">
                <form action="{{ route('user.invest.gold-miner-shovel-nft.store')}}" method="POST">
                    @csrf
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body" style="height:668px">
                        <img src="{{asset('assets/images/gold_land.jpg')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">GoldMinerShovelNFT</h5>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>1 NFT</span>
                                </div>
                                <div class="col-sm-6">
                                    $<span>21 </span>
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" required value="" id="serviceCheckbox">
                                <label class="form-check-label" required for="serviceCheckbox">
                                    <a href="#ShovelMinerNft" data-bs-toggle="modal">GoldMinerShovelNFT/Game Rules</a>
                                </label>
                            </div>


                            <div class="mt-2">
                                <div class="mt-2" style="display" id="minePaymentMethodDiv">
                                    <select class="form-select mt-2" id="goldMinePaymentMethod" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="deposit_wallet">Pay with Deposit Wallet</option>
                                        {{-- <option value="reward_cubes">Pay with Rewards Cube</option>
                                        @if ($currentDay == 1 || $currentDay == 15 )
                                        <option value="reward_cubes">Pay with Rewards Cube</option>
                                        @endif --}}
                                    </select>
                                </div>
                                <div class="mt-2" style="display" id="mineRentAmt">
                                    <span id="qtyLabel">Amount</span>
                                    <input type="number" min="1" class="form-control" required name="quantity"
                                        placeholder="Amount">
                                    @error('amount')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button disabled type="submit" class="cmn--btn plan-btn btn mt-2">Rent</button>

                            </div>
                        </div>
                        <div class="coming-soon-overlay"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                        <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-4">
                <form action="{{ route('user.invest.gold-miner-land-nft.store')}}" method="POST">
                    @csrf
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body" style="height:668px">
                        <img src="{{asset('assets/images/new_GoldMiningLandNFT.JPG')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">GoldMinerLandNFT</h5>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>1 NFT</span>
                                </div>
                                <div class="col-sm-6">
                                    $<span>2400 </span>
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" required type="checkbox" value="" id="serviceCheckbox">
                                <label class="form-check-label" for="serviceCheckbox">
                                    <a href="#LandMinerNft" data-bs-toggle="modal">GoldMinerLandNFT/Game Rules</a>
                                </label>
                            </div>


                            <div class="mt-2">
                                <div class="mt-2" style="display" id="minePaymentMethodDiv">
                                    <select class="form-select mt-2" id="goldMinePaymentMethod" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="deposit_wallet">Pay with Deposit Wallet</option>
                                        {{-- <option value="reward_cubes">Pay with Rewards Cube</option>
                                        @if ($currentDay == 1 || $currentDay == 15 )
                                        <option value="reward_cubes">Pay with Rewards Cube</option>
                                        @endif --}}
                                    </select>
                                </div>
                                <div class="mt-2" style="display" id="mineRentAmt">
                                    <span id="qtyLabel">Amount</span>
                                    <input type="number" min="1" class="form-control" required name="quantity"
                                        placeholder="Amount">
                                    @error('amount')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button disabled type="submit" class="cmn--btn plan-btn btn mt-2">Rent</button>

                            </div>
                        </div>
                        <div class="coming-soon-overlay"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                        <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- <h3 class="mb-2">@lang('Cards')</h3> -->
            <div class="col-sm-4">
                <!-- <form action="{{ route('user.invest.connection-card.store')}}" method="POST">
                    @csrf
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body">
                        <img src="{{asset('assets/images/image 204.png')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Connection Card</h5>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-12">
                                    <span>$490</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-12">
                                    <ul>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Own swift</span> account number
                                        </li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Offshore</span> bank account
                                        </li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Worldwide acceptance</span> and
                                            use ( also in countries where crypto <span class="para-color-2">is banned</span> )</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Rechargeable </span> with
                                            Metamask</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2" style=" text-decoration: line-through; color: red;">5% Loading fees</span> 4% Loading fees</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">$80,000,-</span> Monthly
                                            loading cap</li>
                                        <li class=" my-1 fs-weight">Daily withdraw limit =<span class="para-color-2">$5,000</span></li>
                                    </ul>
                                </div>
                            </div>

                            {{-- <div class="form-check">
                                <input class="form-check-input" type="checkbox" required value="" id="serviceCheckbox">
                                <label class="form-check-label" for="serviceCheckbox">
                                    <a href="#GoldMinerNft" data-bs-toggle="modal">GoldMinerNFT</a>
                                </label>
                            </div> --}}


                            <div class="mt-2">
                                <div class="mt-2" style="display" id="minePaymentMethodDiv">
                                    <select class="form-select mt-2" id="goldMinePaymentMethod" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="deposit_wallet">Pay with Deposit Wallet</option>
                                        {{-- <option value="reward_cubes">Pay with Rewards Cube</option> --}}
                                    </select>
                                </div>
                                {{-- <div class="mt-2" style="display" id="mineRentAmt">
                                    <span id="qtyLabel">Amount</span>
                                    <input type="number" min="1" class="form-control" required name="quantity"
                                        placeholder="Amount">
                                    @error('amount')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                <button disabled type="submit" class="cmn--btn plan-btn btn mt-2">Buy</button>

                            </div>
                        </div>
                        <div class="coming-soon-overlay"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                        <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                        </div>
                    </div>
                </form> -->
            </div>
            <div class="col-sm-4">
                <!-- <form action="{{ route('user.invest.flourish-card.store')}}" method="POST">
                    @csrf
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body" >
                        <img src="{{asset('assets/images/image 205.png')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Flourish Card</h5>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-12">
                                    <span>$2,000</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-12">
                                    <ul>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Own swift</span> account number
                                        </li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Offshore</span> bank account
                                        </li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Worldwide acceptance</span> and
                                            use ( also in countries where crypto <span class="para-color-2">is banned</span> )</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Rechargeable </span> with
                                            Metamask</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2" style=" text-decoration: line-through; color: red;">4.5% Loading fees</span> 4% Loading fees</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">$200,000,-</span> Monthly
                                            loading cap</li>
                                        <li class=" my-1 fs-weight">Daily withdraw limit =<span class="para-color-2"> No
                                                limit</span></li>
                                    </ul>
                                </div>
                            </div>

                            {{-- <div class="form-check">
                                <input class="form-check-input" type="checkbox" required value="" id="serviceCheckbox">
                                <label class="form-check-label" required for="serviceCheckbox">
                                    <a href="#ShovelMinerNft" data-bs-toggle="modal">GoldMinerShovelNFT</a>
                                </label>
                            </div> --}}


                            <div class="mt-2">
                                <div class="mt-2" style="display" id="minePaymentMethodDiv">
                                    <select class="form-select mt-2" id="goldMinePaymentMethod" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="deposit_wallet">Pay with Deposit Wallet</option>
                                        {{-- <option value="reward_cubes">Pay with Rewards Cube</option> --}}
                                    </select>
                                </div>
                                {{-- <div class="mt-2" style="display" id="mineRentAmt">
                                    <span id="qtyLabel">Amount</span>
                                    <input type="number" min="1" class="form-control" required name="quantity"
                                        placeholder="Amount">
                                    @error('amount')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                <button disabled type="submit" class="cmn--btn plan-btn btn mt-2">Buy</button>

                            </div>
                        </div>
                        <div class="coming-soon-overlay"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                        <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                        </div>
                    </div>
                </form> -->
            </div>
            <div class="col-sm-4">
                <!-- <form action="{{ route('user.invest.pinnacle-card.store')}}" method="POST">
                    @csrf
                    <div class="card rounded-3 shadow p-2 mb-5 bg-body" >
                        <img src="{{asset('assets/images/image 203.png')}}" class="card-img-top p-2" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Pinnacle Card</h5>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-12">
                                    <span>$10,000</span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-12">
                                    <ul>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Own swift</span> account number
                                        </li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Offshore</span> bank account
                                        </li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Worldwide acceptance</span> and
                                            use ( also in countries where crypto <span class="para-color-2">is banned</span> )</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">Rechargeable </span> with
                                            Metamask</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">4% Loading fees</span> + 1% Cashback</li>
                                        <li class=" my-1 fs-weight"><span class="para-color-2">No limit</span> Monthly loading
                                            cap</li>
                                        <li class=" my-1 fs-weight">Daily withdraw limit =<span class="para-color-2"> No
                                                Limits-</span></li>
                                    </ul>
                                </div>
                            </div>

                            {{-- <div class="form-check">
                                <input class="form-check-input" required type="checkbox" value="" id="serviceCheckbox">
                                <label class="form-check-label" for="serviceCheckbox">
                                    <a href="#LandMinerNft" data-bs-toggle="modal">GoldMinerShovelNFT</a>
                                </label>
                            </div> --}}


                            <div class="mt-2">
                                <div class="mt-2" style="display" id="minePaymentMethodDiv">
                                    <select class="form-select mt-2" id="goldMinePaymentMethod" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="deposit_wallet">Pay with Deposit Wallet</option>
                                        {{-- <option value="reward_cubes">Pay with Rewards Cube</option> --}}
                                    </select>
                                </div>
                                {{-- <div class="mt-2" style="display" id="mineRentAmt">
                                    <span id="qtyLabel">Amount</span>
                                    <input type="number" min="1" class="form-control" required name="quantity"
                                        placeholder="Amount">
                                    @error('amount')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                <button disabled type="submit" class="cmn--btn plan-btn btn mt-2">Buy</button>

                            </div>
                        </div>
                        <div class="coming-soon-overlay"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                        <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                        </div>
                    </div>
                </form> -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceModalLabel">Service Agreement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('Pages.NFT.family-nft')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="MinerNft" tabindex="-1" aria-labelledby="MinerNftLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MinerNftLabel">MinerNft Agreement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('Pages.NFT.miner-nft')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="GoldMinerNft" tabindex="-1" aria-labelledby="GoldMinerNftLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MinerNftLabel">GoldMinerExcavatorNFT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('Pages.NFT.gold-miner-nft')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ShovelMinerNft" tabindex="-1" aria-labelledby="ShovelMinerNftLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MinerNftLabel">GoldMinerShovelNFT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('Pages.NFT.gold-miner-nft')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="LandMinerNft" tabindex="-1" aria-labelledby="LandMinerNftLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MinerNftLabel">GoldMinerLandNFT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('Pages.NFT.gold-miner-nft')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="{{asset('web3.min.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>

<script>
document.getElementById("nftsRented").addEventListener("input", function() {
    let nftsRented = this.value;
    let ftPrice = document.getElementById("priceft").innerHTML;
    console.log(ftPrice);
    let packagePrice = document.getElementById("packagePrice").innerHTML;
    let totalPrice = nftsRented * packagePrice;
    document.getElementById("totalPrice").innerHTML = totalPrice;
    let newftPrice = totalPrice / ftPrice;
    newftPrice = newftPrice.toFixed(2);
    document.getElementById("newftPrice").innerHTML = newftPrice;

    // Show the two div elements when the input field is not empty
    if (nftsRented !== '') {
        document.getElementById("total_in_ft").style.display = 'flex';
        document.getElementById("total_in_usd").style.display = 'flex';
    } else {
        document.getElementById("total_in_ft").style.display = 'none';
        document.getElementById("total_in_usd").style.display = 'none';
    }
});
</script>

<script>
(async function() {
    //         if (typeof window.ethereum === 'undefined') {
    //     alert("Please install MetaMask extension to use this feature.");
    //     document.getElementById("conntWallet").disabled = true;
    //     document.getElementById("rent").disabled = true;
    //     return;
    // }
    const _web3 = new Web3(window.ethereum);

    const abi = [{
        "inputs": [{
            "internalType": "address",
            "name": "account",
            "type": "address"
        }, {
            "internalType": "address",
            "name": "minter_",
            "type": "address"
        }, {
            "internalType": "uint256",
            "name": "mintingAllowedAfter_",
            "type": "uint256"
        }],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "constructor"
    }, {
        "anonymous": false,
        "inputs": [{
            "indexed": true,
            "internalType": "address",
            "name": "owner",
            "type": "address"
        }, {
            "indexed": true,
            "internalType": "address",
            "name": "spender",
            "type": "address"
        }, {
            "indexed": false,
            "internalType": "uint256",
            "name": "amount",
            "type": "uint256"
        }],
        "name": "Approval",
        "type": "event"
    }, {
        "anonymous": false,
        "inputs": [{
            "indexed": true,
            "internalType": "address",
            "name": "delegator",
            "type": "address"
        }, {
            "indexed": true,
            "internalType": "address",
            "name": "fromDelegate",
            "type": "address"
        }, {
            "indexed": true,
            "internalType": "address",
            "name": "toDelegate",
            "type": "address"
        }],
        "name": "DelegateChanged",
        "type": "event"
    }, {
        "anonymous": false,
        "inputs": [{
            "indexed": true,
            "internalType": "address",
            "name": "delegate",
            "type": "address"
        }, {
            "indexed": false,
            "internalType": "uint256",
            "name": "previousBalance",
            "type": "uint256"
        }, {
            "indexed": false,
            "internalType": "uint256",
            "name": "newBalance",
            "type": "uint256"
        }],
        "name": "DelegateVotesChanged",
        "type": "event"
    }, {
        "anonymous": false,
        "inputs": [{
            "indexed": false,
            "internalType": "address",
            "name": "minter",
            "type": "address"
        }, {
            "indexed": false,
            "internalType": "address",
            "name": "newMinter",
            "type": "address"
        }],
        "name": "MinterChanged",
        "type": "event"
    }, {
        "anonymous": false,
        "inputs": [{
            "indexed": true,
            "internalType": "address",
            "name": "from",
            "type": "address"
        }, {
            "indexed": true,
            "internalType": "address",
            "name": "to",
            "type": "address"
        }, {
            "indexed": false,
            "internalType": "uint256",
            "name": "amount",
            "type": "uint256"
        }],
        "name": "Transfer",
        "type": "event"
    }, {
        "constant": true,
        "inputs": [],
        "name": "DELEGATION_TYPEHASH",
        "outputs": [{
            "internalType": "bytes32",
            "name": "",
            "type": "bytes32"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "DOMAIN_TYPEHASH",
        "outputs": [{
            "internalType": "bytes32",
            "name": "",
            "type": "bytes32"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "PERMIT_TYPEHASH",
        "outputs": [{
            "internalType": "bytes32",
            "name": "",
            "type": "bytes32"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [{
            "internalType": "address",
            "name": "account",
            "type": "address"
        }, {
            "internalType": "address",
            "name": "spender",
            "type": "address"
        }],
        "name": "allowance",
        "outputs": [{
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": false,
        "inputs": [{
            "internalType": "address",
            "name": "spender",
            "type": "address"
        }, {
            "internalType": "uint256",
            "name": "rawAmount",
            "type": "uint256"
        }],
        "name": "approve",
        "outputs": [{
            "internalType": "bool",
            "name": "",
            "type": "bool"
        }],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [{
            "internalType": "address",
            "name": "account",
            "type": "address"
        }],
        "name": "balanceOf",
        "outputs": [{
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [{
            "internalType": "address",
            "name": "",
            "type": "address"
        }, {
            "internalType": "uint32",
            "name": "",
            "type": "uint32"
        }],
        "name": "checkpoints",
        "outputs": [{
            "internalType": "uint32",
            "name": "fromBlock",
            "type": "uint32"
        }, {
            "internalType": "uint96",
            "name": "votes",
            "type": "uint96"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "decimals",
        "outputs": [{
            "internalType": "uint8",
            "name": "",
            "type": "uint8"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": false,
        "inputs": [{
            "internalType": "address",
            "name": "delegatee",
            "type": "address"
        }],
        "name": "delegate",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "constant": false,
        "inputs": [{
            "internalType": "address",
            "name": "delegatee",
            "type": "address"
        }, {
            "internalType": "uint256",
            "name": "nonce",
            "type": "uint256"
        }, {
            "internalType": "uint256",
            "name": "expiry",
            "type": "uint256"
        }, {
            "internalType": "uint8",
            "name": "v",
            "type": "uint8"
        }, {
            "internalType": "bytes32",
            "name": "r",
            "type": "bytes32"
        }, {
            "internalType": "bytes32",
            "name": "s",
            "type": "bytes32"
        }],
        "name": "delegateBySig",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [{
            "internalType": "address",
            "name": "",
            "type": "address"
        }],
        "name": "delegates",
        "outputs": [{
            "internalType": "address",
            "name": "",
            "type": "address"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [{
            "internalType": "address",
            "name": "account",
            "type": "address"
        }],
        "name": "getCurrentVotes",
        "outputs": [{
            "internalType": "uint96",
            "name": "",
            "type": "uint96"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [{
            "internalType": "address",
            "name": "account",
            "type": "address"
        }, {
            "internalType": "uint256",
            "name": "blockNumber",
            "type": "uint256"
        }],
        "name": "getPriorVotes",
        "outputs": [{
            "internalType": "uint96",
            "name": "",
            "type": "uint96"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "minimumTimeBetweenMints",
        "outputs": [{
            "internalType": "uint32",
            "name": "",
            "type": "uint32"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": false,
        "inputs": [{
            "internalType": "address",
            "name": "dst",
            "type": "address"
        }, {
            "internalType": "uint256",
            "name": "rawAmount",
            "type": "uint256"
        }],
        "name": "mint",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "mintCap",
        "outputs": [{
            "internalType": "uint8",
            "name": "",
            "type": "uint8"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "minter",
        "outputs": [{
            "internalType": "address",
            "name": "",
            "type": "address"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "mintingAllowedAfter",
        "outputs": [{
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "name",
        "outputs": [{
            "internalType": "string",
            "name": "",
            "type": "string"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [{
            "internalType": "address",
            "name": "",
            "type": "address"
        }],
        "name": "nonces",
        "outputs": [{
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [{
            "internalType": "address",
            "name": "",
            "type": "address"
        }],
        "name": "numCheckpoints",
        "outputs": [{
            "internalType": "uint32",
            "name": "",
            "type": "uint32"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": false,
        "inputs": [{
            "internalType": "address",
            "name": "owner",
            "type": "address"
        }, {
            "internalType": "address",
            "name": "spender",
            "type": "address"
        }, {
            "internalType": "uint256",
            "name": "rawAmount",
            "type": "uint256"
        }, {
            "internalType": "uint256",
            "name": "deadline",
            "type": "uint256"
        }, {
            "internalType": "uint8",
            "name": "v",
            "type": "uint8"
        }, {
            "internalType": "bytes32",
            "name": "r",
            "type": "bytes32"
        }, {
            "internalType": "bytes32",
            "name": "s",
            "type": "bytes32"
        }],
        "name": "permit",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "constant": false,
        "inputs": [{
            "internalType": "address",
            "name": "minter_",
            "type": "address"
        }],
        "name": "setMinter",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "symbol",
        "outputs": [{
            "internalType": "string",
            "name": "",
            "type": "string"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": true,
        "inputs": [],
        "name": "totalSupply",
        "outputs": [{
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
        }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    }, {
        "constant": false,
        "inputs": [{
            "internalType": "address",
            "name": "dst",
            "type": "address"
        }, {
            "internalType": "uint256",
            "name": "rawAmount",
            "type": "uint256"
        }],
        "name": "transfer",
        "outputs": [{
            "internalType": "bool",
            "name": "",
            "type": "bool"
        }],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "constant": false,
        "inputs": [{
            "internalType": "address",
            "name": "src",
            "type": "address"
        }, {
            "internalType": "address",
            "name": "dst",
            "type": "address"
        }, {
            "internalType": "uint256",
            "name": "rawAmount",
            "type": "uint256"
        }],
        "name": "transferFrom",
        "outputs": [{
            "internalType": "bool",
            "name": "",
            "type": "bool"
        }],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }];
    const contract = new _web3.eth.Contract(
        abi, "0x439ecD2F575f84Ce1587e011116b899Bd0aF1552"
    );

    const abi1 = [{
        "inputs": [{
            "internalType": "address",
            "name": "_tokenAddress",
            "type": "address"
        }],
        "stateMutability": "nonpayable",
        "type": "constructor"
    }, {
        "anonymous": false,
        "inputs": [{
            "indexed": true,
            "internalType": "address",
            "name": "user",
            "type": "address"
        }, {
            "indexed": false,
            "internalType": "uint256",
            "name": "amount",
            "type": "uint256"
        }],
        "name": "Collect",
        "type": "event"
    }, {
        "anonymous": false,
        "inputs": [{
            "indexed": true,
            "internalType": "address",
            "name": "previousOwner",
            "type": "address"
        }, {
            "indexed": true,
            "internalType": "address",
            "name": "newOwner",
            "type": "address"
        }],
        "name": "OwnershipTransferred",
        "type": "event"
    }, {
        "inputs": [{
            "internalType": "uint256",
            "name": "amount",
            "type": "uint256"
        }],
        "name": "collect",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "inputs": [],
        "name": "owner",
        "outputs": [{
            "internalType": "address",
            "name": "",
            "type": "address"
        }],
        "stateMutability": "view",
        "type": "function"
    }, {
        "inputs": [],
        "name": "renounceOwnership",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "inputs": [],
        "name": "tokenAddress",
        "outputs": [{
            "internalType": "address",
            "name": "",
            "type": "address"
        }],
        "stateMutability": "view",
        "type": "function"
    }, {
        "inputs": [],
        "name": "tokenBalance",
        "outputs": [{
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
        }],
        "stateMutability": "view",
        "type": "function"
    }, {
        "inputs": [{
            "internalType": "address",
            "name": "newOwner",
            "type": "address"
        }],
        "name": "transferOwnership",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    }, {
        "inputs": [{
            "internalType": "address",
            "name": "tokenHolder",
            "type": "address"
        }],
        "name": "userBalance",
        "outputs": [{
            "internalType": "uint256",
            "name": "",
            "type": "uint256"
        }],
        "stateMutability": "view",
        "type": "function"
    }];
    const contractCollect = new _web3.eth.Contract(
        abi1, "0x439ecD2F575f84Ce1587e011116b899Bd0aF1552"
    );

    document.getElementById("conntWallet").addEventListener("click", async function() {
        if ($('#nftsRented').val() != '') {
            $("#conntWallet").html("Processing...");
            $("#conntWallet").attr("disabled", true);
        }
        try {
            await window.ethereum.enable();
            console.log(window.ethereum.selectedAddress);

            //           const userAddress = window.ethereum.selectedAddress;
            //           $("#nftsRented").on("keyup change", function() {
            // var number_value =  $('#nftsRented').val();

            // });
            let sendFt = document.getElementById("newftPrice").innerHTML;
            const userAddress = window.ethereum.selectedAddress;
            const amount = sendFt;
            await contract.methods.approve(
                    "0x439ecD2F575f84Ce1587e011116b899Bd0aF1552",
                    _web3.utils.toWei(amount.toString(), "ether")
                )
                .send({
                    from: userAddress
                }).then(function(approvalReceipt) {
                    console.log('The approval receipt is:', approvalReceipt);
                    document.getElementById("conntWallet").style.display = "none";
                    document.getElementById("rent").style.display = "block";
                }).catch(function(test) {
                    console.log('The approval receipt is:', test);
                });

        } catch (error) {
            console.error(error);
        }
    });

    document.getElementById("rent").addEventListener("click", async function() {
        $("#rent").html("Processing...");
        $("#rent").attr("disabled", true);
        try {
            await window.ethereum.enable();
            console.log(window.ethereum.selectedAddress);

            let sendFt = document.getElementById("newftPrice").innerHTML;
            const userAddress = window.ethereum.selectedAddress;
            const amount = sendFt;
            console.log(userAddress, amount);
            let receipt;
            await contractCollect.methods.collect(
                    _web3.utils.toWei(amount.toString(), "ether")
                )
                .send({
                    from: userAddress,
                    // value: _web3.utils.toWei(amount.toString(), "ether")
                }).then(function(result) {
                    receipt = JSON.stringify(result);
                    console.log(receipt);

                    // Saving the receipt information to the server using ajax
                    const rentedNft = document.getElementById("rent").value;
                    console.log("rentedNft");

                    $.ajax({
                        type: "POST",
                        url: "{{route('nft.save')}}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "user_meta_mask_info": receipt,
                            "one_nft_price": 24,
                            "ft_price": document.getElementById("priceft").innerHTML,
                            "rented_nft": document.getElementsByName("amount")[0].value,
                            "buying_date": new Date(),
                            "user_id": {
                                {
                                    Auth::id()
                                }
                            }
                        },
                        success: function(data) {
                            window.location.href = "{{ route('plan')}}";
                        },
                        error: function(error) {
                            console.error(
                                "Error while saving the receipt information: ",
                                error);
                        }
                    });
                });
        } catch (error) {
            console.error(error);
        }
    });


})();
</script>

<script>
const startDate = new Date();
const endDate = new Date();
endDate.setDate(endDate.getDate() + 90);

const profitDate = new Date(startDate);
profitDate.setDate(profitDate.getDate() + 9);

const options = {
    day: "2-digit",
    month: "2-digit",
    year: "numeric"
};

document.getElementById("start-date").textContent = startDate.toLocaleDateString("en-US", options);
document.getElementById("end-date").textContent = endDate.toLocaleDateString("en-US", options);
document.getElementById("profit-date").textContent = profitDate.toLocaleDateString("en-US", options);
</script>
<script>
const rentOptionSelect = document.getElementById("rentOption");
const dataA = document.getElementById("dataA");
const dataB = document.getElementById("dataB");

rentOptionSelect.addEventListener("change", function() {
    if (rentOptionSelect.value === "Metamask") {
        dataA.style.display = "block";
        dataB.style.display = "none";
        if (typeof window.ethereum === 'undefined') {
            alert("Metamask extension is not enabled in your browser");
            document.getElementById("conntWallet").hidden = true;
            document.getElementById("rent").disabled = true;
        } else {
            document.getElementById("conntWallet").disabled = false;
            document.getElementById("rent").disabled = false;
        }
    } else if (rentOptionSelect.value === "DepositWallet" || rentOptionSelect.value === "NftsCube" ||
        rentOptionSelect.value === "RewardsCube") {
        dataA.style.display = "none";
        dataB.style.display = "block";
    } else {
        dataA.style.display = "none";
        dataB.style.display = "none";
    }
});

$("#mineOption").change(function() {
    let selVal = $('#mineOption').val();
    if (selVal == 'whole') {
        $('#mineRentAmt').show();
        $('#qtyLabel').html("Quantity");
        $('#minePaymentMethodDiv').show();
    } else if (selVal == 'partial') {
        $('#mineRentAmt').show();
        $('#qtyLabel').html("Fractions");
        $('#minePaymentMethodDiv').show();
    } else {
        $('#mineRentAmt').hide();
        $('#minePaymentMethodDiv').hide();
        $('#qtyLabel').html("Fractions");
        $('#btnDepositWallet').hide();
    }
});

$("#minePaymentMethod").change(function() {
    let minePaymentMethod = $('#minePaymentMethod').val();
    if (minePaymentMethod == 'deposit' || minePaymentMethod == 'RewardsCube' || minePaymentMethod ==
        'NftsCube') {
        $('#btnDepositWallet').show();
    } else {
        $('#btnDepositWallet').hide();
    }
});
$("#goldMinePaymentMethod").change(function() {
    let goldMinePaymentMethod = $('#goldMinePaymentMethod').val();
    if (goldMinePaymentMethod == 'deposit' || goldMinePaymentMethod == 'RewardsCube' || goldMinePaymentMethod ==
        'NftsCube') {
        $('#goldMtnDepositWallet').show();
    } else {
        $('#goldMtnDepositWallet').hide();
    }
});

$('button[type=submit],input[type=submit]').click(function() {
    var isValid = $(this).closest("form")[0].reportValidity();
    if(isValid){
        $(this).html('<span class="fa fa-spinner fa-spin"></span>');
        $(this).attr('disabled', 'disabled');
        $(this).parents('form').submit();
    }
});
</script>
<script>
document.getElementById("nftsRented").addEventListener("input", function() {
    let nftsRented = this.value;
    let nftsRentedError = document.getElementById("nftsRentedError");
    if (nftsRented.includes('.')) {
        nftsRentedError.style.display = 'block';
        this.value = Math.trunc(nftsRented);
    } else {
        nftsRentedError.style.display = 'none';
    }
});
</script>

@endsection
