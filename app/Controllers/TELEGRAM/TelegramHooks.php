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
				   $Users->update(1, ['user_telegram_id'=>(int)$sender]);
				   TelegramAPI::sendMessage((int)$sender,'<b>Регистрация почти завершена'.PHP_EOL.'Зайдите в админку</b>',false);
			   }
		   }
		   $user_array = explode('create_user/id=',$text);
		   if(isset($user_array[1])){ // Если нам передали команду создать пользователя и сообщили его ID
			   $Users = model(UserModel::class);
			   $pretendent = $Users->find((int)$user_array[1]);
			   if(is_null($pretendent['user_telegram_id'])) { // Проверяем что у пользователя еще нет телеграмID
				   $Users->update((int)$user_array[1], ['user_telegram_id'=>(int)$sender]);
				   TelegramAPI::sendMessage((int)$sender,'<b>Регистрация завершена'.PHP_EOL.'Зайдите в лк на сайте</b>',false);
			   }
		   }
	    }
    }
}
