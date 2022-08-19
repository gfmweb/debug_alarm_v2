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
		
	  $LogsModel = model(LogModel::class);
	  echo '<pre>'; print_r($LogsModel->select('log_id')->orderBy('log_id','DESC')->first()); echo '</pre>';
    }
	
	public function telega()
	{
	
	
	}
	
	
}
