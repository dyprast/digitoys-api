<?php

namespace App\Repositories;

use App\ActionHistory;

class ActionHistoryRepository
{

    public function create($message = null)
    {
        if ($message) {
            $actionHistory          = new ActionHistory;
            $actionHistory->ip      = $_SERVER['REMOTE_ADDR'];
            $actionHistory->message = $message;
            $actionHistory->save();
        }
    }
}
