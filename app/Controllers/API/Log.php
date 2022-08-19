<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Controllers\Redis;
use CodeIgniter\API\ResponseTrait;
use function PHPUnit\Framework\isJson;

/**
 *  Принимает по POST логи для записи
 *  По GET отдаёт инструкции по работе с ним
 */
class Log extends BaseController
{
	use ResponseTrait;
	
	/**
	 * @return bool Проверка состояния сервиса
	 */
	private static function GetServiceStatus(): bool
	{
		$Redis = Redis::getInstance();
		return ($Redis->get('global_service_status') == 'start');
		
	}
	
	/**
	 * @param string $data Сырая строка пришедшая в запросе
	 * @return array набор данных [bool 'valid'=>true||false, string 'errors'=>'описание ошибки', array 'log_record'=>[подготовленная запись для вставки в БД]]
	 */
	private static function ValidateRequest(string $data, string $secret):array
	{
		if(!isJson($data)) return ['valid'=>false,'errors'=>'Json invalid','log_record'=>[]];
		$Client = Redis::ProjectGetBySecret($secret);
		if(!isset($Client['project_id'])) return ['valid'=>false,'errors'=>'Incorrect Key','log_record'=>[]];
		$data = json_decode($data,true);
		if(!isset($data['data'])) return ['valid'=>false,'errors'=>'Empty LOG','log_record'=>[]];
		$data['log_structured_data']=$data['data'];
		$data['project_name'] = $Client['project_name'];
		$data['project_id'] = $Client['project_id'];
		unset($data['data']);
		
		$data['recipients'] = (!isset($data['recipients'])||!is_array($data['recipients'])) ? Redis::UsersGetAll():Redis::UserGetByLogin($data['recipients']);
		if(!isset($data['alert_mode'])) $data['alert_mode']='hide';
		if(!in_array($data['alert_mode'],['hide','silent','alarm'])) $data['alert_mode']='hide';
		if(!isset($data['type'])){$data['part']='body'; $data['type']='single';}
		
		if(!in_array($data['type'],['single','block'])) $data['type']='single';
		
		if(!in_array($data['part'],['body','start','finish'])) $data['part']='body';
		
		if(!isset($data['title']) && $data['part']=='body' && $data['type']=='single') $data['title'] = 'Одиночная запись лога с сервиса '.$Client['project_name'];
		
		if(!isset($data['title']) && $data['part']=='start' && $data['type']=='block') $data['title'] = 'Начальная запись лога с сервиса '.$Client['project_name'];
		if(!isset($data['title']) && $data['part']=='body' && $data['type']=='block') $data['title'] = 'Продолжение запись лога с сервиса '.$Client['project_name'];
		if(!isset($data['title']) && $data['part']=='finish' && $data['type']=='block') $data['title'] = 'Конечная запись лога с сервиса '.$Client['project_name'];
		if($data['type']=='block'&&(!isset($data['timer_check'])||(!is_int($data['timer_check'])))) $data['timer'] = 60;
		if($data['type']=='block' && in_array($data['part'],['body','finish']) && !isset($data['block_id'])) return ['valid'=>false,'errors'=>'missing block_id','log_record'=>[]];
		
		if(!isset($data['status'])) $data['status']='normal';
		
		return ['valid'=>true,'errors'=>'','log_record'=>$data];
		
	}
	
	
	
    public function CreateLog()
    {
        if(!self::GetServiceStatus()) return $this->respond('503 Service Unavailable',503);
		$request = $this->request->getUri()->getSegments();
		if(!isset($request[3])) return $this->respond('Bad request. No SecretKey detected',400);
		$data = self::ValidateRequest($this->request->getVar('log'),$request[3]);
		if(!$data['valid']) return $this->respond('Bad request OR Auth error'.PHP_EOL.$data['errors'],400);
	 
		
		if($data['log_record']['type']=='block' && $data['log_record']['part']=='start'){
			$result = Redis::BlockStartLog($data);
		}
	    elseif($data['log_record']['type']=='block' && $data['log_record']['part']=='body'){
		    $result = Redis::BlockBodyLog($data);
	    }
		elseif($data['log_record']['type']=='block' && $data['log_record']['part']=='finish'){
			$result = Redis::BlockFinishLog($data);
		}
		else{
			$result = Redis::SingleLog($data);
		}
	 
		return ($result['id']!==0)? $this->respond(['store'=>'ok','result'=>$result,'data'=>$data],200):$this->respond(['store'=>false,'errors'=>$result['errors'],'data'=>$data],400);
		
    }
	
	public function getInstruction()
	{
		if(!self::GetServiceStatus()) return $this->respond('503 Service Unavailable',503);
		$request = $this->request->getUri()->getSegments();
		if(!isset($request[3])) return $this->respond('Bad request. No SecretKey detected',400);
		$Client = Redis::ProjectGetBySecret($request[3]);
		if(!isset($Client['project_id'])) return $this->respond('Incorrect Key',403);
		return view('user/instruction');
	}
}
