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
							'title'=>'block test body finish',
							'type'=>'block',
							'block_id'=>16609762594785946,
							'part'=>'finish',
							'data'=>['Body block'],
							'recipients'=>['admin'],
							'alert_mode'=>'hide',
							'status'=>'normal'
						],256),
		        ]
		    ]);
	    $response = $result->getBody();
	    $data_response = json_decode($response, true);
	    echo '<pre>'; print_r($data_response); echo '</pre>';
	  
    }
	
	public function telega()
	{
		//$Client = Redis::ProjectGetBySecret('IcjxBCUp4zLv1e9V1660824325');
		//echo '<pre>'; print_r($Client); echo '</pre>';
		$row = '{ "valid": true, "errors": "", "log_record": { "title": "block test start", "type": "block", "block_id": null, "part": "start", "recipients": [ { "user_id": "1", "user_telegram_id": "822173207", "user_name": "admin", "user_login": "admin" } ], "alert_mode": "silent", "status": "normal", "log_structured_data": [ "Start of block" ], "project_name": "СМС-Конвеер", "project_id": "1", "timer": 60 } }';
		$data = json_decode($row,true);
		echo'<pre>'; print_r($data); echo'</pre>';
		echo '<pre>'; print_r(Redis::BlockStartLog($data)); echo '</pre>';
		
	}
	
	
}
