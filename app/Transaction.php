<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function sub_distributor()
    {
        return $this->belongsTo('App\SubDistributor');
    }
}
