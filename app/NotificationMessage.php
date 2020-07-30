<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    public function notificationMessageSend(){
        return $this->hasMany(NotificationMessageSend::class);
    }
}
