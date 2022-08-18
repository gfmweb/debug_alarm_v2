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
		
	  $Redis =  Redis::getInstance();
	  $Redis->rPush('real_time_update',json_encode(['log_id'=>6092,'project_name'=>'ADD_ME','title'=>'add_now_it_in_head - ','time'=>'2022-08-19 01:52:32','part'=>'body','status'=>'critical']));
	    $Redis->rPush('real_time_update',json_encode(['log_id'=>6093,'project_name'=>'ADD_ME2','title'=>'add_now_it_in_head2 - ','time'=>'2022-08-19 01:52:35','part'=>'body','status'=>'critical']));
    }
	
	public function telega()
	{
	
	
	}
	
	
}
