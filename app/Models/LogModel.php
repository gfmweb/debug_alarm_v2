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
		return $this->select(['logs.log_id', 'logs.log_structured_data','logs.log_title','logs.log_part','logs.log_status','logs.created_at','projects.project_name'])->join('projects','projects.project_id = logs.log_project_id','LEFT')->where('logs.log_id',$logID)->first();
	}
	
	/**
	 * @param int $logID ID интересующего ЛОГА
	 * @return array Данные как есть от проекта
	 */
	public function getLogRowDataByID(int $logID):array
	{
		return $this->select(['logs.log_row_data','logs,created_at'])->where('log_id',$logID)->first();
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
	public function createLog(int $projectID, string $row_data, array $structured_data):bool
	{
		return $this->insert(['log_project_id'=>$projectID,'log_row_data'=>$row_data,'log_structured_data'=>json_encode($structured_data,256)]);
	}
	
	public function Test($variable)
	{
		//logs.log_id, logs.log_structured_data, logs.created_at, projects.project_name
		//logs.log_project_id = rojects.project_id
		
		$result = $this->select(['logs.log_id', 'logs.log_structured_data', 'logs.created_at', 'projects.project_name'])
			->join('projects','logs.log_project_id = projects.project_id')
			->whereIn('logs.log_project_id',[1])
			->where('logs.created_at BETWEEN "2022-08-17 11:29:12" AND "2022-08-17 11:29:16"')
			->like('logs.log_structured_data','+79786867888','both')
			->limit(100)
			->orderBy('logs.log_id','DESC')
			->find();
		return $result;
	}
}
