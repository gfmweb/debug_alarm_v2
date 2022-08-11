<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Login extends BaseController
{
	use ResponseTrait;
	
	public function index()
	{
		return view('login');
	}
	
	
	/**
	 * @return \CodeIgniter\HTTP\Response Форма авторизации для входа
	 */
    public function requestLoginForm()
    {
		$mode = $this->request->getVar('mode');
		$form = [
			'method'=>'POST',
			'form_header'=>($mode == 'admin')?'Вход в админку':'Просмотр логов',
			'action'=>($mode == 'admin')? ['/login/admin','/login/admin/check']:['/login/user','/login/user/check'],
			'form_fields'=>[
				[
					['label'=>'Login','type'=>'text','name'=>'login'],
					['label'=>'Password','type'=>'password','name'=>'password'],
				],
				[
					['label'=>'Check CODE','type'=>'number','name'=>'code']
				]
			]
		];
		return $this->respond($form,200);
    }
}
