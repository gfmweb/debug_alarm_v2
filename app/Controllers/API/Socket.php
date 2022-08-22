<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Controllers\TELEGRAM\TelegramAPI;
use App\Models\LogModel;
use CodeIgniter\API\ResponseTrait;

/**
 *  Класс обрабатывающий команды с WebSocket демона
 */
class Socket extends BaseController
{
	use ResponseTrait;
    public function index()
    {
	    $Secret = 'Nacta the best!';
		$hash = $this->request->getVar('secret');
		$action = $this->request->getVar('action');
        $data = json_decode($this->request->getVar('data'),true);
		if(!password_verify($Secret,$hash)) return $this->respond('No password or password is missing',200);
		if($action == 'dump'){
			for($i=0,$iMax=count($data); $i < $iMax; $i++){
				unset($data[$i]['log_id']);
				$data[$i]['log_title']      =   $data[$i]['title'];
				$data[$i]['log_part']       =   $data[$i]['part'];
				$data[$i]['log_project_id'] =   $data[$i]['project_id'];
				$data[$i]['log_status']     =   $data[$i]['status'];
			}
			$Logs = model(LogModel::class);
			$Logs->insertBatch($data);
		}
		elseif($action == 'timer'){
			$text = 'За отведенное время <b>'.$data['ttl_etalon'].'</b> секунд.'.PHP_EOL.'Сервис  <b>'.$data['project_name'].'</b> больше не прислал ни одного лога! ВОЗМОЖНО <i>'.$data['project_name'].'</i>  <b> УПАЛ!</b>'.PHP_EOL.'Начало цепочки действий ID= <b>'.$data['log_id'].'</b>';
			foreach ($data['recipients'] as $user){
				TelegramAPI::sendMessage($user['user_telegram_id'],$text,false);
			}
		}
		return $this->respond('ok',200);
    }
}
