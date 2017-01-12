<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Основные страницы.
 */
class Home_controller extends CI_Controller
{
	/**
	 *  Информация о пользователе.
	 *  
	 *  @var  array
	 */
	protected $user = [];
	
	/**
	 *  Время жизни куки авторизации.
	 */
	const COOKIES_TLT = 2592000;
	
	/**
	 *  Ссылки на разделы сайта.
	 *  
	 *  @var  array
	 */
	protected $links = [
		1 => 'admin',
		2 => 'reader'
	];

	// ------------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		// Библиотеки.
		$this->load->library('parser');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('news_model');
		$this->load->model('auth_model');
		
		// Новости.
		$this->data['news'] = get_news_data($this->news_model->_get(0, 10));
	}

	// ------------------------------------------------------------------------
	
	/**
	 *  Главная страница.
	 *  
	 *  @return  void
	 */
	public function index()
	{
		$this->user = $this->config->item('user');

		// Параметры страницы.
		$this->data['page'] = [
			'title'       => 'Электронная библиотечная система',
			'description' => '',
			'mode'        => ''
		];
		
		// Обработка авторизации.
		self::authorization();

		// Данные для подстановки в шаблоны.
		$this->data['tmpl'] = [
			'crsf.token_name' => $this->security->get_csrf_token_name(),
			'crsf.hash'       => $this->security->get_csrf_hash(),
			'news'            => $this->parser->parse('blocks/news', $this->data, TRUE),
			'user'            => $this->user
		];
		
		$this->data['tmpl'] = array_merge($this->data['tmpl'], $this->form, $this->form['errors']);

		// Сборка страницы.
		$this->parser->parse('header', $this->data['page']);
		$this->load->view('menu', get_user());
		$this->parser->parse('index', $this->data['tmpl']);
		$this->load->view('footer');
	}
	
	/**
	 *  Страница: о нас.
	 *  
	 *  @return  void
	 */
	public function about()
	{
		// Параметры страницы.
		$this->data['page'] = [
			'title'       => 'О нас — Электронная библиотечная система',
			'description' => '',
			'mode'        => ''
		];

		// Сборка страницы.
		$this->parser->parse('header', $this->data['page']);
		$this->load->view('menu', get_user());
		$this->load->view('about');
		$this->load->view('footer');
	}

	// ------------------------------------------------------------------------
	
	/**
	 *  Обработчик авторизации.
	 *  
	 *  @return  void
	 */
	private function authorization()
	{
		$this->form = [
			'submit'   => FALSE,
			'login'    => '',
			'password' => '',
			'errors' => [
				'auth'           => '',
				'auth[login]'    => '',
				'auth[password]' => ''
			]
		];
		
		// Проверка формы авторизации при отправке.
		if ($this->input->post('sign-in'))
		{
			$form = $this->input->post('auth');
			
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
			
			// Вывод ошибок.
			if (!self::validation())
			{
				$this->form['errors'] = set_errors_wrap($this->form['errors']);
			}
			else
			{
				// Переадрессация в панель управления.
				redirect($this->config->item('base_url').'user/'.$this->links[$this->user['group_id']], 'location', 301);
			}
		}
	}
	
	/**
	 *  Валидация формы авторизации.
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
		$this->form_validation->set_rules('auth[login]', 'Login', 'trim|required|max_length[20]|min_length[3]');
		$this->form_validation->set_rules('auth[password]', 'Password', 'trim|required|max_length[20]|min_length[3]');
		
		// Проверка формы.
		if ($this->form_validation->run() === FALSE || !self::login())
		{
			// Объединение ошибок.
			$this->form['errors'] = array_merge($this->form['errors'], $this->form_validation->error_array());
			
			return FALSE;
		}

		return TRUE;
	}
	
	/**
	 *  Обработка входа в аккаунт.
	 *  
	 *  @return  void
	 */
	public function login()
	{
		$this->user = $this->auth_model->_auth(get_login($this->form['login']), get_password_hash($this->form['password']));
				
		if (!empty($this->user))
		{			
			$this->session->set_tempdata('_igor_user_login', $this->form['login'], self::COOKIES_TLT);
			$this->session->set_tempdata('_igor_user_password', get_password_hash($this->form['password']), self::COOKIES_TLT);
			
			return TRUE;
		}
		
		return FALSE;
	}

	/**
	 *  Обработка выхода из аккаунта.
	 *  
	 *  @return  void
	 */
	public function logout()
	{
		// Уничтожение сессии.
		$this->session->sess_destroy();

		// Переадресация на главную страницу.
		redirect($this->config->item('base_url'), 'location', 301);
	}
}

/* End of file Home_controller.php */
/* Location: ./application/controllers/Home_controller.php */