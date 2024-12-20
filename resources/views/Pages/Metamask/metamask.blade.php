@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-4 text-center">
        <div class="form-group">
            <h3>Enter Amount Here</h3>
            <input type="text" class="form-control" name="amount" id="inp_amount" aria-describedby="helpId"
                placeholder="Enter Amount In USD">
        </div>
        <button type="button" onClick="startProcess()" class="btn btn-success">Pay Now</button>
    </div>
</div>

@endsection
@push('js')

@endpush
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>

<script>
// Import the web3.js library
(async function() {
    await window.ethereum.enable();
    console.log(window.ethereum.selectedAddress);


    const _web3 = new Web3(
        window.ethereum
        // "https://restless-wider-sailboat.bsc-testnet.discover.quiknode.pro/909a45380f909abe8c3f55065b06014a0a9e69f0"
    );

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

    // const contract = new _web3.eth.Contract(
    //     abi, "0x6bf322e9db8b725E840dAc6fe403B923003584A0"
    // )
    // var userAddress = window.ethereum.selectedAddress;
    // // console.log(userAddress);
    // var amount = 1;
    // contract.methods.approve("0x6bf322e9db8b725E840dAc6fe403B923003584A0", _web3.utils.toWei(amount.toString(),
    //         'ether'))
    //     .send({
    //         from: userAddress
    //     }).then(function(approvalReceipt) {
    //         console.log('The approval receipt is:', approvalReceipt);
    //     }).catch(function(test) {
    //         console.log('The approval receipt is:', test);
    //     });

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
    }]
    const contract = new _web3.eth.Contract(
        abi1, "0xea417362aa8add9a38be9b3933f47cf48d45a93e"
    )
    var userAddress = window.ethereum.selectedAddress;
    // console.log(userAddress);
    var amount = 1;
    contract.methods.collect(_web3.utils.toWei(amount.toString(),
            'ether'))
        .send({
            from: userAddress
        }).then(function(approvalReceipt) {
            console.log('The approval receipt is:', approvalReceipt);
        }).catch(function(test) {
            console.log('The approval receipt is:', test);
        });

}());
</script>
<script src="{{asset('web3.min.js')}}"></script>