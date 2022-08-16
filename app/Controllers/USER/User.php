<?php

namespace App\Controllers\USER;

use App\Controllers\BaseController;
use App\Controllers\Redis;
use App\Models\LogModel;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
	use ResponseTrait;
    public function index()
    {
       return view('user/user_index');
    }
	
	public function getMainMenu()
	{
		$user = $this->session->get('user');
		
		return $this->respond(
			[
				'MenuButtons'=>
					[
						['name'=>'Real time','action'=>'real'],
						['name'=>'Выборка','action'=>'prepareQuery'],
						['name'=>'Настройки','action'=>'settings'],
					],
				'MenuHeaderText'=>'Основные действия ',
				'PathToServer'=>$_SERVER['SERVER_NAME'],
				'UserIdentity'=>$user['user_id']
			],200);
	}
	
	public function getLastLogs()
	{
		$Redis = Redis::getInstance();
		$count = $Redis->lLen('list_logs');
		$array = [];
		for($i=0; $i < $count; $i++)
		{
			array_push($array,json_decode($Redis->lIndex('list_logs',$i),true));
		}
		return $this->respond($array,200);
	}
	
	public function getLogInfoByID()
	{
		$id = $this->request->getVar('id');
		$Logs = model(LogModel::class);
		$log = $Logs->find(1);
		return $this->respond($this->arrayToHTML(json_decode($log['log_structured_data'],true)),200);
	}
}
