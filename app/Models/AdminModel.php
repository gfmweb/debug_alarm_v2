<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'admins';
    protected $primaryKey       = 'admin_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['admin_user_id','admin_login','admin_password'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = ['admin_login'=>'is_unique'];
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
	 * CRUD OPERATION BLOCK READ
	*/
	
	/**
	 * @param string $login Логин
	 * @return array Возвращает сущность в виде массива используется при авторизации доступа к админке
	 */
	public function getAdminByLogin(string $login)//:array
	{
		return $this->select(
			[
				'admin_id',
				'admin_login',
				'admin_password',
				'users.user_name',
				'users.user_id',
				'users.user_telegram_id'
			])
			->join('users','users.user_id = admins.admin_user_id','LEFT')
			->where('admin_login',$login)
			->first();
	}
	
	
	
	/**
	 * CRUD BLOCK CREATE
	 */
	
	/**
	 * @param int $userID ID пользователя которого мы делаем админом
	 * @param string $adminLogin Логин админа
	 * @return int Создаст Админа с временным паролем, который вернёт в ответе (этот пароль нужно отправить новоиспеченному админу для того чтобы он смог войти)
	 */
	public function createAdmin(int $userID,  string $adminLogin):int
	{
		$temporallyPassword = rand(11111,99999);
		$this->insert(['admin_user_id'=>$userID, 'admin_login'=>$adminLogin,'admin_password'=>password_hash($temporallyPassword,PASSWORD_DEFAULT)]);
		return $temporallyPassword;
	}
	
	/**
	 * CRUD BLOCK UPDATE
	 */
	
	/**
	 * @param int $adminID ID Админа
	 * @param string $password ХЕШ Пароля
	 * @return bool Обновление пароля администратора
	 */
	public function updatePassword(int $adminID, string $password):bool
	{
		return $this->update($adminID,['admin_password'=>password_hash($password,PASSWORD_DEFAULT)]);
	}
	
	/**
	 * @param int $adminID  ID Админа
	 * @param int $userID ID Пользователя привязанного к админу
	 * @param string $adminLogin Новый логин
	 * @return bool Редактирование админов
	 */
	public function updateAdmin(int $adminID, int $userID, string $adminLogin):bool
	{
		return $this->update($adminID,['admin_user_id'=>$userID,'admin_login'=>$adminLogin]);
	}
	
	/**
	 * @param  int $adminID ID Админа
	 * @return int Сброс пароля администратора по его ID Возвращает временный пароль !(Пока не используется)!
	 */
	public function dropPassword(int $adminID):int
	{
		$temporallyPassword = rand(11111,99999);
		$this->update($adminID,['admin_password'=>password_hash($temporallyPassword,PASSWORD_DEFAULT)]);
		return $temporallyPassword;
	}
	
}
