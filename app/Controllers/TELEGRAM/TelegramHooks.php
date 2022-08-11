<?php

namespace App\Controllers\TELEGRAM;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\UserModel;

class TelegramHooks extends BaseController
{
    public function index()
    {
	    $data = file_get_contents('php://input');
	    $data = json_decode($data, true);
		$file = fopen('hook.txt','w');
		if(!isset($data['message']['from']['id'])||!isset($data['message']['text'])){
			
			die();
		}
		$sender = $data['message']['from']['id'];
		$text = $data['message']['text'];
	    $findme   = '/start';
	    $pos = strpos($text, $findme);
	    if ($pos === false) {
		   
		    die();
	    }
		else // Если deepLink удовлетворяет условиям
		{
			
		   $text = trim(str_replace('/start','',$text));
		   $text = base64_decode($text);
			
		   if($text =='create_admin'){ //Регистрация первого админа (добавление его пользователю Телеграм ID)
			  
			   $Users = model(UserModel::class);
			   $pretendent = $Users->find(1);
			   
			   if(is_null($pretendent['user_telegram_id'])) { // Проверяем что у пользователя 1 еще нет телеграмID
				   $Users->completeRegisterUser(1, (int)$sender);
			   }
		   }
		   $user_array = explode('create_user/id=',$text);
		   if(isset($user_array[1])){ // Если нам передали команду создать пользователя и сообщили его ID
			   $Users = model(UserModel::class);
			   $pretendent = $Users->find((int)$user_array[1]);
			   if(is_null($pretendent['user_telegram_id'])) { // Проверяем что у пользователя еще нет телеграмID
				   $Users->completeRegisterUser((int)$user_array[1], (int)$sender);
			   }
		   }
	    }
		
    }
}
