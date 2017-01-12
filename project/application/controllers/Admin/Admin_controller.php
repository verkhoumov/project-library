<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Основные страницы админ-панели.
 */
class Admin_controller extends CI_Controller
{
	/**
	 *  Список групп, имеющих доступ к разделу сайта.
	 *  
	 *  @var  array
	 */
	protected $access = [1];

	// ------------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		// Библиотека для парсинга страниц.
		$this->load->library('parser');

		$this->data['attention'] = [
			'person' => 'Сотрудник'
		];
	}

	// ------------------------------------------------------------------------
	
	/**
	 *  Панель управления.
	 *  
	 *  @return  void
	 */
	public function index()
	{
		if (check_access($this->access))
		{
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
	
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->load->view('admin/index');
			$this->load->view('footer');
		}
	}
}

/* End of file Admin_controller.php */
/* Location: ./application/controllers/Admin/Admin_controller.php */