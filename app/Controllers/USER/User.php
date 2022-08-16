<?php

namespace App\Controllers\USER;

use App\Controllers\BaseController;
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
						['name'=>'Настройки','action'=>'settings'],
					],
				'MenuHeaderText'=>'Основные действия ',
				'PathToServer'=>$_SERVER['SERVER_NAME'],
				'UserIdentity'=>$user['user_id']
			],200);
	}
}
