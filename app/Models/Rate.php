<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $dates = ['date:Y-m-d'];
    protected $fillable = ['value', 'date'];

    /** Relations */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
