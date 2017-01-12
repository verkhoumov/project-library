<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Группы пользователей.
 */
class Groups_model extends CI_Model
{
	/**
	 *  Список групп пользователей.
	 *  
	 *  @var  array
	 */
	protected $groups = [];
	
	// ------------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		// Подключение к базе данных.
		$this->load->database();
	}

	// ------------------------------------------------------------------------

	/**
	 *  Группа, список групп.
	 *  
	 *  @param   integer  $group_id   [ID группы]
	 *  @return  array
	 */
	public function _get($group_id = 0)
	{
		$group_id = (integer) $group_id;

		$this->db
			->reset_query()
			->from('groups');

		if ($group_id > 0)
		{
			$this->db->where('id', $group_id);
		}

		if ($query = $this->db->get())
		{
			if ($group_id > 0)
			{
				$this->groups = $query->row_array();
			}
			else
			{
				foreach ($query->result_array() as $data)
				{
					$this->groups[$data['id']] = $data;
				}
			}
		}

		return $this->groups;
	}
}

/* End of file Groups_model.php */
/* Location: ./application/models/Groups_model.php */