<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Пользователи: читатели, сотрудники.
 */
class Users_controller extends CI_Controller
{
	/**
	 *  Группы пользователей.
	 *  
	 *  @var  array
	 */
	private $users_group = [
		'readers'  => 2,
		'employee' => 1
	];

	/**
	 *  Описание для каждой группы пользователей.
	 *  
	 *  @var  array
	 */
	private $group_description = [
		'readers'  => 'Список читателей библиотеки, которым доступны материалы для поиска.',
		'employee' => 'Список сотрудников библиотеки, которые имеют возможность добавлять новые материалы.'
	];
	
	/**
	 *  Информация о пользователе по-умолчанию.
	 *  
	 *  @var  array
	 */
	protected $user = [
		'login'      => '',
		'password'   => '',
		'group_id'   => 0,
		'firstname'  => '',
		'secondname' => '',
		'thirdname'  => '',
		'email'      => ''
	];
	
	/**
	 *  Список групп пользователей, имеющих доступ к текущему разделу.
	 *  
	 *  @var  array
	 */
	protected $access = [1];
	
	/**
	 *  ID пользователя.
	 *  
	 *  @var  integer
	 */
	protected $user_id = 0;

	/**
	 *  Группа пользователя.
	 *  
	 *  @var  string
	 */
	protected $group = '';

	/**
	 *  Сообщение об ошибке.
	 *  
	 *  @var  string
	 */
	protected $alert = '';

	// ------------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		// Библиотеки.
		$this->load->library('parser');
		$this->load->model('users_model');
		$this->load->model('groups_model');
		$this->load->helper('url');

		// Группа авторизованного пользователя.
		$this->data['attention'] = [
			'person' => 'Сотрудник'
		];

		// Группы пользователей.
		$this->groups = $this->groups_model->_get();
		
		if ($status = $this->input->get('alert'))
		{
			if ($status == 'add')
			{
				$this->alert = set_page_alert('Пользователь добавлен! Здесь Вы можете изменить информацию!');
			}
			elseif ($status == 'delete')
			{
				$this->alert = set_page_alert('Пользователь успешно удалён!');
			}
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 *  Список пользователей/читателей.
	 *  
	 *  @param   string  $group  [Категория пользователей]
	 *  @return  void
	 */
	public function index($group = '')
	{
		if (check_access($this->access))
		{
			$this->group = (string) $group;
	
			if (isset($this->users_group[$this->group]))
			{
				$this->group_id = $this->users_group[$this->group];
				$this->group_name = $this->groups[$this->group_id]['group_name'];
				$this->group_plural = $this->groups[$this->group_id]['group_name_plural'];
			}
			else
			{
				show_404();
			}
	
			self::get_backward($this->group);
	
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => $this->group_name.' — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
			
			// Данные для шаблонов.
			$this->data['backward'] = [
				'name' => 'Назад',
				'link' => '/user/admin'
			];
	
			$this->users = $this->users_model->_get(0, $this->group_id);
	
			$this->data['tmpl'] = [
				'users'             => get_users_data($this->users, $this->group),
				'users.count'       => count($this->users),
				'user_group'        => $this->group,
				'group_name'        => $this->group_name,
				'group_name_plural' => $this->group_plural,
				'description'       => $this->group_description[$this->group],
				'alert'             => $this->alert
			];
			
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/users/index', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}
	
	/**
	 *  Добавление нового пользователя.
	 *  
	 *  @param   string  $group  [Категория пользователей]
	 *  @return  void
	 */
	public function add($group = '')
	{
		if (check_access($this->access))
		{
			$this->group = (string) $group;
			
			if (isset($this->users_group[$this->group]))
			{
				$this->group_id = $this->users_group[$this->group];
				$this->group_name = $this->groups[$this->group_id]['group_name'];
				$this->group_plural = $this->groups[$this->group_id]['group_name_plural'];
			}
			else
			{
				show_404();
			}
	
			self::get_backward($this->group);

			// Обработчик формы.
			self::form();
	
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Добавить '.$this->group_plural.' — '.$this->group_name.' — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
			
			// Данные для шаблонов.
			$this->data['tmpl'] = [
				'user_group'        => $this->group,
				'group_name'        => $this->group_name,
				'group_name_plural' => $this->group_plural,
				'description'       => $this->group_description[$this->group],
				'crsf.token_name'   => $this->security->get_csrf_token_name(),
				'crsf.hash'         => $this->security->get_csrf_hash(),
				'alert'             => $this->alert
			];
			
			$this->data['tmpl'] = array_merge($this->data['tmpl'], $this->form, $this->form['errors']);
			
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/users/add', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}
	
	/**
	 *  Изменить информацию о пользователе.
	 *  
	 *  @param   string   $group    [Категория пользователей]
	 *  @param   integer  $user_id  [ID пользователя]
	 *  @return  void
	 */
	public function edit($group = '', $user_id = 0)
	{
		if (check_access($this->access))
		{
			// Проверка ID пользователя.
			$this->group = (string) $group;
			$this->user_id = (integer) $user_id;
	
			if (isset($this->users_group[$this->group]) && $this->user_id > 0)
			{
				$this->group_id     = $this->users_group[$this->group];
				$this->group_name   = $this->groups[$this->group_id]['group_name'];
				$this->group_plural = $this->groups[$this->group_id]['group_name_plural'];
			}
			else
			{
				show_404();
			}
			
			$this->user = $this->users_model->_get($this->user_id);
			$this->user['password'] = '';
			
			if (empty($this->user))
			{
				show_404();
			}
	
			self::get_backward($this->group);

			// Обработчик формы.
			self::form();
			
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Редактировать '.$this->group_plural.' — '.$this->group_name.' — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
	
			// Данные для шаблонов.
			$this->data['tmpl'] = [
				'user_group'        => $this->group,
				'group_name'        => $this->group_name,
				'group_name_plural' => $this->group_plural,
				'description'       => $this->group_description[$this->group],
				'crsf.token_name'   => $this->security->get_csrf_token_name(),
				'crsf.hash'         => $this->security->get_csrf_hash(),
				'alert'             => $this->alert
			];
	
			$this->data['tmpl'] = array_merge($this->data['tmpl'], $this->form, $this->form['errors']);
		
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/users/edit', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}

	/**
	 *  Удаление пользователя.
	 *  
	 *  @param   string   $group    [Категория пользователей]
	 *  @param   integer  $user_id  [ID пользователя]
	 *  @return  void
	 */
	public function delete($group = '', $user_id = 0)
	{
		if (check_access($this->access))
		{
			// Проверка ID пользователя.
			$this->group = (string) $group;
			$this->user_id = (integer) $user_id;
	
			if (isset($this->users_group[$this->group]) && $this->user_id > 0)
			{
				$this->group_id     = $this->users_group[$this->group];
				$this->group_name   = $this->groups[$this->group_id]['group_name'];
				$this->group_plural = $this->groups[$this->group_id]['group_name_plural'];
			}
			else
			{
				show_404();
			}
			
			$this->user = $this->users_model->_get($this->user_id);
			
			if (empty($this->user))
			{
				show_404();
			}
	
			self::get_backward($this->group);

			// Обработчик формы.
			self::form();
			
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Удалить '.$this->group_plural.' — '.$this->group_name.' — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];

			// Данные для шаблонов.
			$this->data['tmpl'] = [
				'user_group'        => $this->group,
				'group_name'        => $this->group_name,
				'group_name_plural' => $this->group_plural,
				'user'              => $this->parser->parse('blocks/users', ['users' => get_users_data([$this->user], $this->group)], TRUE),
				'description'       => $this->group_description[$this->group],
				'crsf.token_name'   => $this->security->get_csrf_token_name(),
				'crsf.hash'         => $this->security->get_csrf_hash(),
				'alert'             => $this->alert
			];
			
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/users/delete', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}

	// ------------------------------------------------------------------------
	
	/**
	 *  Обработчик формы.
	 *  
	 *  @return  void
	 */
	private function form()
	{
		$this->form = [
			'submit'     => FALSE,
			'login'      => $this->user['login'],
			'password'   => $this->user['password'],
			'firstname'  => $this->user['firstname'],
			'secondname' => $this->user['secondname'],
			'thirdname'  => $this->user['thirdname'],
			'email'      => $this->user['email'],
			'errors' => [
				'user[login]'      => '',
				'user[password]'   => '',
				'user[firstname]'  => '',
				'user[secondname]' => '',
				'user[thirdname]'  => '',
				'user[email]'      => ''
			]
		];
		
		// Добавление и редактирования работ.		
		if (($this->input->post('add') || $this->input->post('edit')) && check_access($this->access))
		{
			// Статус формы.
			$this->form['submit'] = TRUE;

			// Форма.
			$form = (array) $this->input->post('user');

			// Логин.
			if (!empty($form['login']))
			{
				$this->form['login'] = htmlspecialchars(trim((string) $form['login']), ENT_QUOTES);
			}
			
			// Пароль.
			if (!empty($form['password']))
			{
				$this->form['password'] = htmlspecialchars(trim((string) $form['password']), ENT_QUOTES);
			}
			
			// Имя.
			if (!empty($form['firstname']))
			{
				$this->form['firstname'] = htmlspecialchars(trim((string) $form['firstname']), ENT_QUOTES);
			}
			
			// Фамилия.
			if (!empty($form['secondname']))
			{
				$this->form['secondname'] = htmlspecialchars(trim((string) $form['secondname']), ENT_QUOTES);
			}
			
			// Отчество.
			if (!empty($form['thirdname']))
			{
				$this->form['thirdname'] = htmlspecialchars(trim((string) $form['thirdname']), ENT_QUOTES);
			}
			
			// Почта.
			if (!empty($form['email']))
			{
				$this->form['email'] = htmlspecialchars(trim((string) $form['email']), ENT_QUOTES);
			}
			
			// Вывод ошибок.
			if (!self::validation())
			{
				$this->form['errors'] = set_errors_wrap($this->form['errors']);
			}
			else
			{
				$this->form['data'] = self::get_form_data();
				
				if ($this->input->post('add'))
				{
					$this->user_id = $this->users_model->_set($this->form['data']);
					
					// Переход на страницу редактирования.
					redirect($this->config->item('base_url').$this->data['backward']['addr'].'/edit/'.$this->user_id.'?alert=add', 'location', 301);
				}
				elseif ($this->input->post('edit'))
				{
					$this->users_model->_update($this->user_id, $this->form['data']);
					$this->alert = set_page_alert('Новая информация успешно сохранена!');
				}
			}
		}
		
		// Если запрошено удаление материала.
		if ($this->input->post('delete') && check_access($this->access))
		{
			if ($this->users_model->_unset($this->user_id))
			{
				redirect($this->config->item('base_url').$this->data['backward']['addr'], 'location', 301);
			}
		}
	}
	
	/**
	 *  Валидация формы.
	 *  
	 *  @return  boolean
	 */
	private function validation()
	{
		// Библиотека для валидации формы.
		$this->load->library('form_validation');

		// Сообщения об ошибках.
		$this->form_validation->set_message('max_length', 'Максимальное количество символов — {param}.');
		$this->form_validation->set_message('min_length', 'Минимальное количество символов — {param}.');
		$this->form_validation->set_message('required', 'Поле не может быть пустым.');
		
		// Правила для проверки форм.
		$this->form_validation->set_rules('user[login]', 'Логин', 'trim|required|max_length[20]|min_length[3]');
		$this->form_validation->set_rules('user[firstname]', 'Имя', 'trim|required|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('user[secondname]', 'Фамилия', 'trim|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('user[thirdname]', 'Отчество', 'trim|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('user[email]', 'Почта', 'trim|max_length[50]|min_length[6]');
		
		if ($this->input->post('edit'))
		{
			$this->form_validation->set_rules('user[password]', 'Пароль', 'trim|max_length[20]|min_length[3]');
		}
		else
		{
			$this->form_validation->set_rules('user[password]', 'Пароль', 'trim|required|max_length[20]|min_length[3]');
		}
		
		// Проверка формы.
		if ($this->form_validation->run() === FALSE)
		{
			// Объединение ошибок.
			$this->form['errors'] = array_merge($this->form['errors'], $this->form_validation->error_array());
			
			return FALSE;
		}

		return TRUE;
	}
	
	/**
	 *  Получение данных из формы.
	 *  
	 *  @return  array
	 */
	private function get_form_data()
	{
		return [
			'login'      => $this->form['login'],
			'password'   => get_password_hash($this->form['password']),
			'group_id'   => $this->group_id,
			'firstname'  => $this->form['firstname'],
			'secondname' => $this->form['secondname'],
			'thirdname'  => $this->form['thirdname'],
			'email'      => $this->form['email']
		];
	}

	// ------------------------------------------------------------------------
	
	/**
	 *  Генерация кнопки "Назад".
	 *  
	 *  @param   string  $group  [Категория пользователей]
	 *  @return  void
	 */
	private function get_backward($group = '')
	{
		$group = (string) $group;

		$this->data['backward'] = [
			'name' => 'Назад',
			'link' => '/user/admin/'.$group,
			'addr' => 'user/admin/'.$group
		];
	}
}

/* End of file Users_controller.php */
/* Location: ./application/controllers/Admin/Users_controller.php */