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
		$names = ['СМС-Конвеер','Нарезчик анкет','ВП'];
		$actions = ['Начало обработки','Рабочий момент','Ответ от внешнего сервиса','Конец обработки'];
		$array = ['project_name'=>$names[rand(0,2)],'log_id'=>rand(1000,2000),'title'=>$actions[rand(0,3)],'time'=>date('Y-m-d H:i:s')];
		
		
		$redis = new \Redis();
		$redis->connect('127.0.0.1',6379);
		$redis->del('list_logs');
		for ($i = 0; $i < 95; $i++){
			$redis->lPush('list_logs',json_encode($array,256));
		}
		
		
		
		
    }
	
	
	
	
}
