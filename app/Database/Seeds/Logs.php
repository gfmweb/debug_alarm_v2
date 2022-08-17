<?php

namespace App\Database\Seeds;

use App\Controllers\Redis;
use CodeIgniter\Database\Seeder;

class Logs extends Seeder
{
    public function run()
    {
	    $data = [
		    [
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79786867888\",\"text\":\"N_APT: LSP:4378 OPD:1453 BKR:8315 ID:20198 https:\\\/\\\/lk.aptnn.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 21:39:56\",\"ProviderResponse\":\"627e1ec1-0b1f-4c02-8cc7-b735592ad47c\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79877055199\",\"text\":\"N_NKR: LSP:3562 OPD:9692 BKR:8935 ID:10187 https:\\\/\\\/lk.ncrltd.ru\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 17:13:09\",\"ProviderResponse\":\"aee8f117-b429-4e2e-b171-b6fbeb60747f\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79898363483\",\"text\":\"N_KAG: TCK:7754 KCT:2215 ID:83448 https:\\\/\\\/lk.korolevnn.ru\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 16:39:42\",\"ProviderResponse\":\"3fb5ea7e-3bfc-4927-9e1f-31307c10b723\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79780184147\",\"text\":\"N_APT: ZNZ:0395 IGP:4370 ID:19533 https:\\\/\\\/lk.aptnn.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 16:00:24\",\"ProviderResponse\":\"2387a3fb-9525-40a9-8f7f-8a4013935261\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79018067467\",\"text\":\"N_NKZ: LSP:9312 OPD:0214 BKR:7104 ID:21017 https:\\\/\\\/lk.ncwltd.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 15:48:25\",\"ProviderResponse\":\"f9a22ecb-5c4b-4219-88ae-a23d8c7f9e3f\"}"'
		    ],
		    [
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79786867888\",\"text\":\"N_APT: LSP:4378 OPD:1453 BKR:8315 ID:20198 https:\\\/\\\/lk.aptnn.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 21:39:56\",\"ProviderResponse\":\"627e1ec1-0b1f-4c02-8cc7-b735592ad47c\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79877055199\",\"text\":\"N_NKR: LSP:3562 OPD:9692 BKR:8935 ID:10187 https:\\\/\\\/lk.ncrltd.ru\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 17:13:09\",\"ProviderResponse\":\"aee8f117-b429-4e2e-b171-b6fbeb60747f\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79898363483\",\"text\":\"N_KAG: TCK:7754 KCT:2215 ID:83448 https:\\\/\\\/lk.korolevnn.ru\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 16:39:42\",\"ProviderResponse\":\"3fb5ea7e-3bfc-4927-9e1f-31307c10b723\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79780184147\",\"text\":\"N_APT: ZNZ:0395 IGP:4370 ID:19533 https:\\\/\\\/lk.aptnn.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 16:00:24\",\"ProviderResponse\":\"2387a3fb-9525-40a9-8f7f-8a4013935261\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79018067467\",\"text\":\"N_NKZ: LSP:9312 OPD:0214 BKR:7104 ID:21017 https:\\\/\\\/lk.ncwltd.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 15:48:25\",\"ProviderResponse\":\"f9a22ecb-5c4b-4219-88ae-a23d8c7f9e3f\"}"'
		    ],
		    [
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79786867888\",\"text\":\"N_APT: LSP:4378 OPD:1453 BKR:8315 ID:20198 https:\\\/\\\/lk.aptnn.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 21:39:56\",\"ProviderResponse\":\"627e1ec1-0b1f-4c02-8cc7-b735592ad47c\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79877055199\",\"text\":\"N_NKR: LSP:3562 OPD:9692 BKR:8935 ID:10187 https:\\\/\\\/lk.ncrltd.ru\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 17:13:09\",\"ProviderResponse\":\"aee8f117-b429-4e2e-b171-b6fbeb60747f\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79898363483\",\"text\":\"N_KAG: TCK:7754 KCT:2215 ID:83448 https:\\\/\\\/lk.korolevnn.ru\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 16:39:42\",\"ProviderResponse\":\"3fb5ea7e-3bfc-4927-9e1f-31307c10b723\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79780184147\",\"text\":\"N_APT: ZNZ:0395 IGP:4370 ID:19533 https:\\\/\\\/lk.aptnn.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 16:00:24\",\"ProviderResponse\":\"2387a3fb-9525-40a9-8f7f-8a4013935261\"}"',
			    '"{\"status\":\"Постановка задачи\",\"phone\":\"+79018067467\",\"text\":\"N_NKZ: LSP:9312 OPD:0214 BKR:7104 ID:21017 https:\\\/\\\/lk.ncwltd.ru\\\/\",\"params\":\"\",\"time\":\"16\\\/08\\\/2022 15:48:25\",\"ProviderResponse\":\"f9a22ecb-5c4b-4219-88ae-a23d8c7f9e3f\"}"'
			    
		    ]];
	
	    $query = 'INSERT INTO logs(log_project_id, log_structured_data) VALUES ';
	    for($i=1; $i <3000; $i++)
	    {
		    $project_id = rand(0,2);
			$data_index = $project_id;
		    $project_value = rand(0,4);
		    $project_id++;
		    $query.=' ('.$project_id.','.$data[$data_index][$project_value].'),';
	    }
	    $query = substr($query,0,-1);
	    $this->db->query($query);
		
		$Redis = new Redis();
		$Redis::updateLogList();
    }
}
