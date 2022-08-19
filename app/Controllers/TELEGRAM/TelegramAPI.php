<?php

namespace App\Controllers\TELEGRAM;

use App\Controllers\BaseController;

/**
 * Класс транспорта отправки запросов к телеграм
 */
class TelegramAPI extends BaseController
{
	/**
	 * @param int $recipient_telegram_id ID пользователя
	 * @param string $text Форматированный текст
	 * @param bool $disable_notification Тихий (без уведомления) режим
	 * @return array Результат отправки сообщения
	 */
	public static function sendMessage(int $recipient_telegram_id, string $text, bool $disable_notification = true):array
	{
		$options = ['baseURI' => 'https://api.telegram.org/bot' . TELEGRAM . '/sendMessage', 'timeout' => 3,];
		$curl = \Config\Services::curlrequest($options);
		$result = $curl->request('POST', $options['baseURI'], ['form_params' => ['chat_id' => $recipient_telegram_id, 'text' => $text, 'parse_mode' => 'HTML', 'disable_web_page_preview' => true, 'disable_notification' => $disable_notification, 'protect_content' => true]]);
		$response = $result->getBody();
		$data_response = json_decode($response, true);
		return (isset($data_response['ok']) && $data_response['ok'] == 1) ? ['status' => 'ok', 'message_id' => $data_response['result']['message_id']] : ['status' => 'fail','message_id'=>0];
	}
	
	/**
	 * @param int $telegram_user_id ID пользователя
	 * @param int $message_id ID сообщения
	 * @return bool  Удаляет сообщение у пользователя
	 */
	public static function deleteMessage(int $telegram_user_id, int $message_id):bool
	{
			$options = [
			'baseURI' => 'https://api.telegram.org/bot'.TELEGRAM.'/deleteMessage' ,
			'timeout' => 3,
			];
			$curl = \Config\Services::curlrequest($options);
			$curl->request('POST',$options['baseURI'],
				[
					'form_params' =>[
						'chat_id'=>$telegram_user_id,
						'message_id'=>$message_id,
						]
				]);
			return true;
	}
}
