<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Пользователи.
 */
class Users_model extends CI_Model
{
	/**
	 *  Список пользователей.
	 *  
	 *  @var  array
	 */
	protected $users = [];

	// ------------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		// Подключение к базе данных.
		$this->load->database();
	}

	// ------------------------------------------------------------------------

	/**
	 *  Пользователь, список пользователей.
	 *  
	 *  @param   integer  $user_id   [ID пользователя]
	 *  @param   integer  $group_id  [ID группы]
	 *  @param   integer  $limit     [Ограничение]
	 *  @return  array
	 */
	public function _get($user_id = 0, $group_id = 0, $limit = 0)
	{
		$user_id  = (integer) $user_id;
		$group_id = (integer) $group_id;
		$limit    = (integer) $limit;

		$this->db
			->reset_query()
			->from('users');

		if ($group_id > 0)
		{
			$this->db->where('group_id', $group_id);
		}

		if ($user_id > 0)
		{
			$this->db->where('id', $user_id);
		}
		else
		{
			$this->db->order_by('date_online', 'DESC');
		}

		if ($user_id == 0 && $limit > 0)
		{
			$this->db->limit($limit);
		}

		if ($query = $this->db->get())
		{
			if ($user_id > 0)
			{
				$this->users = $query->row_array();
			}
			else
			{
				foreach ($query->result_array() as $data)
				{
					$this->users[$data['id']] = $data;
				}
			}
		}

		return $this->users;
	}

	// ------------------------------------------------------------------------

	/**
	 *  Добавить пользователя.
	 *  
	 *  @param  array  $data  [Данные]
	 */
	public function _set($data = [])
	{
		$data    = (array) $data;
		$user_id = 0;
		
		if (!empty($data))
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->set($data)
				->set('date_add', 'NOW()', FALSE)
				->insert('users');
				
			$user_id = $this->db->insert_id();

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
			{
				return 0;
			}
		}

		return $user_id;
	}

	/**
	 *  Обновить информацию о пользователе.
	 *  
	 *  @param   integer  $user_id  [ID пользователя]
	 *  @param   array    $data     [Информация]
	 *  @return  boolean
	 */
	public function _update($user_id = 0, $data = [])
	{
		$user_id = (integer) $user_id;
		$data    = (array) $data;

		if (!empty($data))
		{
			if (empty($data['password']))
			{
				unset($data['password']);
			}
			
			$this->db->trans_start();

			$this->db
				->reset_query()
				->where('id', $user_id)
				->update('users', $data);

			$this->db->trans_complete();

			return $this->db->trans_status();
		}

		return FALSE;
	}

	/**
	 *  Удалить пользователя.
	 *  
	 *  @param   integer  $user_id  [ID пользователя]
	 *  @return  boolean
	 */
	public function _unset($user_id = 0)
	{
		$user_id = (integer) $user_id;

		if ($user_id > 0)
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->where('id', $user_id)
				->delete('users');

			$this->db->trans_complete();

			return $this->db->trans_status();
		}

		return FALSE;
	}
}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */