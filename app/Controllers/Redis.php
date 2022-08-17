<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogModel;

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
		if(is_null (self::$Rediska)){
			self::initial();
		}
		return self::$Rediska;
	}
	
	public static function set(string $key, string $value):bool
	{
		
		self::$Rediska->set($key);
		return true;
	}
	
	public static function updateLogList()
	{
		self::initial();
		if(self::$Rediska->exists('list_log')){
			self::$Rediska->del('list_logs');
		}
		$LogsModel = model(LogModel::class);
		$data = $LogsModel->select(['logs.log_id','logs.log_structured_data','logs.created_at','projects.project_name'])
			->join('projects','logs.log_project_id = projects.project_id','LEFT')
			->limit(100)
			->orderBy('log_id','DESC')
			->find();
		for($i=0,$imax=count($data); $i < $imax; $i++){
			$data[$i]['log_structured_data'] = json_decode($data[$i]['log_structured_data'],true);
		}
		foreach ($data as $record){
			self::$Rediska->lPush('list_logs',json_encode(['log_id'=>$record['log_id'],'project_name'=>$record['project_name'],'title'=>'Загружено из БД','time'=>$record['created_at'],'part'=>'body','status'=>'normal'],256));
		}
		return $data;
	}
	
	
}
