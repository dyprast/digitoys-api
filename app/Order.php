<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function sub_distributor()
    {
        return $this->belongsTo('App\SubDistributor');
    }

    public function main_distributor()
    {
        return $this->belongsTo('App\MainDistributor');
    }
}
