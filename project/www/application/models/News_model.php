<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Новости.
 */
class News_model extends CI_Model
{
	/**
	 *  Список новостей.
	 *  
	 *  @var  array
	 */
	protected $news = [];

	// ------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		// Подключение к базе данных.
		$this->load->database();
	}

	// ------------------------------------------------------------------------

	/**
	 *  Новость или список новостей.
	 *  
	 *  @param   integer  $news_id  [ID новости]
	 *  @param   integer  $limit    [Ограничение]
	 *  @return  array
	 */
	public function _get($news_id = 0, $limit = 0)
	{
		$news_id = (integer) $news_id;
		$limit   = (integer) $limit;

		$this->db
			->reset_query()
			->from('news')
			->where('title !=', '')
			->where('message !=', '');

		if ($news_id > 0)
		{
			$this->db->where('id', $news_id);
		}
		else
		{
			$this->db->order_by('date_add', 'DESC');
		}

		if ($limit > 0)
		{
			$this->db->limit($limit);
		}

		if ($query = $this->db->get())
		{
			if ($news_id > 0)
			{
				$this->news = $query->row_array();
			}
			else
			{
				foreach ($query->result_array() as $data)
				{
					$this->news[$data['id']] = $data;
				}
			}
		}

		return $this->news;
	}

	// ------------------------------------------------------------------------
	
	/**
	 *  Добавление новостей.
	 *  
	 *  @param  array  $data  [Данные]
	 */
	public function _set($data = [])
	{
		$data = (array) $data;
		$news_id = 0;
		
		if (!empty($data))
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->set($data)
				->set('date_add', 'NOW()', FALSE)
				->insert('news');
				
			$news_id = $this->db->insert_id();

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
			{
				return 0;
			}
		}

		return $news_id;
	}

	/**
	 *  Обновление данных по новости.
	 *  
	 *  @param   integer  $news_id  [ID новости]
	 *  @param   array    $data     [Новые данные]
	 *  @return  boolean
	 */
	public function _update($news_id = 0, $data = [])
	{
		$news_id = (integer) $news_id;
		$data    = (array) $data;

		if (!empty($data))
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->where('id', $news_id)
				->update('news', $data);

			$this->db->trans_complete();

			return $this->db->trans_status();
		}

		return FALSE;
	}

	/**
	 *  Удаление новости.
	 *  
	 *  @param   integer  $news_id  [ID новости]
	 *  @return  boolean
	 */
	public function _unset($news_id = 0)
	{
		$news_id = (integer) $news_id;

		if ($news_id > 0)
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->where('id', $news_id)
				->delete('news');

			$this->db->trans_complete();

			return $this->db->trans_status();
		}

		return FALSE;
	}
}

/* End of file News_model.php */
/* Location: ./application/models/News_model.php */