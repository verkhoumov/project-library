<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Авторизация.
 */
class Auth_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		// Подключение к базе данных.
		$this->load->database();
	}

	// ------------------------------------------------------------------------

	/**
	 *  Проверить существование пользователя.
	 *  
	 *  @param   string  $user_id   [Логин]
	 *  @param   string  $password  [Пароль]
	 *  @return  boolean
	 */
	public function _auth($login = '', $password = '')
	{
		$login = (string) $login;
		$password = (string) $password;
		
		$result = array();

		if ($login != '' && $password != '')
		{
			$this->db
				->reset_query()
				->from('users')
				->where('login', $login)
				->where('password', $password);

			if ($query = $this->db->get())
			{
				$result = $query->row_array();

				if (!empty($result))
				{
					// Онлайн статус.
					self::_set_online($result['id']);
				}
			}
		}

		return $result;
	}

	/**
	 *  Обновить дату последней активности.
	 *  
	 *  @param   integer  $user_id  [ID пользователя]
	 *  @return  boolean
	 */
	private function _set_online($user_id = 0)
	{
		$user_id = (integer) $user_id;

		if ($user_id > 0)
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->set('date_online', 'NOW()', FALSE)
				->where('id', $user_id)
				->update('users');

			$this->db->trans_complete();

			return $this->db->trans_status();
		}

		return FALSE;
	}
}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */