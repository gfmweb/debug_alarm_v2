<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'logs';
    protected $primaryKey       = 'log_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['log_project_id','log_structured_data','log_title','log_part','log_status'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
	
	/**
	 * CRUD BLOCK READ
	 */
	
	/**
	 * @param int $logID ID интересующего ЛОГА
	 * @return array Полная информация по записи лога
	 */
	public function getLogByID(int $logID):array
	{
		return $this->select(['logs.log_id',
			'logs.log_structured_data',
			'logs.log_title',
			'logs.log_part',
			'logs.log_status',
			'logs.created_at',
			'projects.project_name'])
			->join('projects','projects.project_id = logs.log_project_id','LEFT')->where('logs.log_id',$logID)->first();
	}
	
	
	/**
	 * @param array $query Массив запроса
	 * @return array Краткая информация по логам удовлетворяющим условиям поиска
	 */
	public function getLogsByQuery(array $Request):array
	{
		if($Request['start']&&$Request['finish']){
			if($Request['query']){
			 return	$this->select(['logs.log_id', 'logs.log_structured_data', 'logs.log_title','logs.log_part','logs.log_status','logs.created_at', 'projects.project_name'])
					->join('projects','logs.log_project_id = projects.project_id')
					->whereIn('logs.log_project_id',$Request['projects'])
					->where('logs.created_at BETWEEN "'.$Request['start'].'" AND "'.$Request['finish'].'"')
					->like('logs.log_structured_data',$Request['query'],'both')
					->limit(100)
					->orderBy('logs.log_id','DESC')
					->find();
			}
			else{
				return	$this->select(['logs.log_id', 'logs.log_structured_data', 'logs.log_title','logs.log_part','logs.log_status','logs.created_at', 'projects.project_name'])
					->join('projects','logs.log_project_id = projects.project_id')
					->whereIn('logs.log_project_id',$Request['projects'])
					->where('logs.created_at BETWEEN "'.$Request['start'].'" AND "'.$Request['finish'].'"')
					->limit(100)
					->orderBy('logs.log_id','DESC')
					->find();
			}
		}
		if($Request['start']&&!$Request['finish']){
			if($Request['query']){
				return	$this->select(['logs.log_id', 'logs.log_structured_data', 'logs.log_title','logs.log_part','logs.log_status','logs.created_at', 'projects.project_name'])
					->join('projects','logs.log_project_id = projects.project_id')
					->whereIn('logs.log_project_id',$Request['projects'])
					->where('logs.created_at >= "'.$Request['start'].'"')
					->like('logs.log_structured_data',$Request['query'],'both')
					->limit(100)
					->orderBy('logs.log_id','DESC')
					->find();
			}
			else{
				return	$this->select(['logs.log_id', 'logs.log_structured_data', 'logs.log_title','logs.log_part','logs.log_status','logs.created_at', 'projects.project_name'])
					->join('projects','logs.log_project_id = projects.project_id')
					->whereIn('logs.log_project_id',$Request['projects'])
					->where('logs.created_at >= "'.$Request['start'].'"')
					->limit(100)
					->orderBy('logs.log_id','DESC')
					->find();
			}
		}
		if(!$Request['start']&&$Request['finish']){
			if($Request['query']){
				return	$this->select(['logs.log_id', 'logs.log_structured_data', 'logs.log_title','logs.log_part','logs.log_status','logs.created_at', 'projects.project_name'])
					->join('projects','logs.log_project_id = projects.project_id')
					->whereIn('logs.log_project_id',$Request['projects'])
					->where('logs.created_at <= "'.$Request['finish'].'"')
					->like('logs.log_structured_data',$Request['query'],'both')
					->limit(100)
					->orderBy('logs.log_id','DESC')
					->find();
			}
			else{
				return	$this->select(['logs.log_id', 'logs.log_structured_data', 'logs.log_title','logs.log_part','logs.log_status','logs.created_at', 'projects.project_name'])
					->join('projects','logs.log_project_id = projects.project_id')
					->whereIn('logs.log_project_id',$Request['projects'])
					->where('logs.created_at <= "'.$Request['finish'].'"')
					->limit(100)
					->orderBy('logs.log_id','DESC')
					->find();
			}
		}
		if(!$Request['start']&&!$Request['finish']){
			if($Request['query']){
				return	$this->select(['logs.log_id', 'logs.log_structured_data', 'logs.log_title','logs.log_part','logs.log_status','logs.created_at', 'projects.project_name'])
					->join('projects','logs.log_project_id = projects.project_id')
					->whereIn('logs.log_project_id',$Request['projects'])
					->like('logs.log_structured_data',$Request['query'],'both')
					->limit(100)
					->orderBy('logs.log_id','DESC')
					->find();
			}
			else{
				return	$this->select(['logs.log_id', 'logs.log_structured_data', 'logs.log_title','logs.log_part','logs.log_status','logs.created_at', 'projects.project_name'])
					->join('projects','logs.log_project_id = projects.project_id')
					->whereIn('logs.log_project_id',$Request['projects'])
					->limit(100)
					->orderBy('logs.log_id','DESC')
					->find();
			}
		}
	}
	
	/**
	 * CRUD BLOCK CREATE
	 */
	
	
	/**
	 * @param int $projectID
	 * @param array $structured_data
	 * @param string $log_title
	 * @param string $log_part
	 * @param string $log_status
	 * @return bool Одиночная вставка лога в БД
	 */
	public function createLog(int $projectID,  array $structured_data, string $log_title, string $log_part, string $log_status):bool
	{
		return true;
	}
	
	
	/**
	 * @return bool Инициализация редис ключей после заполнения БД
	 * @throws \RedisException
	 */
	public function afterSeed():bool
	{
		$Rediska = new \Redis();
		$Rediska->connect('127.0.0.1',6379);
		$data = $this->select(
			[
				'logs.log_id',
				'logs.log_structured_data',
				'logs.log_title',
				'logs.log_part',
				'logs.log_status',
				'logs.created_at',
				'projects.project_name'
			])
			->join('projects','logs.log_project_id = projects.project_id','LEFT')
			->limit(100)
			->orderBy('logs.log_id','DESC')
			->find();
		for($i=0,$imax=count($data); $i < $imax; $i++){
			$data[$i]['log_structured_data'] = json_decode($data[$i]['log_structured_data'],true);
		}
		foreach ($data as $record){
			$Rediska->rPush('list_logs',json_encode(
				[   'log_id'=>$record['log_id'],
					'project_name'=>$record['project_name'],
					'title'=>$record['log_title'],
					'time'=>$record['created_at'],
					'part'=>$record['log_part'],
					'status'=>$record['log_status']
				],256));
		}
		$counter = $this->select('log_id')->limit(1)->orderBy('log_id','DESC')->find();
		$Rediska->set('total_log_rows',$counter[0]['log_id']);
		$Rediska->set('global_service_status','stop');
		$Rediska->set('service_max_requests',0);
		$Rediska->close();
		return true;
	}
}
