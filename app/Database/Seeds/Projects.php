<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Projects extends Seeder
{
	private $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	private function getSecret():string
	{
		$secret = '';
		$max  = strlen($this->characters)-1;
		for($i = 0; $i < 16; $i++)
		{
			$secret.=$this->characters[rand(0,$max)];
		}
		$secret.= time();
		return $secret;
	}
	
    public function run()
    {
		$Redis = new \Redis;
		$Redis->connect('127.0.0.1',6379);
	    $names = ['СМС-Конвеер','Нарезчик анкет','ВП'];
	    $query = 'INSERT INTO projects (`project_name`, `project_secret`) VALUES ';
	    foreach ($names as $name)
	    {
		    $query.=' ("'.$name.'","'.$this->getSecret().'"),';
	    }
	    $query = substr($query,0,-1);
	    $this->db->query($query);
		
    }
}
