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
	
	/**
	 * @return string Загрузка фронтовой части
	 */
    public function index():string
    {  return view('user/user_index'); }
	
	
	/**
	 * @return \CodeIgniter\HTTP\Response Основное меню пользователя
	 */
	public function getMainMenu()
	{
		$user = $this->session->get('user');
		return $this->respond(
			[
				'MenuButtons'=>
					[
						['name'=>'<i class="fa-solid fa-clock"></i>','action'=>'real'],
						['name'=>'<i class="fa-solid fa-database"></i>','action'=>'prepareQuery'],
						['name'=>'<i class="fa-solid fa-gear"></i>','action'=>'settings'],
					],
				'MenuHeaderText'=>'Основные действия ',
				'PathToServer'=>$_SERVER['SERVER_NAME'],
				'UserIdentity'=>$user['user_id']
			],200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response 100 последних записей (для меню просмотр в режиме реального времени)
	 */
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
	
	/**
	 * @return \CodeIgniter\HTTP\Response Тело конкретной записи лога по его ID (при нажатии на строку с логом в модалке опображается именно это)
	 */
	public function getLogInfoByID()
	{
		$id = $this->request->getVar('id');
		$Logs = model(LogModel::class);
		$log = $Logs->find($id);
		return $this->respond($this->arrayToHTML(json_decode($log['log_structured_data'],true)),200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Рассылка сообщений пользователям
	 */
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
		}
		return $this->respond('ok',200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Смена пароля пользователя (при этом не сменится пароль администартора)
	 * @throws \ReflectionException
	 */
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
	
	
	/**
	 * @return \CodeIgniter\HTTP\Response Обработчик запроса формы поиска по БД
	 */
	public function LogDBQuery()
	{
		$Projects = model(ProjectModel::class);
		$Logs = model(LogModel::class);
		$idLog = $this->request->getVar('id');
		if(!is_null($idLog)&&$idLog!=='')
		{
			$DBdata = $Logs->getLogByID((int)$idLog);
			return $this->respond($this->prepareForFront($DBdata),200);
		}
		$project_to_find = $this->request->getVar('project_name');
		$projects = $Projects->getAll();
		// Собираем часть запроса относящегося к проекту
		$project_request = [];
		if($project_to_find !== 'null'){
			foreach ($projects as $project) {
				if ($project['project_name'] == $project_to_find) {
					array_push($project_request, $project['project_id']);
				}
			}
		}
		else{
			foreach ($projects as $project) {array_push($project_request, $project['project_id']);}
		}
		$needle = $this->request->getVar('query');
		
		$startDateTime = $this->request->getVar('starttime');
		$startDateTime = str_replace('T',' ',$startDateTime);
		if($startDateTime!==''){ //Работаем по ветке есть начало
			$startSeconds = $this->request->getVar('startsec');
			if(strlen($startSeconds)<1)$startSeconds = '0'.$startSeconds;
			if(strlen($startSeconds)<2)$startSeconds = '0'.$startSeconds;
			$startDateTime.=':'.$startSeconds;
		}

		$finishDateTime = $this->request->getVar('finishtime');
		$finishDateTime = str_replace('T',' ',$finishDateTime);
		if($finishDateTime!==''){ //Работаем по ветке есть начало
			$finishSeconds = $this->request->getVar('finishs');
			if(strlen($finishSeconds)<1)$finishSeconds = '0'.$finishSeconds;
			if(strlen($finishSeconds)<2)$finishSeconds = '0'.$finishSeconds;
			$finishDateTime.=':'.$finishSeconds;
		}
		$queryRequest = [
			'projects'=>$project_request,
			'query'=>($needle!=='')?$needle:false,
			'start'=>($startDateTime!=='')?$startDateTime:false,
			'finish'=>($finishDateTime!=='')?$finishDateTime:false
		];
		$result = $Logs->getLogsByQuery($queryRequest);
		return $this->respond($this->prepareForFront($result),200);
	}
	
	/**
	 * @param array $Data Массив логов из БД
	 * @return array Подготовленный к рендерингу массив для отправки на фронт
	 */
	private function prepareForFront(array $Data):array
	{
		if(isset($Data[0])&&is_array($Data[0])) {
			for ($i = 0, $imax = count($Data); $i < $imax; $i++) {
				$Data[$i]['log_structured_data'] = json_decode($Data[$i]['log_structured_data'], true);
			}
			$preparing = [];
			foreach ($Data as $record) {
				array_push($preparing, ['log_id' => $record['log_id'], 'project_name' => $record['project_name'], 'title' => $record['log_title'], 'time' => $record['created_at'], 'part' => $record['log_part'], 'status' => $record['log_status']]);
			}
		}
		else{
			$preparing[0] = (isset($Data['log_id']))?['log_id' => $Data['log_id'], 'project_name' => $Data['project_name'], 'title' => $Data['log_title'], 'time' => $Data['created_at'], 'part' => $Data['log_part'], 'status' => $Data['log_status']]:[];
		}
		return $preparing;
	}
}
