<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    /** Relations */

    public function rate()
    {
        return $this->hasOne(Rate::class)->where('date', date('Y-m-d'));
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
