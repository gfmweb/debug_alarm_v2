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
			['name'=>'Проекты',             'urI'=>null,       'method'=>'POST',      'after'=>['action'=>'show_content']],
			['name'=>'Пользователи',        'urI'=>null,       'method'=>'POST',      'after'=>['action'=>'show_content']],
			['name'=>'Управление сервисом', 'urI'=>null,       'method'=>'POST',      'after'=>['action'=>'show_content']],
			['name'=>'Выход',               'urI'=>'/logout',  'method'=>'GET',       'after'=>['action'=>'/login']]
		];
		return $this->respond(['menu_text'=>$menu_text,'adminActions'=>$adminActions],200);
	}
}
