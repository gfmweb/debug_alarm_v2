<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'projects';
    protected $primaryKey       = 'project_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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
	
	private $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	/**
	 * @return string Генерация секретного ключа для подключения проекта
	 */
	private function getSecret():string
	{
		$secret = '';
		$max  = count($this->characters)-1;
		for($i = 0; $i < 16; $i++)
		{
			$secret.=$this->characters[rand(0,$max)];
		}
		$secret.= time();
		return $secret;
	}
	
	/**
	 * CRUD BLOCK READ
	 */
	
	/**
	 * @return array Вернет все имена и ID имеющихся проектов
	 */
	public function getAll():array
	{
		return $this->select(['project_id','project_name'])->find();
	}
	
	/**
	 * @param int $projectID ID проекта
	 * @return array Вернет всю информацию по проекту
	 */
	public function getProject(int $projectID):array
	{
		return $this
			->select(
				[
					'projects.project_id',
					'projects.project_name',
					'projects.project_secret',
					'projects.project_rules',
					'(SELECT users.user_id, users.user_name FROM users WHERE users.user_id IN (projects.project_permissions)) as users'
				])
			->where($projectID)
			->first();
	}
	
	/**
	 * CRUD BLOCK CREATE
	 */
	
	/**
	 * @param string $projectName Имя нового проекта
	 * @return int Вернет ID только-что созданного проекта
	 */
	public function createProject(string $projectName):int
	{
		$secret = $this->getSecret();
		return $this->insert(['project_name'=>$projectName,'project_secret'=>$secret],true);
	}
	
	/**
	 * CRUD BLOCK UPDATE
	 */
	
	/**
	 * @param int $projectID ID проекта
	 * @param array $permissions Массив разрешений
	 * @return bool Обновит пользователей получающих уведомления по проекту
	 */
	public function editProjectPermissions (int $projectID,array $permissions):bool
	{
		return $this->update($projectID,['project_permissions'=>json_encode($permissions,256)]);
	}
	
	/**
	 * @param int $projectID ID проекта
	 * @param string $projectName Имя проекта
	 * @return bool Обновит имя проекта
	 */
	public function editProjectName(int $projectID,string $projectName):bool
	{
		return $this->update($projectID,['project_name'=>$projectName]);
	}
	
	/**
	 * @param int $projectID ID проекта
	 * @param string $projectRules Код правил обработки входящих / исходящих данных
	 * @return bool Обновит проектные правила обработки входящих / исходящих данных
	 */
	public function editProjectRulles(int $projectID, string $projectRules):bool
	{
		return $this->update($projectID,['project_rules'=>$projectRules]);
	}
	
	/**
	 * CRUD BLOCK DELETE
	 */
	
	/**
	 * @param int $projectID ID проекта
	 * @return bool Удалит проект
	 */
	public function deleteProject(int $projectID):bool
	{
		return $this->delete($projectID);
	}
}
