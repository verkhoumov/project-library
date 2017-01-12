<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Сессия.
 */
class Session_hooks
{
	/**
	 *  CodeIgniter handler.
	 *  
	 *  @var  $this
	 */
	protected $CI;

	/**
	 *  Логин пользователя.
	 *  
	 *  @var  string
	 */
	protected $login;

	/**
	 *  Пароль пользователя.
	 *  
	 *  @var  string
	 */
	protected $password;

	/**
	 *  Время жизни куки авторизации.
	 */
	const COOKIES_TLT = 2592000;

	// ------------------------------------------------------------------------

	public function __construct()
	{
		// CodeIgniter handler.
		$this->CI = &get_instance();

		// Библиотеки.
		$this->CI->load->library('session');
		$this->CI->load->model('auth_model');
		$this->CI->load->model('groups_model');

		// Данные сессии.
		$this->login    = (string) $this->CI->session->tempdata('_igor_user_login');
		$this->password = (string) $this->CI->session->tempdata('_igor_user_password');
		
		$this->login = get_login($this->login);
	}

	// ------------------------------------------------------------------------

	/**
	 *  Обработка сессии.
	 *  
	 *  @return void
	 */
	public function start()
	{
		$user = [];
		
		// Если логин и пароль получены.
		if (isset($this->login) && isset($this->password))
		{
			$user = self::get_user();
			$groups = self::get_groups();
			
			// Not found user.
			if (empty($user) || empty($groups))
			{
				$this->CI->session->set_tempdata('_igor_user_login', '', self::COOKIES_TLT);
				$this->CI->session->set_tempdata('_igor_user_password', '', self::COOKIES_TLT);
			}
			else
			{
				$user['group'] = $groups[$user['group_id']]['user_name'];
			}
		}
		
		// Сохранение данных о сессии в конфиге для дальнейшего использования.
		$this->CI->config->set_item('user', $user);
	}

	// ------------------------------------------------------------------------

	/**
	 *  Получение информации о пользователе по логину и паролю.
	 *  
	 *  @return  array
	 */
	private function get_user()
	{
		// Проверка данных.
		return $this->CI->auth_model->_auth($this->login, $this->password);
	}
	
	/**
	 *  Получение списка групп пользователей.
	 *  
	 *  @return  array
	 */
	private function get_groups()
	{
		return $this->CI->groups_model->_get();
	}
}

/* End of file Session_hooks.php */
/* Location: ./application/hooks/Session_hooks.php */