<?php

namespace App\Controllers\USER;

use App\Controllers\BaseController;
use App\Controllers\Redis;
use App\Controllers\TELEGRAM\TelegramAPI;
use App\Models\LogModel;
use App\Models\ProjectModel;
use App\Models\UserModel;
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
						['name'=>'Сменить пароль','action'=>'settings'],
					],
				'MenuHeaderText'=>'Основные действия ',
				'PathToServer'=>$_SERVER['SERVER_NAME'],
				'UserIdentity'=>$user['user_id']
			],200);
	}
	
	public function getLastLogs()
	{
		$Projects = model(ProjectModel::class);
		$Users = model(UserModel::class);
		$Redis = Redis::getInstance();
		$count = $Redis->lLen('list_logs');
		$array = [];
		for($i=0; $i < $count; $i++)
		{
			array_push($array,json_decode($Redis->lIndex('list_logs',$i),true));
		}
		
		$projects = $Projects->getAll();
		$users = $Users->getAnotherUsers($_SESSION['user']['user_id']);
		for($i=0,$iMax=count($projects); $i<$iMax; $i++){
			unset($projects[$i]['project_secret']);
		}
		$response['projects']=$projects;
		$response['list']=$array;
		$response['parts']=[['name'=>'Стартовые','value'=>'start'],['name'=>'Выполнение','value'=>'body'],['name'=>'Завершающие','value'=>'finish']];
		$response['statuses']=[['name'=>'Корректные','value'=>'normal'],['name'=>'Критические','value'=>'critical']];
		$response['users']=$users;
		return $this->respond($response,200);
	}
	
	public function getLogInfoByID()
	{
		$id = $this->request->getVar('id');
		$Logs = model(LogModel::class);
		$log = $Logs->find($id);
		return $this->respond($this->arrayToHTML(json_decode($log['log_structured_data'],true)),200);
	}
	
	public function sendAlarm()
	{
		$UserModel = model(UserModel::class);
		$recipient = $this->request->getVar('recipient');
		$id = $this->request->getVar('log_id');
		$comment = $this->request->getVar('comment');
		if($recipient == 'All'){
			$recipient= $UserModel->getAnotherUsers($_SESSION['user']['user_id']);
			for($i=0,$iMax=count($recipient); $i<$iMax; $i++){
				$recipient[$i]=$recipient[$i]['user_telegram_id'];
			}
		}
		else{
			$recipient = [(int)$recipient];
		}
		$text = $_SESSION['user']['user_name'].' Просит посмотреть запись ID-<b>'.$id.'</b>';
		$text.=($comment!=='')?PHP_EOL.'Комментарий отправителя:  <b>'.PHP_EOL.$comment.'</b>':'';
		foreach ($recipient as $user)
		{
			$messageID = TelegramAPI::sendMessage($user,$text,false);
			//todo inline button accept action! Payload telegram && recall to sender
		}
		return $this->respond('ok',200);
	}
	
	public function setNewPassword()
	{
		$response['classs']='bg-danger';
		$response['text']='Ващ текущий пароль введен неверно';
		$Users = model(UserModel::class);
		$pass = $this->request->getVar('password');
		$newPass = $this->request->getVar('newPassword');
		$currentUser = $Users->find($_SESSION['user']['user_id']);
		if(!password_verify($pass,$currentUser['user_password'])){
			return $this->respond($response,200);
		}
		$Users->updateUserPassword($_SESSION['user']['user_id'],$newPass);
		$response['classs']='bg-success';
		$response['text']='Ващ  пароль успешно изменён';
		return $this->respond($response,200);
		
	}
}
