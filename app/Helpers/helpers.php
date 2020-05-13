<?php

if(!function_exists('transaction')){
    function transaction($type, $user_id, $caused_by_id, $wallet_id, $amount){
        $transaction = new \App\Models\Transaction();
        $transaction->type = $type;
        $transaction->user_id = $user_id;
        $transaction->caused_by_id = $caused_by_id;
        $transaction->wallet_id = $wallet_id;
        $transaction->amount = $amount;
        $transaction->save();
    }
}
