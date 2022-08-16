<?php
	
	use Workerman\Lib\Timer;
	use Workerman\Worker;
	
	require_once __DIR__ . '/vendor/autoload.php';
	$connections = []; // сюда будем складывать все подключения
// SSL context.
	$context = array(
		'ssl' => array(
			'local_cert'  => '/home/admin/conf/web/ssl.debug.gfmweb.ru.pem',
			'local_pk'    => '/home/admin/conf/web/ssl.debug.gfmweb.ru.key',
			'verify_peer' => false,
		)
	);

// Create a Websocket server with ssl context.
	$worker = new Worker('websocket://0.0.0.0:27800', $context);

// Enable SSL. WebSocket+SSL means that Secure WebSocket (wss://).
// The similar approaches for Https etc.
	$worker->transport = 'ssl';
	
	$worker->onWorkerStart = function($worker) use (&$connections)
	{
		$interval = 1; // пингуем каждую  секунду
		Timer::add($interval, function() use(&$connections) {
			
			
			foreach ($connections as $c) {
				// Если ответ не пришел 3 раза, то удаляем соединение из списка
				// и оповещаем всех участников об "отвалившемся" пользователе
				if ($c->pingWithoutResponseCount >= 3) {
					unset($connections[$c->id]);
					
					$messageData = [
						'action' => 'ConnectionLost',
						'userId' => $c->id,
						'userName' => $c->userName,
						'gender' => $c->gender,
						'userColor' => $c->userColor
					];
					$message = json_encode($messageData);
									$c->destroy(); // уничтожаем соединение
					
					foreach ($connections as $c) {
						$c->send($message);
					}
					
				} else {
					foreach ($connections as $c) {
						$c->send('{"action":"Ping"}');
						$c->pingWithoutResponseCount++; // увеличиваем счетчик пингов
					}
					
					
					
				}
			}
			
		});
	};
	
	$worker->onConnect = function($connection) use(&$connections)
	{
		// Эта функция выполняется при подключении пользователя к WebSocket-серверу
		$connection->onWebSocketConnect = function($connection) use (&$connections)
		{
			// Достаём имя пользователя, если оно было указано
			if (isset($_GET['token'])) {
				$originalUserName = trim($_GET['token']);
			}
			else {
				
				$originalUserName = 'Коннект без токена ?token=null';
			}
			
			
			if (isset($_GET['gender'])) {
				$gender = (int) $_GET['gender'];
			}
			else {
				$gender = 0;
			}
			
			if ($gender != 0 && $gender != 1 && $gender != 2)
				$gender = 0;
			
			
			$userName = $originalUserName;
			
			$num = 2;
			do {
				$duplicate = false;
				foreach ($connections as $c) {
					if ($c->userName == $userName) {
						$userName = "$originalUserName ($num)";
						$num++;
						$duplicate = true;
						break;
					}
				}
			}
			while($duplicate);
			
			// Добавляем соединение в список
			$connection->userName = $userName;
			$connection->gender = $gender;
			$connection->userColor = '';
			$connection->pingWithoutResponseCount = 0; // счетчик безответных пингов
			
			$connections[$connection->id] = $connection;
			
			// Собираем список всех пользователей
			$users = [];
			foreach ($connections as $c) {
				$users[] = [
					'userId' => $c->id,
					'userName' => $c->userName,
					'gender' => $c->gender,
					'userColor' => $c->userColor
				];
			}
			
			// Отправляем пользователю данные авторизации
			$messageData = [
				'action' => 'Authorized',
				'userId' => $connection->id,
				'userName' => $connection->userName,
				'gender' => $connection->gender,
				'userColor' => $connection->userColor,
				'users' => $users
			];
			
			
			
			$connection->send(json_encode($messageData));
			
			// Оповещаем всех пользователей о новом участнике в чате
			$messageData = [
				'action' => 'Connected',
				'userId' => $connection->id,
				'userName' => $connection->userName,
				'gender' => $connection->gender,
				'userColor' => $connection->userColor
			];
			$message = json_encode($messageData);
			
			foreach ($connections as $c) {
				$c->send($message);
			}
		};
	};
	
	$worker->onClose = function($connection) use(&$connections)
	{
		// Эта функция выполняется при закрытии соединения
		if (!isset($connections[$connection->id])) {
			return;
		}
		
		// Удаляем соединение из списка
		$redis = new \Redis();
		$redis->connect('127.0.0.1',6379);
		$redis->select(6);
		$redis->del($connection->userName);
		
		
		unset($connections[$connection->id]);
		
		
		// Оповещаем всех пользователей о выходе участника из чата
		$messageData = [
			'action' => 'Disconnected',
			'userId' => $connection->id,
			'userName' => $connection->userName,
			'gender' => $connection->gender,
			'userColor' => $connection->userColor
		];
		$message = json_encode($messageData);
		
		
		
		foreach ($connections as $c) {
			$c->send($message);
		}
	};
	
	$worker->onMessage = function($connection, $message) use (&$connections)
	{
		$messageData = json_decode($message, true);
		$toUserId = isset($messageData['toUserId']) ? (int) $messageData['toUserId'] : 0;
		$action = isset($messageData['action']) ? $messageData['action'] : '';
		
		if ($action == 'Pong') {
			// При получении сообщения "Pong", обнуляем счетчик пингов
			$connection->pingWithoutResponseCount = 0;
			
		}
		
		else {
			// Дополняем сообщение данными об отправителе
			$messageData['userId'] = $connection->id;
			$messageData['userName'] = $connection->userName;
			$messageData['gender'] = $connection->gender;
			$messageData['userColor'] = $connection->userColor;
			
			// Преобразуем специальные символы в HTML-сущности в тексте сообщения
			$messageData['text'] = htmlspecialchars($messageData['text']);
			// Заменяем текст заключенный в фигурные скобки на жирный
			$messageData['text'] = preg_replace('/\{(.*)\}/u', '<b>\\1</b>', $messageData['text']);
			
			if ($toUserId == 0) {
				// Отправляем сообщение всем пользователям
				$messageData['action'] = 'PublicMessage';
				foreach ($connections as $c) {
					$c->send(json_encode($messageData));
				}
				foreach ($connections as $admin){
					if (($admin->userName=='admin_debugMode')&&($admin->userName!==$c->userName)){
						$message = ['action'=>'DEBUG_INFO','data'=>'MESSAGE FROM: '.$c->userName.' '.$messageData];
						$message = json_encode($message);
						$admin->send($message);
						
					}
				}
			}
			else {
				$messageData['action'] = 'PrivateMessage';
				if (isset($connections[$toUserId])) {
					// Отправляем приватное сообщение указанному пользователю
					$connections[$toUserId]->send(json_encode($messageData));
					// и отправителю
					$connections->send(json_encode($messageData));
				}
				else {
					$messageData['text'] = 'Не удалось отправить сообщение выбранному пользователю';
					$connection->send(json_encode($messageData));
				}
			}
		}
	};
	
	Worker::runAll();
