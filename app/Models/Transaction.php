<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const CREATED = 1;
    const FILLED = 2;
    const SENT = 3;
    const RECEIVED = 4;

    public static $types = [
        self::CREATED => 'created',
        self::FILLED => 'filled',
        self::SENT => 'sent',
        self::RECEIVED => 'received'
    ];

    /** Relations */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function causedBy()
    {
        return $this->belongsTo(User::class, 'caused_by_id', 'id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    /** Scopes */

    public function scopeFilter($query)
    {
        $from = request('from') ?? now()->subMonth()->format('Y-m-d');
        $to = request('to') ?? now()->addDay()->format('Y-m-d');
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
