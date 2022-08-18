<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Controllers\TELEGRAM\TelegramAPI;
use App\Models\AdminModel;
use App\Models\LogModel;
use CodeIgniter\API\ResponseTrait;

class Test extends BaseController
{
	use ResponseTrait;
	
	
	
    public function index()
    {
		
	   $Redis = new Redis();
	   $result = $Redis::updateLogList(null);
	   echo '<pre>'; print_r($result); echo'</pre>';
		
    }
	
	public function telega()
	{
	
	
	}
	
	
}
