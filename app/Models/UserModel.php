<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_telegram_id','user_name','user_login','user_password'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = ['user_login'=>'is_unique'];
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
	 * @param string $login Логин пользователя
	 * @return array Вернёт сущность пользователя
	 */
	public function getUserByLogin(string $login):array
	{
		return $this->select(['user_telegram_id','user_name','user_login','user_password'])->first();
	}
	
	/**
	 * @param int ID текущего пользователя
	 * @return array Вернет всех остальных пользователей
	 */
	public function getAnotherUsers(int $userID):array
	{
		return $this->select(['user_id','user_telegram_id','user_name'])->whereNotIn('user_id',[$userID])->find();
	}
	
	
	/**
	 * CRUD BLOCK CREATE
	 */
	
	/**
	 * @param string $userName Имя нового пользователя
	 * @param string $login Логин нового пользователя
	 * @return int Вернет ID нового почти полноценного пользователя для подготовки ссылки на БОТА для регистрации
	 */
	public function preCreateUser(string $userName, string $login):int
	{
		return $this->insert(['user_name'=>$userName,'user_login'=>$login,'user_password'=>'temp'],true);
	}
	
	/**
	 * CRUD BLOCK UPDATE
	 */
	
	/**
	 * @param int $userID  ID Пользователя
	 * @param int $TelegramID Телеграм ID пользователя
	 * @return int Обновит пользователя добавив ему временный пароль и прикрепив к пользователю его Телеграм
	 */
	public function completeRegisterUser(int $userID, int $TelegramID):int
	{
		$temporallyPassword = rand(11111,99999);
		$this->update($userID,['user_telegram_id'=>$TelegramID,'user_password'=>password_hash($temporallyPassword,PASSWORD_DEFAULT)]);
		return $temporallyPassword;
	}
	

	/**
	 * @param int $userID ID Пользователя
	 * @param string $name Имя
	 * @param string $login Логин
	 * @return bool Обновит имя и логин по ID пользователю
	 */
	public function editUser(int $userID, string $name, string $login):bool
	{
		return $this->update($userID,['user_name'=>$name,'login'=>$login]);
	}
	
	/**
	 * @param int $userID ID Пользователя
	 * @return int Вернет временный пароль
	 */
	public function dropUserPassword(int $userID):int
	{
		$temporallyPassword = rand(11111,99999);
		$this->update($userID,['user_password'=>password_hash($temporallyPassword,PASSWORD_DEFAULT)]);
		return $temporallyPassword;
	}
	
	/**
	 * CRUD BLOCK DELETE
	 */
	
	/**
	 * @param int $userID ID Пользователя
	 * @return bool Удалит пользователя по ID
	 */
	public function deleteUser(int $userID):bool
	{
		return $this->delete($userID);
	}
}

