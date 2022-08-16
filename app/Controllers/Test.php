<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Controllers\TELEGRAM\TelegramAPI;
use App\Models\AdminModel;
use CodeIgniter\API\ResponseTrait;

class Test extends BaseController
{
	use ResponseTrait;
	
	
    public function index()
    {
		$redis = new \Redis();
		$redis->connect('127.0.0.1',6379);
		/*$redis->del('list');
		for ($i = 0; $i < 97; $i++){
			$redis->lPush('list','row'.$i);
		}*/
		$count = $redis->lLen('list');
		echo $count.'<br/>';
		if($count > 99){
			$redis->rPop('list');
			$redis->lPush('list','simulated_row');
		}
		else{
			$redis->lPush('list','communicative_string');
		}
		echo $redis->lIndex('list', 0);
    }
	
	
	
	
}
