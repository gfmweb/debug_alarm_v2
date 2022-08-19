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
		//https://debug.gfmweb.ru/api/v1/log/IcjxBCUp4zLv1e9V1660824325
	    $options = ['baseURI' => 'https://debug.gfmweb.ru/api/v1/log/IcjxBCUp4zLv1e9V1660824325', 'timeout' => 3,];
	    $curl = \Config\Services::curlrequest($options);
	    $result = $curl->request('POST', $options['baseURI'],
		    ['form_params' =>
			    [
			        'log' => json_encode(['type'=>'single','status'=>'critical', 'block_id'=>12, 'part'=>'body','data'=>['some data'],'recipients'=>['admin'],'alert_mode'=>'silent'],256),
		        ]
		    ]);
	    $response = $result->getBody();
	    $data_response = json_decode($response, true);
	    echo ($response);
	  
    }
	
	public function telega()
	{
		//$Client = Redis::ProjectGetBySecret('IcjxBCUp4zLv1e9V1660824325');
		//echo '<pre>'; print_r($Client); echo '</pre>';
		$row = '{ "store": "ok", "log_id": "6113", "data": { "valid": true, "errors": "", "log_record": { "title": "title", "type": "single", "block_id": 12, "part": "body", "recipients": [ { "user_id": "1", "user_telegram_id": "822173207", "user_name": "admin", "user_login": "admin" } ], "alert_mode": "silent", "log_structured_data": [ "some data" ], "project_name": "СМС-Конвеер", "status": "normal" } } }';
		$data = json_decode($row,true);
		echo'<pre>'; print_r($data); echo'</pre>';
		echo '<pre>'; print_r(Redis::SingleLog($data)); echo '</pre>';
		
	}
	
	
}
