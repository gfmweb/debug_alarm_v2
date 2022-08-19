<?php

namespace App\Controllers\ADMIN;

use App\Controllers\BaseController;
use App\Controllers\Redis;
use App\Models\AdminModel;
use App\Models\ProjectModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Admin extends BaseController
{
	use ResponseTrait;
	
	/**
	 * @return string Фронтовая часть админки
	 */
    public function index()
    {  return view('admin/admin_index');   }
	
	
	/**
	 * @return \CodeIgniter\HTTP\Response Возвращает начальное меню админки
	 */
	public function init()
	{
		$menu_text = 'Основные действия';
		$adminActions =
		[
			['name'=>'Проекты',             'urI'=>'/admin/projects',       'method'=>'GET',      'after'=>['action'=>'show_content']],
			['name'=>'Пользователи',        'urI'=>'/admin/users',          'method'=>'GET',      'after'=>['action'=>'show_content']],
			['name'=>'Управление сервисом', 'urI'=>'/admin/settings',       'method'=>'GET',      'after'=>['action'=>'/admin/settings']],
			['name'=>'Выход',               'urI'=>'/logout',               'method'=>'GET',      'after'=>['action'=>'/login']]
		];
		return $this->respond(['menu_text'=>$menu_text,'adminActions'=>$adminActions],200);
	}
	
	
	/**
	 * @return \CodeIgniter\HTTP\Response Возвращает все логируемые проекты + кнопки действия с ними
	 */
	public function getProjects()
	{
		$ProjectModel = model(ProjectModel::class);
		$row = $ProjectModel->getAll();
		$header = 'Проекты';
		$data = ['greeds'=>['Имя','Секретный ключ','Действия'],
				'data'=>$this->frontGreedsTransform($row,['project_id']),
		];
		$activeDataContentView = 'CRUD';
		$operations = [
				'outline'=>[
						'create'=>[
							'urI'=>'/admin/createProject',
							'name'=>'Создать проект',
							'id'=>'create_project',
							'template'=>file_get_contents('./logical_forms/admin/create_project.html')],
					],
				'inline'=>[
						
						'deleteProject'=>[
							'urI'=>'/admin/deleteProject',
							'method'=>'POST',
							'label'=>'Удалить проект',
							'icon'=>'<i class="fa-solid fa-trash"></i>',
							'btn_class'=>'btn btn-sm btn-rounded btn-danger',
							'dependencies'=>['project_id'],
							'confirmation'=>true,
							'confirmation_text'=>'Вы действительно хотите удалить'
							
						],
						'editProject'  =>[
							'urI'=>'/admin/getProjectByID',
							'method'=>'GET',
							'label'=>'Редактировать проект',
							'icon'=>'<i class="fa-solid fa-pen-nib"></i>',
							'btn_class'=>'btn btn-sm btn-rounded btn-primary',
							'dependencies'=>['project_id'],
							'confirmation'=>false,
							'confirmation_text'=>''
						],
					]
		];
		return $this->respond(['header'=>$header,'content'=>$data,'activeDataRequests'=>$operations,'activeDataContentView'=>$activeDataContentView]);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Возвращает всех пользователей кроме пользователя Админа + кнопки действия с ними
	 */
	public function getUsers()
	{
		$UserModel = model(UserModel::class);
		$current_user = $this->session->get('user');
		$row = $UserModel->getAnotherUsers($current_user);
		for($t = 0,$tMax=count($row); $t<$tMax; $t++){
			if(!is_numeric($row[$t]['user_telegram_id'])){
				$row[$t]['user_telegram_id']='https://'.$_SERVER['SERVER_NAME'].'/finishRegister/'.$row[$t]['user_login'];}
		}

		$header = 'Пользватели';
		$data = ['greeds'=>['Telegram_id','Имя','Логин','Действия'],
			'data'=>$this->frontGreedsTransform($row,['user_id']),
		];
		
		$activeDataContentView = 'CRUD';
		$operations = [
			'outline'=>[
				'create'=>[
					'urI'=>'/admin/createUser',
					'name'=>'Создать пользователя',
					'id'=>'create_user',
					'template'=>file_get_contents('logical_forms/admin/create_user.html')],
			],
			'inline'=>[
				'deleteUser'=>[
					'urI'=>'/admin/deleteUser',
					'method'=>'POST',
					'label'=>'Удалить пользователя',
					'icon'=>'<i class="fa-solid fa-trash"></i>',
					'btn_class'=>'btn btn-sm btn-rounded btn-danger',
					'dependencies'=>['user_id'],
					'confirmation'=>true,
					'confirmation_text'=>'Вы действительно хотите удалить'
				],
			]
		];
		return $this->respond(['header'=>$header,'content'=>$data,'activeDataRequests'=>$operations,'activeDataContentView'=>$activeDataContentView]);
	}
	
	
	/**
	 * @return string Фронтовая часть страницы настроек для админки
	 */
	public function getSettings()
	{
		return view('admin/admin_settings');
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Вернёт данные по запрашиваемому проекту уже с формой для редактирования
	 */
	public function getProjectByID()
	{
		$Projects = model(ProjectModel::class);
		$id = $this->request->getVar('project_id');
		$response['data'] = $Projects->getProject($id);
		$response['header'] = 'Редактирование проекта';
		$response['target_id']=['name'=>'project_id','value'=>$id];
		$response['fields']=[
			['name'=>'project_name','value'=>$response['data']['project_name'],'type'=>'text','placeholder'=>'Имя проекта'],
			['name'=>'project_secret','value'=>$response['data']['project_secret'],'type'=>'text','placeholder'=>'Секретный ключ']
		];
		$response['form']=['urI'=>'/admin/updateProject','method'=>'POST'];
		return $this->respond($response,200);
	}
	
	/**
	 * @return void Создаёт новый проект
	 */
	public function createProject(){
		$Projects = model(ProjectModel::class);
		$Projects->createProject($this->request->getVar('project_name'));
	}
	
	
	/**
	 * @return \CodeIgniter\HTTP\Response Удаляет проект по его ID
	 */
	public function deleteProject()
	{
		$Project = model(ProjectModel::class);
		$id = $this->request->getVar('project_id');
		$Project->deleteProject($id);
		return $this->respond([$id],200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Обновляет проект по ID (обновляется имя проекта и его секретный ключ)
	 */
	public function updateProject()
	{
		$projectID = $this->request->getVar('project_id');
		$projectName = $this->request->getVar('project_name');
		$projectSecret = $this->request->getVar('project_secret');
		$Projects = model(ProjectModel::class);
		$Projects->editProject($projectID,$projectName,$projectSecret);
		return $this->respond('ok',200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Создаёт пользователя
	 */
	public function createUser()
	{
		$name = $this->request->getVar('user_name');
		$login = $this->request->getVar('user_login');
		$UsersModel = model(UserModel::class);
		$Redis = Redis::getInstance();
		$Redis->set($login,json_encode(['user_id'=>$UsersModel->preCreateUser($name,$login)],256),300);
		return $this->respond(['ok'],200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Удаляет пользователя по его ID
	 */
	public function deleteUser()
	{
		$user_id = $this->request->getVar('user_id');
		$UsersModel = model(UserModel::class);
		$UsersModel->deleteUser($user_id);
		return $this->respond('ok',200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Получение текущего состояния сервиса логирования
	 */
	public function getServiceStatus()
	{
		$Redis = Redis::getInstance();
		if(!$Redis->exists('global_service_status')){
			$Redis->set('global_service_status','stop');
			return $this->respond('stop',200);
		}
		return $this->respond((string)$Redis->get('global_service_status'),200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Смена состояния сервиса ВКЛ/ВЫКЛ
	 */
	public function changeServiceMode()
	{
		$mode = $this->request->getVar('serviceMode');
		$Redis = Redis::getInstance();
		$Redis->set('global_service_status',$mode);
		if($mode == 'start'){
			$Redis->set('service_at_work',time());
		}
		return $this->respond((string)$mode,200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response
	 * @throws \ReflectionException Изменение пароля администратора (так же меняется парол и у пользователя к которому прицеплен админ)
	 */
	public function setNewPassword()
	{
		$current_password = $this->request->getVar('current');
		$newPassword = $this->request->getVar('password');
		$Adm = $this->session->get('user');
		
		if(!password_verify($current_password,$Adm['admin_password']))
		{return	$this->respond(['text'=>'Текущий пароль введен неверно','background'=>'bg-danger','btn_text'=>'Исправить'],200);}
		
		else{
			$Admins = model(AdminModel::class);
			$Users = model(UserModel::class);
			$Users->updateUserPassword($Adm['user_id'],$newPassword);
			$Admins->updatePassword($Adm['admin_id'],$newPassword);
			return $this->respond(['text'=>' Пароль успешно изменен','background'=>'bg-success','btn_text'=>'Ок'],200);
		}
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Получение адреса и имени роута которое отображается в модалке при смене адреса WebHook
	 */
	public function getHookAddress()
	{
		return $this->respond(['main'=>'https://'.$_SERVER['SERVER_NAME'].'/','current'=>'hook'],200);
	}
	
	/**
	 * @return \CodeIgniter\HTTP\Response Устанавливает новое значение для отправки Телеграмом WebHooks в наш адрес
	 */
	public function setWebHook()
	{
		$link = 'https://api.telegram.org/bot'.TELEGRAM.'/setWebhook?url=https://'.$_SERVER['SERVER_NAME'].'/'.$this->request->getVar('route');
		return $this->respond($link,200);
	}
}
