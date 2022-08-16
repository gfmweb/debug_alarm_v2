<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Logs extends Seeder
{
    public function run()
    {
	    $data = [
		    [
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256)
		    ],
		    [
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256)
		    ],
		    [
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256),
			    json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256)
		    ]];
	
	    $query = 'INSERT INTO logs(log_project_id, log_structured_data) VALUES ';
	    for($i=1; $i < 2000; $i++)
	    {
		    $project_id = rand(0,2);
			$data_index = $project_id;
		    $project_value = rand(0,4);
		    $project_id++;
		    $query.=' ('.$project_id.',"[]"),';
	    }
	    $query = substr($query,0,-1);
	    $this->db->query($query);
		$query = 'UPDATE logs SET log_structured_data = '.json_encode(['Что-то'=>'Some_time','data_array'=>['First'=>'Иван','Last'=>'Иванов','DDI'=>['seria'=>'0113', 'number'=>'947029']]],256).' WHERE 1';
	    $this->db->query($query);
    }
}
