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
			        'log' => json_encode(['title'=>'title','type'=>'block', 'part'=>'start','data'=>['some data'],'recipients'=>['admin'],'alert_mode'=>'silent'],256),
		        ]
		    ]);
	    $response = $result->getBody();
	    $data_response = json_decode($response, true);
	    echo '<pre>'; print_r($data_response); echo '</pre>';
	  
    }
	
	public function telega()
	{
	
	}
	
	
}
