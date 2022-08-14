<?php

namespace App\Controllers\ADMIN;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use CodeIgniter\API\ResponseTrait;

class Admin extends BaseController
{
	use ResponseTrait;
    public function index()
    {
       return view('admin/admin_index');
    }
	
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
			['name'=>'Администраторы',      'urI'=>'/admin/admins',         'method'=>'GET',      'after'=>['action'=>'show_content']],
			['name'=>'Управление сервисом', 'urI'=>'/admin/settings',       'method'=>'GET',      'after'=>['action'=>'show_content']],
			['name'=>'Выход',               'urI'=>'/logout',               'method'=>'GET',      'after'=>['action'=>'/login']]
		];
		return $this->respond(['menu_text'=>$menu_text,'adminActions'=>$adminActions],200);
	}
	
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
	
	public function getUsers()
	{
		$header = 'Пользователи';
		$data = [];
		return $this->respond(['header'=>$header,'content'=>$data]);
	}
	
	public function getAdmins()
	{
		$header = 'Администараторы';
		$data = [];
		return $this->respond(['header'=>$header,'content'=>$data]);
	}
	
	public function getSettings()
	{
		$header = 'Настройки сервиса';
		$data = [];
		return $this->respond(['header'=>$header,'content'=>$data]);
	}
	
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
		$response['form']=['urI'=>'/admin/update_project','method'=>'POST'];
		return $this->respond($response,200);
	}
	
	public function createProject(){
		
		$Projects = model(ProjectModel::class);
		$Projects->createProject($this->request->getVar('project_name'));
	}
	
	public function deleteProject()
	{
		$Project = model(ProjectModel::class);
		$id = $this->request->getVar('project_id');
		$Project->deleteProject($id);
		return $this->respond([$id],200);
	}
}
