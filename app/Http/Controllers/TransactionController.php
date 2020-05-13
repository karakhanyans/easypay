<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Show transactions by auth user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        // Get user all transactions filtered by date
        $transactions = $user
            ->transactions()
            ->filter()
            ->with('user', 'causedBy', 'wallet')
            ->get();
        // Get transactions with type=sent
        $totalSent = $user->wallets()->with(['transactions' => function($query){
            $query->whereType(Transaction::SENT)->filter();
        }])->get();
        // Get transactions with type=received
        $totalReceived = $user->wallets()->with(['transactions' => function($query){
            $query->whereType(Transaction::RECEIVED)->filter();
        }])->get();
        // Get sum of sent transactions grouped by wallet
        $totalSent = $totalSent->map(function($wallet){
            return [$wallet->currency->name => $wallet->transactions->sum('amount') * $wallet->currency->rate->value];
        });
        // Get sum of received transactions grouped by wallet
        $totalReceived = $totalReceived->map(function($wallet){
            return [$wallet->currency->name => $wallet->transactions->sum('amount') * $wallet->currency->rate->value];
        });

        $sum = [
            'sent' => $totalSent,
            'received' => $totalReceived,
        ];
        return view('transactions.index', compact('transactions', 'sum'));
    }

    /**
     * Download data in CSV
     * @throws \League\Csv\CannotInsertRecord
     */
    public function download()
    {
        $transactions = Auth::user()
            ->transactions()
            ->filter()
            ->with('user', 'causedBy', 'wallet')
            ->get();
        $csvExporter = new \Laracsv\Export();
        $csvExporter->beforeEach(function ($transaction) {
            $transaction->type = \App\Models\Transaction::$types[$transaction->type];
        });
        $csvExporter->build($transactions,
            [
                'id' => 'ID',
                'created_at' => 'Date',
                'type' => 'Type',
                'user.name' => 'User Name',
                'causedBy.name' => 'To/From',
                'amount' => 'Amount',
                'wallet.currency.name' => 'Currency'
            ]
        )->download();
    }
}
