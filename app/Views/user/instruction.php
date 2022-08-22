<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Пример пользовательского класса</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet"
	      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>
<body>
	<div class="container bg-secondary">
		<code class="text-dark ">
			class NactaLogger <br/>
			{<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $server = "https://<?=$_SERVER['SERVER_NAME']?>/";<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $Secret = '<?=$secret?>'; // Строка секретного ключа проекта<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $type = 'single'; // Не менять<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $block_id = false; // Не менять<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $title = false; // Не менять<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $part = 'body'; // Не менять<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $data = []; // Не менять<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $recipients = []; // Не менять<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $alert_mode = 'hide'; // Режим оповещения по умолчанию<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $status = 'normal'; // Не менять<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;private $timer_check = 60; // Время до алерта о том что нет продолжения или закрывающего лога<br/>
			<br/>
			<section class="text-white">
			/**<br/>
			* @param array $config ['secret'=>'Секретный ключ проекта','recipients'=>['admin','user','test'],'alert_mode'=>'hide' || 'silent' || 'alarm', 'timer_check'=>30]<br/>
			* @return boolean Установит секретный ключ, массив пользователей для оповещения, режим оповещения, время до наступления оповещения о падении<br/>
			*/<br/>
			</section>
			private function Config(array $config ):bool<br/>
			{<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$this->Secret       = (isset($config['secret']))?$config['secret']:$this->Secret;<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$this->recipients   = (isset($sonfig['recipients']))?$sonfig['recipients']:['All'];<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$this->alert_mode   = (isset($config['alert_mode']))?$config['alert_mode']:'hide';<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$this->timer_check  = (isset($config['timer_check']))?$config['timer_check']:60;<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;return true;<br/>
			}<br/>
			<br/>
			private function SendRequest(array $Data)<br/>
			{<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$url = $this->server.'api/v1/log/'.$this->Secret;<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$post_data = [ // поля нашего запроса<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;'log' =>json_encode([<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'title'=>$this->title,<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'type'=>$this->type,<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'block_id'=>$this->block_id,<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'part'=>$this->part,<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'data'=>$Data,<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'recipients'=>$this->recipients,<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'alert_mode'=>$this->alert_mode,<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'status'=>$this->status,<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'timer_check'=>$this->timer_check<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],256)<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;];<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$post_data = http_build_query($post_data);<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$curl = curl_init();<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;curl_setopt($curl, CURLOPT_VERBOSE, 1);<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;curl_setopt($curl, CURLOPT_URL, $url);<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;curl_setopt($curl, CURLOPT_POST, true);<br/>
			try {<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;$result = curl_exec($curl);<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;if(!$result){throw new Exception('Ошибка отправки.');}<br/>
			}<br/>
			catch (Exception $e){<br/>
			}<br/>
			return ($result['result'])?json_decode($result['result'],true):$e->getMessage().PHP_EOL;<br/>
			}<br/>
			<br/>
			<br/>
			public function sendLog(array $Data, $config = null)<br/>
			{<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;if(is_array($config)){$this->Config($config);}<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;return $this->SendRequest($Data);<br/>
			}<br/>
			<br/>
			public function startBlock(array $Data, $config = null)<br/>
			{<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;if(is_array($config)){$this->Config($config);}<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->type='block';<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->part='start';<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$result = $this->SendRequest($Data);<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->block_id = (isset($result['block_id']));<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;return $result;<br/>
			<br/>
			}<br/>
			<br/>
			public function finishBlock(arra $Data, $config = null)<br/>
			{<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;if(is_array($config)){$this->Config($config);}<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->type='block';<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->part='finish';<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$result = $this->SendRequest($Data);<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->type='single';<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->part='body';<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->block_id = false;<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;return $result;<br/>
			}<br/>
}<br/>
		</code>
	</div>
	<div class="container">
		<p>Пример вызова:</p>
		<p>	$NLog = new NactaLogger();	</p>
		<p>	$NLog->Config(['recipients'=>['admin','user','test'],'alert_mode'=>'alarm', 'timer_check'=>10]);</p>
		<h3 class="text-center"> Отправка одиночного лога</h3>
		<p>	$NLog->sendLog(['key'=>$val,'nKey'=>['nnkey'=>'nnVal']]);</p>
		<h3 class="text-center"> Отправка одиночного лога c изменённым конфигом</h3>
		<p>	$NLog->sendLog(['key'=>$val,'nKey'=>['nnkey'=>'nnVal']],['title'=>'Новый тайтл']);</p>
		<hr/>
		<h3 class="text-center"> Начало цепочки логов</h3>
		<p>	$NLog->startBlock(['key'=>$val,'nKey'=>['nnkey'=>'nnVal']],['title'=>'Новый тайтл']);</p>
		<h3 class="text-center"> Продолжение цепочки логов</h3>
		<p>	$NLog->sendLog(['key'=>$val,'nKey'=>['nnkey'=>'nnVal']],['title'=>'Новый тайтл']);</p>
		<p>	$NLog->sendLog(['key'=>$val,'nKey'=>['nnkey'=>'nnVal']],['title'=>'Новый тайтл']);</p>
		<h3 class="text-center"> Завершение цепочки логов</h3>
		<p>	$NLog->finishBlock(['key'=>$val,'nKey'=>['nnkey'=>'nnVal']],['title'=>'Новый тайтл']);</p>
	</div>
</body>