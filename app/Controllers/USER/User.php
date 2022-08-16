<?php

namespace App\Controllers\USER;

use App\Controllers\BaseController;

class User extends BaseController
{
    public function index()
    {
       return view('user/user_index');
    }
}
