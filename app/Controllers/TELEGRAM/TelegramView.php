<?php

namespace App\Controllers\TELEGRAM;

use App\Controllers\BaseController;
use App\Models\UserModel;

class TelegramView extends BaseController
{
    public function index()
    {
       return view('user/user_index');
    }
}
