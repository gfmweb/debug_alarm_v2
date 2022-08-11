<?php

namespace App\Controllers\ADMIN;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function index()
    {
       echo '<pre>'; var_dump($this->session); echo '</pre>';
	   $this->session->destroy();
    }
}
