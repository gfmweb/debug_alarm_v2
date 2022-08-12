<?php

namespace App\Controllers\ADMIN;

use App\Controllers\BaseController;
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
		$header = 'Проекты';
		$data = ['greeds'=>['name','secret','rules','permissions','actions'],
				'data'=>[
					[['name'=>'id','hidden'=>true,'value'=>1],['name'=>'name','hidden'=>false,'value'=>'test'],['name'=>'secret','hidden'=>false,'value'=>'secret'],['name'=>'rules','hidden'=>false,'value'=>'rules'],['name'=>'permissions','hidden'=>false,'value'=>'permissions']],
					[['name'=>'id','hidden'=>true,'value'=>2],['name'=>'name','hidden'=>false,'value'=>'test2'],['name'=>'secret','hidden'=>false,'value'=>'secret2'],['name'=>'rules','hidden'=>false,'value'=>'rules'],['name'=>'permissions','hidden'=>false,'value'=>'permissions2']],
					[['name'=>'id','hidden'=>true,'value'=>3],['name'=>'name','hidden'=>false,'value'=>'test3'],['name'=>'secret','hidden'=>false,'value'=>'secret3'],['name'=>'rules','hidden'=>false,'value'=>'rules'],['name'=>'permissions','hidden'=>false,'value'=>'permissions3']],
					[['name'=>'id','hidden'=>true,'value'=>4],['name'=>'name','hidden'=>false,'value'=>'test4'],['name'=>'secret','hidden'=>false,'value'=>'secret4'],['name'=>'rules','hidden'=>false,'value'=>'rules'],['name'=>'permissions','hidden'=>false,'value'=>'permissions4']],
					//[5,'test5','secret5','rules5','permissions5'],
				]
		];
		$activeDataContentView = 'CRUD';
		$operations = [
				'outline'=>[
						'create'     =>['urI'=>'/admin/createProject','name'=>'Создать проект','dependencies'=>[]],
					],
				'inline'=>[
						
						'deleteProject'=>[
							'urI'=>'/admin/deleteProject',
							'method'=>'POST',
							'label'=>'Удалить проект',
							'icon'=>'<i class="fa-solid fa-trash"></i>',
							'btn_class'=>'btn btn-sm btn-rounded btn-danger',
							'dependencies'=>['id']
						],
						'editProject'  =>[
							'urI'=>'/admin/getProjectByID',
							'method'=>'GET',
							'label'=>'Редактировать проект',
							'icon'=>'<i class="fa-solid fa-pen-nib"></i>',
							'btn_class'=>'btn btn-sm btn-rounded btn-primary',
							'dependencies'=>['id']
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
		$id = $this->request->getVar('project_id');
		return $this->respond([],200);
	}
	
	public function deleteProject()
	{
		$id = $this->request->getVar('project_id');
		return $this->respond([],200);
	}
}
