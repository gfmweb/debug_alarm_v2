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
		// start - 16609762594785946 -id=6093
	    // body  - 6094,6095б с-6096 фин 6097
		//https://debug.gfmweb.ru/api/v1/log/IcjxBCUp4zLv1e9V1660824325
	    $options = ['baseURI' => 'https://debug.gfmweb.ru/api/v1/log/IcjxBCUp4zLv1e9V1660824325', 'timeout' => 3,];
	    $curl = \Config\Services::curlrequest($options);
	    $result = $curl->request('POST', $options['baseURI'],
		    ['form_params' =>
			    [
			        'log' => json_encode(
						[
							'title'=>'record finish',
							'type'=>'block',
							'block_id'=>16609900385901173,
							'part'=>'start',
							'data'=>['Alert_timer_test'],
							'recipients'=>['admin'],
							'alert_mode'=>'hide',
							'status'=>'normal',
							'timer_check'=>10
						],256),
		        ]
		    ]);
	    $response = $result->getBody();
	    $data_response = json_decode($response, true);
	    echo '<pre>'; print_r($data_response); echo '</pre>';
	  
    }
	
	public function telega()
	{
	 $row = '{"log_id":"2915","project_id":"1","project_name":"\u0421\u041c\u0421-\u041a\u043e\u043d\u0432\u0435\u0435\u0440","block_id":"1660993008673140","ttl":0,"ttl_etalon":10,"recipients":[{"user_id":"1","user_telegram_id":"822173207","user_name":"admin","user_login":"admin"}]}';
	 $data = json_decode($row,true);
	 
	 echo'<pre>'; print_r($data); echo'</pre>';
		$text = 'За отведенное время <b>'.$data['ttl_etalon'].'</b> секунд.'.PHP_EOL.'
			Сервис  <b>'.$data['project_name'].'</b> больше не прислал ни одного лога! ВОЗМОЖНО <i>'.$data['project_name'].'</i>  <b> УПАЛ!</b>'.PHP_EOL.'
			Начало цепочки действий ID= '.$data['log_id'];
		foreach ($data['recipients'] as $user){
			TelegramAPI::sendMessage($user['user_telegram_id'],$text,false);
		}
		
	}
	
	
}
