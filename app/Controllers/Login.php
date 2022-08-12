<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\TELEGRAM\TelegramAPI;
use App\Models\AdminModel;
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
			'submit_btn_txt'=>'Submit',
			'form_header'=>($mode == 'admin')?'Вход в админку':'Просмотр логов',
			'action'=>($mode == 'admin')? ['/login/admin','/login/check']:['/login/user','/login/check'],
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
		return $this->respond(
			[
				'forms'=>$form,
				'login_variable'=>[
					['name'=>'Админка','mode'=>'admin'],
					['name'=>'Логи','mode'=>'logs']
				]
			],200);
    }
	
	/**
	 * @return \CodeIgniter\HTTP\Response Проверка пары логин пароль для входа в админку
	 */
	public function adminLogPas()
	{
		$login = $this->request->getVar('login');
		$password = $this->request->getVar('password');
		$Admins = model(AdminModel::class);
		$admin = $Admins->getAdminByLogin($login);
		if(!isset($admin['admin_password'])||!password_verify($password,$admin['admin_password'])){
			return $this->respond(['errors'=>['Не верное имя пользователя или пароль'],'data'=>null],200);
		}
		if(is_null($admin['user_telegram_id'])){
			$this->session->set('Login',true);
			$this->session->set('admin',$admin);
			return $this->respond(['errors'=>['Deployment mode'],'data'=>'/CreateAdmin'],200);
			
		}
		else{
			$Redis = Redis::getInstance();
			$key = rand(1111,9999);
			$messageID = TelegramAPI::sendMessage($admin['user_telegram_id'],'Код подтверждения входа в админку'.PHP_EOL.'<b>'.$key.'</b>',false);
			$Redis->set('verify_code_'.$admin['user_telegram_id'],json_encode(['key'=>$key,'message_id'=>$messageID['message_id'],'target'=>'admin']),60);
			
		}
		return $this->respond(['errors'=>null,'data'=>$admin],200);
	}
	
	public function userLogPas()
	{
		$login = $this->request->getVar('login');
		$password = $this->request->getVar('password');
		$Users = model(UserModel::class);
		$user = $Users->getUserByLogin($login);
		if(!isset($user['user_password'])||!password_verify($password,$user['user_password'])){
			return $this->respond(['errors'=>['Не верное имя пользователя или пароль'],'data'=>null],200);
		}
		
		else{
			$Redis = Redis::getInstance();
			$key = rand(1111,9999);
			$messageID = TelegramAPI::sendMessage($user['user_telegram_id'],'Код подтверждения входа к логам'.PHP_EOL.'<b>'.$key.'</b>',false);
			$Redis->set('verify_code_'.$user['user_telegram_id'],json_encode(['key'=>$key,'message_id'=>$messageID['message_id'],'target'=>'user']),60);
			
		}
		return $this->respond(['errors'=>null,'data'=>$user],200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Проверка кода подтверждения
	 */
	public function checkCode()
	{
		$telegram_id = $this->request->getVar('telegram');
		$code = $this->request->getVar('code');
		$Redis = Redis::getInstance();
		if(!$Redis->exists('verify_code_'.$telegram_id))
		{
			return $this->respond(['errors'=>['Время на ожидание кода истекло авторизируйтесь заного'],'data'=>null],200);
		}
		$verify = json_decode($Redis->get('verify_code_'.$telegram_id),true);
		$Redis->del('verify_code_'.$telegram_id);
		if((int)$code !==(int)$verify['key']){
			return $this->respond(['errors'=>['Код подтверждения введен неверно '],'data'=>null],200);
		}
		TelegramAPI::deleteMessage($telegram_id,$verify['message_id']);
		return ($verify['target']=='admin')?$this->respond(['errors'=>null,'data'=>'/admin']):$this->respond(['errors'=>null,'data'=>'/user']);
	}
	
	public function generateRegisterLinkForUser()
	{
		//todo генерация дип линка для создания клиентских подключений
	}
	
	public function FirstAdminCreateLink()
	{
		//todo генерация дип линка для создания первого админа
	}
	
	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 * Выход из сервиса
	 */
	public function logOut()
	{
		$this->session->destroy();
		return  redirect('/login');
	}
}
