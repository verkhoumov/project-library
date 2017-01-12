<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Работы студентов (материалы).
 */
class Books_model extends CI_Model
{
	/**
	 *  Список материалов.
	 *  
	 *  @var  array
	 */
	protected $books = [];

	/**
	 *  Список категорий материалов.
	 *  
	 *  @var  array
	 */
	protected $categories = [];

	/**
	 *  Список годов материалов.
	 *  
	 *  @var  array
	 */
	protected $years = [];

	/**
	 *  Список специализаций студентов.
	 *  @var  array
	 */
	protected $specialities = [];

	// ------------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		// Подключение к базе данных.
		$this->load->database();
	}

	// ------------------------------------------------------------------------

	/**
	 *  Материалы, список материалов.
	 *  
	 *  @param   integer  $book_id  [ID материала]
	 *  @param   integer  $limit    [Ограничение]
	 *  @return  array
	 */
	public function _get($book_id = 0, $limit = 0)
	{
		$book_id = (integer) $book_id;
		$limit   = (integer) $limit;

		$this->db
			->reset_query()
			->from('books');

		if ($book_id > 0)
		{
			$this->db->where('id', $book_id);
		}
		else
		{
			$this->db->order_by('date_add', 'DESC');
		}

		if ($book_id == 0 && $limit > 0)
		{
			$this->db->limit($limit);
		}

		if ($query = $this->db->get())
		{
			if ($book_id > 0)
			{
				$this->books = $query->row_array();
			}
			else
			{
				foreach ($query->result_array() as $data)
				{
					$this->books[$data['id']] = $data;
				}
			}
		}

		return $this->books;
	}
	
	/**
	 *  Категории материалов.
	 *  
	 *  @param   integer  $category_id  [ID категории]
	 *  @return  array
	 */
	public function _get_categories($category_id = 0)
	{
		$category_id = (integer) $category_id;

		$this->db
			->reset_query()
			->from('categories');

		if ($category_id > 0)
		{
			$this->db->where('id', $category_id);
		}

		if ($query = $this->db->get())
		{
			if ($category_id > 0)
			{
				$this->categories = $query->row_array();
			}
			else
			{
				foreach ($query->result_array() as $data)
				{
					$this->categories['by_id'][$data['id']] = $data;
					$this->categories['by_code'][$data['code']] = $data;
				}
			}
		}

		return $this->categories;
	}

	/**
	 *  Список годов добавленных материалов.
	 *  
	 *  @param   integer  $category_id  [Категория материала]
	 *  @return  array
	 */
	public function _get_years($category_id = 0)
	{
		$category_id = (integer) $category_id;

		$this->db
			->reset_query()
			->select('year')
			->from('books');

		if ($category_id > 0)
		{
			$this->db->where('category_id', $category_id);
		}

		$this->db->where('year >', 1900)
			->group_by('year')
			->order_by('year', 'DESC');

		if ($query = $this->db->get())
		{
			foreach ($query->result_array() as $data)
			{
				$this->years[$data['year']] = $data['year'];
			}
		}

		return $this->years;
	}

	/**
	 *  Список специальностей студентов.
	 *  
	 *  @param   integer  $category_id  [Категория материала]
	 *  @return  array
	 */
	public function _get_specialities($category_id = 0)
	{
		$category_id = (integer) $category_id;

		$this->db
			->reset_query()
			->select('speciality')
			->from('books');

		if ($category_id > 0)
		{
			$this->db->where('category_id', $category_id);
		}

		$this->db->where('speciality !=', '')
			->group_by('speciality')
			->order_by('speciality', 'ASC');

		if ($query = $this->db->get())
		{
			foreach ($query->result_array() as $data)
			{
				$this->specialities[$data['speciality']] = $data['speciality'];
			}
		}

		return $this->specialities;
	}

	// ------------------------------------------------------------------------

	/**
	 *  Поиск материалов по заданным условиям в определённой категории.
	 *  
	 *  @param   array    $form         [Условия поиска]
	 *  @param   integer  $category_id  [Категория материалов]
	 *  @return  array
	 */
	public function _search($form = [], $category_id = 0)
	{
		$form = (array) $form;
		$category_id = (integer) $category_id;

		$this->db
			->reset_query()
			->from('books');

		// Тип работы.
		if ($category_id > 0)
		{
			$this->db->where('category_id', $category_id);
		}

		// Год публикации.
		if (!empty($form['year']))
		{
			$this->db->where('year', $form['year']);
		}

		// Специальность.
		if (!empty($form['speciality']) && $form['speciality'] != 'all')
		{
			$this->db->where('speciality', $form['speciality']);
		}

		// Строка запроса.
		if (!empty($form['query']))
		{
			$this->db->group_start()
				->like('title', $form['query'])
				->or_like('CONCAT_WS(" ", author_secondname, author_firstname, author_thirdname)', $form['query'], 'both', FALSE)
				->or_like('CONCAT_WS(" ", leader_secondname, leader_firstname, leader_thirdname)', $form['query'], 'both', FALSE)
			->group_end();
		}

		$this->db->order_by('year', 'DESC')
			->order_by('date_add', 'DESC');

		if ($query = $this->db->get())
		{
			foreach ($query->result_array() as $data)
			{
				$this->books[$data['id']] = $data;
			}
		}

		return $this->books;
	}

	// ------------------------------------------------------------------------

	/**
	 *  Добавление материала.
	 *  
	 *  @param  array  $data  [Данные]
	 */
	public function _set($data = [])
	{
		$data    = (array) $data;
		$book_id = 0;
		
		if (!empty($data))
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->set($data)
				->set('date_add', 'NOW()', FALSE)
				->insert('books');
				
			$book_id = $this->db->insert_id();

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
			{
				return 0;
			}
		}

		return $book_id;
	}

	/**
	 *  Обновление информации о материале.
	 *  
	 *  @param   integer  $book_id  [ID материала]
	 *  @param   array    $data     [Информация]
	 *  @return  boolean
	 */
	public function _update($book_id = 0, $data = [])
	{
		$book_id = (integer) $book_id;
		$data    = (array) $data;

		if (!empty($data))
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->where('id', $book_id)
				->update('books', $data);

			$this->db->trans_complete();

			return $this->db->trans_status();
		}

		return FALSE;
	}

	/**
	 *  Удаление материала.
	 *  
	 *  @param   integer  $book_id  [ID материала]
	 *  @return  boolean
	 */
	public function _unset($book_id = 0)
	{
		$book_id = (integer) $book_id;

		if ($book_id > 0)
		{
			$this->db->trans_start();

			$this->db
				->reset_query()
				->where('id', $book_id)
				->delete('books');

			$this->db->trans_complete();

			return $this->db->trans_status();
		}

		return FALSE;
	}
}

/* End of file Books_model.php */
/* Location: ./application/models/Books_model.php */