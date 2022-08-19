<?php

namespace App\Controllers\TELEGRAM;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

/**
 * Обработчик для приложения WebApp Telegram
 */
class TelegramView extends BaseController
{
	use ResponseTrait;
	
	/**
	 * @return string Основной вид
	 */
    public function index()
    {      return view('user/user_index_telegram');   }
	
	
	/**
	 * @return \CodeIgniter\HTTP\Response Метод авторизации по телеграм_ID
	 */
	public function login()
	{
		$telegram_id = $this->request->getVar('id');
		$UsersModel=model(UserModel::class);
		$current_user = $UsersModel->where('user_telegram_id',$telegram_id)->first();
		if(isset($current_user['user_id'])) {
			$this->session->set('user', $current_user);
			return $this->respond("ok", 200);
		}
		else{
			return $this->respond("close", 200);
		}
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Основное меню телеграм приложения
	 */
	public function getMainMenu(){
		$user = $this->session->get('user');
		return $this->respond(
			[
				'MenuButtons'=>
					[
						['name'=>'<i class="fa-solid fa-clock"></i>','action'=>'real'],
						['name'=>'<i class="fa-solid fa-database"></i>','action'=>'prepareQuery']
						
					],
				'MenuHeaderText'=>'Основные действия ',
				'PathToServer'=>$_SERVER['SERVER_NAME'],
				'UserIdentity'=>$user['user_id']
			],200);
	}
}
