<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogModel;
use App\Models\ProjectModel;
use App\Models\UserModel;

/**
 *  Очень неудачный класс Redis
 *  Не хотел каждый раз писать строчку подключения
 */
class Redis extends BaseController
{
	public static $Rediska = null;

    public static function initial()
    {
       self::$Rediska = new \Redis();
	   self::$Rediska->connect('127.0.0.1',6379);
    }
	
	public static function getInstance():object
	{
		if(is_null (self::$Rediska)){self::initial();}
		return self::$Rediska;
	}
	
	/**
	 * @return void Реинициализация ключей
	 * @throws \RedisException
	 */
	public static function reInit()
	{
		self::initial();
		if(!self::$Rediska->exists('service_max_requests')) self::$Rediska->set('service_max_requests',0);
		if(!self::$Rediska->exists('total_log_rows')) {
			$Logs = model(LogModel::class);
			$lastID = $Logs->select('log_id')->orderBy('log_id','DESC')->first();
			$lastID = (isset($lastID['log_id']))?$lastID['log_id']:0;
			self::$Rediska->set('total_log_rows',$lastID);
		}
		if(!self::$Rediska->exists('service_at_work')) self::$Rediska->set('service_at_work','stop');
		if(!self::$Rediska->exists('list_logs')) $Logs->afterSeed();
		if(!self::$Rediska->exists('log_service_users')){
			$Users = model(UserModel::class);
			$users = $Users->getAnotherUsers(0);
			foreach ($users as $user){
				self::$Rediska->lpush('log_service_users',json_encode($user,256));
			}
		}
		if(!self::$Rediska->exists('log_service_projects')){
			$Projects = model(ProjectModel::class);
			foreach ($Projects->getAll() as $item){
				self::$Rediska->lpush('log_service_projects',json_encode($item,256));
			}
		}
	}
	
	/**
	 * Действия с пользователями
	 */
	
	/**
	 * @param array $User ['user_name'=>' ','user_login'=>' ','user_id'=>' ' ]
	 * @return bool Добавит пользователя в список
	 */
	public static function UserAdd(array $User):bool
	{
		self::initial();
		self::$Rediska->lpush('log_service_users',json_encode($User,256));
		return true;
	}
	
	public static function UserUpdate(int $userID, int $TelegramID)
	{
		self::initial();
		if(!self::$Rediska->exists('log_service_users')) return false;
		$usersCount = self::$Rediska->llen('log_service_users');
		for($i = 0,$iMax=$usersCount; $i<$iMax; $i++){
			$user = json_decode(self::$Rediska->lIndex('log_service_users',$i),true);
			if($user['user_id'] == $userID){
				$user['user_telegram_id'] = $TelegramID;
				self::$Rediska->lset('log_service_users',$i,json_encode($user,256));
			}
		}
		return true;
	}
	
	public static function UserGetByLogin(array $userLogin):array
	{
		self::initial();
		$usersCount = self::$Rediska->llen('log_service_users');
		$results = [];
		for($i = 0,$iMax=$usersCount; $i<$iMax; $i++){
			$user = json_decode(self::$Rediska->lIndex('log_service_users',$i),true);
			foreach ($userLogin as $userLog) {
				if ($user['user_login'] == $userLog) {
					$results[] = json_decode(self::$Rediska->lIndex('log_service_users', $i), true);
				}
			}
		}
		return $results;
	}
	
	public static function UsersGetAll():array
	{
		self::initial();
		$usersCount = self::$Rediska->llen('log_service_users');
		for($i = 0,$iMax=$usersCount; $i<$iMax; $i++){
			$users[] = json_decode(self::$Rediska->lIndex('log_service_users',$i),true);
		}
		return $users;
	}
	
	public static function UserDrop(int $userID):bool
	{
		self::initial();
		$usersCount = self::$Rediska->llen('log_service_users');
		for($i = 0,$iMax=$usersCount; $i<$iMax; $i++){
			$user = json_decode(self::$Rediska->lIndex('log_service_users',$i),true);
			if($user['user_id'] == $userID){
				$record = self::$Rediska->lIndex('log_service_users',$i);
				self::$Rediska->lRem('log_service_users',$record);
			}
		}
		return true;
	}
	
	/**
	 * Действия с проектами
	 */
	
	
	/**
	 * @param array $Project ['project_id','project_name','project_secret']
	 * @return bool Добавит проект для логирования
	 */
	public static function ProjectAdd(array $Project):bool
	{
		self::initial();
		self::$Rediska->lpush('log_service_projects',json_encode($Project,256));
		return true;
	}
	
	public static function ProjectUpdate(int $ProjectID, string $ProjectName, string $ProjectSecret):bool
	{
		self::initial();
		$projectsCount = self::$Rediska->llen('log_service_projects');
		for ($i=0,$iMax=$projectsCount; $i < $iMax; $i++){
			$project = json_decode(self::$Rediska->lIndex('log_service_projects',$i),true);
			if($project['project_id'] == $ProjectID){
				$project['project_name'] = $ProjectName;
				$project['project_secret'] = $ProjectSecret;
				self::$Rediska->lset('log_service_projects',$i,json_encode($project,256));
				return true;
			}
		}
		return false;
	}
	
	public static function ProjectGet(int $ProjectID):array
	{
		self::initial();
		$projectsCount = self::$Rediska->llen('log_service_projects');
		for ($i=0,$iMax=$projectsCount; $i < $iMax; $i++){
			$project = json_decode(self::$Rediska->lIndex('log_service_projects',$i),true);
			if($project['project_id'] == $ProjectID){
				return $project;
			}
		}
		return [];
	}
	
	public static function ProjectGetBySecret(string $ProjectSecret):array
	{
		self::initial();
		$projectsCount = self::$Rediska->llen('log_service_projects');
		for ($i=0,$iMax=$projectsCount; $i < $iMax; $i++){
			$project = json_decode(self::$Rediska->lIndex('log_service_projects',$i),true);
			if($project['project_secret'] == $ProjectSecret){
				return $project;
			}
		}
		return [];
	}
	
	public static function ProjectDrop(int $ProjectID):bool
	{
		self::initial();
		$projectsCount = self::$Rediska->llen('log_service_projects');
		for ($i=0,$iMax=$projectsCount; $i < $iMax; $i++){
			$project = json_decode(self::$Rediska->lIndex('log_service_projects',$i),true);
			if($project['project_id'] == $ProjectID){
				$record = self::$Rediska->lIndex('log_service_projects',$i);
				self::$Rediska->lRem('log_service_projects',$record);
			}
		}
		return true;
	}
	
	
	/**
	 * Действия с логами
	 */
	public static function SingleLog(array $Data):array
	{
		self::initial();
		self::$Rediska->incr('total_log_rows');
		$Data['log_record']['log_id']=self::$Rediska->get('total_log_rows');
		self::$Rediska->lPush('real_time_update',json_encode( // Отправили в кумулитивные обновления
			[
				'log_id'=>$Data['log_record']['log_id'],
				'project_name'=>$Data['log_record']['project_name'],
				'title'=>$Data['log_record']['title'],
				'part'=>$Data['log_record']['part'],
				'status'=>$Data['log_record']['status'],
				'time'=>date('Y-m-d H:i:s'),
				'log_structured_data'=>json_encode($Data['log_record']['log_structured_data'],256)
			]));
		self::$Rediska->rPush('logs_turn_to_DB',json_encode( // Положили в очередь на запись
			[
				'log_id'=>$Data['log_record']['log_id'],
				'project_name'=>$Data['log_record']['project_name'],
				'title'=>$Data['log_record']['title'],
				'part'=>$Data['log_record']['part'],
				'status'=>$Data['log_record']['status'],
				'time'=>date('Y-m-d H:i:s'),
				'log_structured_data'=>json_encode($Data['log_record']['log_structured_data'],256)
			]));
		self::$Rediska->lPush('list_logs',json_encode( // Добавили в общий лист Real_time
			[
				'log_id'=>$Data['log_record']['log_id'],
				'project_name'=>$Data['log_record']['project_name'],
				'title'=>$Data['log_record']['title'],
				'part'=>$Data['log_record']['part'],
				'status'=>$Data['log_record']['status'],
				'time'=>date('Y-m-d H:i:s'),
				'log_structured_data'=>json_encode($Data['log_record']['log_structured_data'],256)
			]));
		if(self::$Rediska->llen('list_logs')>100){
			self::$Rediska->rpop('list_logs');
		}
		$result['id'] = $Data['log_record']['log_id'];
		$result['errors']='';
		return 	$result;
	}
	
	public static function BlockStartLog(array $Data):array
	{
		self::initial();
		$result['id'] = 0;
		$result['errors']='';
		return 	$result;
	}
	
	public static function BlockBodyLog(array $Data):array
	{
		self::initial();
		$result['id'] = 0;
		$result['errors']='';
		return 	$result;
	}
	
	public static function BlockFinishLog(array  $Data):array
	{
		self::initial();
		$result['id'] = 0;
		$result['errors']='';
		return 	$result;
	}
	
	
	
}
