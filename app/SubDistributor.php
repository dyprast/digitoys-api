<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubDistributor extends Model
{
    protected $hidden = ['password'];

    use SoftDeletes;

    public function sub_region()
    {
        return $this->belongsTo('App\SubRegion');
    }
}
