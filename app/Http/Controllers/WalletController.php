<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Http\Requests\WalletTransactionRequest;
use App\Http\Requests\WalletUpdateRequest;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // Get auth user all wallets
        $wallets = $user->wallets()->with('currency')->get();
        return view('wallets.index', compact('wallets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::all();
        return view('wallets.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WalletRequest $request
     * @return mixed
     */
    public function store(WalletRequest $request)
    {
        $user = Auth::user();
        $currency = Currency::findOrFail($request->get('currency_id'));
        // Check if user has wallet with this currency, if no create model fresh instance
        $wallet = $user->wallets()->firstOrNew(['currency_id' => $request->get('currency_id')]);
        $wallet = $wallet->fill($request->only('name', 'balance'));
        $wallet->currency()->associate($currency);
        $user->wallets()->save($wallet);
        // Save transaction to database
        transaction(Transaction::CREATED, $user->id, $user->id, $wallet->id, $request->balance);

        return redirect()->route('wallets.index')->withStatus('Wallet saved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        return view('wallets.edit', compact('wallet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WalletUpdateRequest $request
     * @param Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(WalletUpdateRequest $request, Wallet $wallet)
    {
        $wallet->balance += $request->get('balance');
        $wallet->save();
        // Save transaction to database
        transaction(Transaction::FILLED, Auth::id(), Auth::id(), $wallet->id, $request->balance);

        return redirect()->route('wallets.index')->withStatus('Amount added to balance');
    }

    /**
     * Show send money form
     * @param Wallet $wallet
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transactionForm(Wallet $wallet)
    {
        $users = User::with('wallets.currency')->where('id', '!=', auth()->id())->get();
        return view('wallets.transaction', compact('wallet', 'users'));
    }

    /**
     * @param WalletTransactionRequest $request
     * @param Wallet $wallet
     * @return mixed
     * @throws \Throwable
     */
    public function transaction(WalletTransactionRequest $request, Wallet $wallet)
    {
        // Check if user balance less then the amount he want to send
        throw_if($wallet->balance < $request->amount, \Exception::class, "You don't have enough money");

        $senderCurrencyRate = $wallet->currency->rate->value; // Get sender currency rate to usd
        $receiverWallet = Wallet::with('currency.rate')->find($request->wallet_id); //Get receiver wallet
        $receiverCurrencyRate = $receiverWallet->currency->rate->value ?? 1;
        $senderAmountInUsd = $senderCurrencyRate * $request->amount; // Convert sending amount to usd
        $receiverAmount = $senderAmountInUsd / $receiverCurrencyRate; // Convert sending amount to receiver currency
        $receiverWallet->balance += $receiverAmount; // Increase receiver balance by received amount
        $receiverWallet->save();
        $wallet->balance -= $request->amount; // Decrease sender amount by sent money
        $wallet->save();
        // Logging transaction to database
        transaction(Transaction::SENT, Auth::id(), $receiverWallet->user->id, $wallet->id, $request->amount);
        transaction(Transaction::RECEIVED, $receiverWallet->user->id, Auth::id(), $receiverWallet->id, $request->amount);

        return redirect()->route('wallets.index')->withStatus($request->amount . ' '  .$wallet->currency->name . ' sent to ' . $receiverWallet->user->name . ' ' . $receiverWallet->currency->name . ' balance');
    }
}
