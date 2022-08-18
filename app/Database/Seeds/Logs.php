<?php

namespace App\Database\Seeds;

use App\Controllers\Redis;
use App\Models\LogModel;
use CodeIgniter\Database\Seeder;

class Logs extends Seeder
{
    public function run()
    {
	    $rowData = [
		    [
			    [
				    'type'=>'SingleRow',
				    'Owner'=>'Автонарезчик анкет',
				    'project_id'=>2,
				    'data'=>[
					    '{"action":{"Оптимальный 3":{"start":"21.04.22","end":"","exceptions":{"standart":"Стандарт Плюс","action":"Оптимальный Плюс","specaction":"Возможно всё Плюс"}}},"specaction":{"ВД 2%":{"start":"21.04.22","end":"","exceptions":{"standart":"","action":"","specaction":""}}}}',
					    '{"specaction":{"Лёгкий 2":{"start":"22.04.22","end":"","exceptions":{"standart":"","action":"","specaction":""}}}}',
					    '{"specaction":{"Лёгкий 3":{"start":"22.04.22","end":"","exceptions":{"standart":"","action":"","specaction":""}}}}',
					    '{"action":{"Удобный":{"start":"22.04.22","end":"","exceptions":{"standart":"","action":"","specaction":""}},"Удобный 2":{"start":"04.04.22","end":"21.04.22","exceptions":{"standart":"","action":"","specaction":""}}},"specaction":{"Лёгкий 2":{"start":"22.04.22","end":"","exceptions":{"standart":"","action":"","specaction":""}}}}',
				
				    ]
			    ],
			    [
				    'type'=>'BlockData',
				    'Owner'=>'Автонарезчик анкет',
				    'project_id'=>2,
				    'data'=>[
					    json_encode(['initiator'=>'Партнёр префикс -Х999','order'=>['VP_1'=>['start'=>'18.08.2022','finish'=>null],'VP_2'=>['start'=>null,'finish'=>'18.08.2022']]],256),
					    json_encode(['reflection'=>'Создана работа для Воркера'],256),
					    json_encode(['reflection'=>'Worker запущен'],256),
					    json_encode(['worker_result'=>['Лог воркера'=>['start'=>'Парнёр -Х999 не найден',['Запрос СОАП','Создана новая анкета','Cтруктура новой анкеты'=>['VP_1','VP_2']]]]],256),
					    json_encode(['reflection'=>'Worker закончил работу'])
				    ]
			    ]
		    ],
		    [
			    [
				    'type'=>'SingleRow',
				    'Owner'=>'SMS-Шлюз',
				    'project_id'=>1,
				    'data'=>[
					    '{"status":"Постановка задачи","phone":"+79281224202","text":"N_NKZ: SMS:4832 ZNZ:7503 IGP:6924 ID:95799 https://lk.ncwltd.ru/","params":"","time":"18/08/2022 14:25:46","ProviderResponse":"750756da-57bb-4f38-a63f-dbd2e10c875a"}',
					    '{"status":"Постановка задачи","phone":"+79782068886","text":"N_APT: LSP:5086 OPD:7074 BKR:7001 ID:62293 https://lk.aptnn.ru/","params":"","time":"18/08/2022 13:14:22","ProviderResponse":"dc82af05-935c-42ac-9117-6cd636559a36"}',
					    '{"status":"Постановка задачи","phone":"+79519153736","text":"Тест СМС","params":null,"time":"24/06/2022 18:44:43","ProviderResponse":"6846819174"}',
					    '{"status":"Постановка задачи","phone":"+79177568447","text":"Проверка каскада","params":"null","time":"25/06/2022 18:10:28","ProviderResponse":"6847130371"}',
				
				    ]
			    ],
			    [
				    'type'=>'BlockData',
				    'Owner'=>'SMS-Шлюз',
				    'project_id'=>2,
				    'data'=>[
					    json_encode(['initiator'=>'Запуск каскада -ISMS','order'=>['phone'=>'+79177568447','text'=>'SOME_TEXT_4_EXAMPLE'],'partner'=>'Some Partner Name or Prefix'],256),
					    json_encode(['reflection'=>'Создана работа для Первой очереди'],256),
					    json_encode(['reflection'=>'Sender запущен'],256),
					    json_encode(['worker_result'=>['SENDER_LOG'=>['start'=>'Данные переданы во внешний сервис',['Ответ сервиса'=>'OK DELIVERED']]]],256),
					    json_encode(['reflection'=>'Каскад закончил работу успешно на итерации № 1'])
				    ]
			    ]
		    ],
		    [
			    [
				    'type'=>'SingleRow',
				    'Owner'=>'Конструктор продаж',
				    'project_id'=>3,
				    'data'=>[
					    '{"status":"Постановка задачи","phone":"+79281224202","text":"N_NKZ: SMS:4832 ZNZ:7503 IGP:6924 ID:95799 https://lk.ncwltd.ru/","params":"","time":"18/08/2022 14:25:46","ProviderResponse":"750756da-57bb-4f38-a63f-dbd2e10c875a"}',
					    '{"status":"Постановка задачи","phone":"+79782068886","text":"N_APT: LSP:5086 OPD:7074 BKR:7001 ID:62293 https://lk.aptnn.ru/","params":"","time":"18/08/2022 13:14:22","ProviderResponse":"dc82af05-935c-42ac-9117-6cd636559a36"}',
					    '{"status":"Постановка задачи","phone":"+79519153736","text":"Тест СМС","params":null,"time":"24/06/2022 18:44:43","ProviderResponse":"6846819174"}',
					    '{"status":"Постановка задачи","phone":"+79177568447","text":"Проверка каскада","params":"null","time":"25/06/2022 18:10:28","ProviderResponse":"6847130371"}',
				
				    ]
			    ],
			    [
				    'type'=>'BlockData',
				    'Owner'=>'Конструктор продаж',
				    'project_id'=>3,
				    'data'=>[
					    json_encode(['initiator'=>'Запуск каскада -ISMS','order'=>['phone'=>'+79177568447','text'=>'SOME_TEXT_4_EXAMPLE'],'partner'=>'Some Partner Name or Prefix'],256),
					    json_encode(['reflection'=>'Создана работа для Первой очереди'],256),
					    json_encode(['reflection'=>'Sender запущен'],256),
					    json_encode(['worker_result'=>['SENDER_LOG'=>['start'=>'Данные переданы во внешний сервис',['Ответ сервиса'=>'OK DELIVERED']]]],256),
					    json_encode(['reflection'=>'Каскад закончил работу успешно на итерации № 1'],256)
				    ]
			    ]
		    ]
	    ];
	    $BigData = [];
	
	    for ($i=0; $i<1000; $i++)
	    {
		    $dataSection = rand(0,2);
		    $projectSection = rand(0,1);
		    $log_project_id = $rowData[$dataSection][$projectSection]['project_id'];
		
		    if($rowData[$dataSection][$projectSection]['type']=='BlockData'){
			    for($t=0,$tMax=count($rowData[$dataSection][$projectSection]['data']); $t < $tMax; $t++)
			    {
				    $arr['log_project_id']=$log_project_id;
				    $arr['log_structured_data']=$rowData[$dataSection][$projectSection]['data'][$t];
				    $arr['log_status']='normal';
				    $title_extract_full = json_decode($rowData[$dataSection][$projectSection]['data'][0],true);
				    $title = $title_extract_full['initiator'];
				    $arr['log_title'] = 'Работа по '.$title.' Сервиса '.$rowData[$dataSection][$projectSection]['Owner'];
				    if($t==0){$arr['log_part']='start'; }
				    elseif($t==$tMax-1){$arr['log_part']='finish';}
				    else{$arr['log_part']='body';}
				    array_push($BigData,$arr);
				
			    }
		    }
		    else{
			    $rowNumber = rand(0,3);
			    $arr['log_project_id']=$log_project_id;
			    $arr['log_structured_data']=$rowData[$dataSection][$projectSection]['data'][$rowNumber];
			    $arr['log_status']='normal';
			    $arr['log_title'] = 'Одиночная запись сервиса '.$rowData[$dataSection][$projectSection]['Owner'];
			    $arr['log_part']='body';
			    array_push($BigData,$arr);
			
		    }
	    }
	    $Logs = model(LogModel::class);
	    $Logs->insertBatch($BigData);
		
		
		
    }
}
