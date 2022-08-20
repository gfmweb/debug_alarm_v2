<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        echo password_hash('temp',PASSWORD_DEFAULT);
    }
}
