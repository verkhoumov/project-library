<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Работы студентов.
 */
class Books_controller extends CI_Controller
{
	/**
	 *  Информация о материале по-умолчанию.
	 *  
	 *  @var  array
	 */
	protected $book = [
		'category_id'       => 0,
		'title'             => '',
		'year'              => '',
		'link'              => '',
		'author_firstname'  => '',
		'author_secondname' => '',
		'author_thirdname'  => '',
		'speciality'        => '',
		'leader_firstname'  => '',
		'leader_secondname' => '',
		'leader_thirdname'  => ''
	];
	
	/**
	 *  Список категорий материалов.
	 *  
	 *  @var  array
	 */
	protected $categories = [];
	
	/**
	 *  ID просматриваемого материала.
	 *  
	 *  @var  integer
	 */
	protected $book_id = 0;

	/**
	 *  Уведомление об ошибке.
	 *  
	 *  @var  string
	 */
	protected $alert = '';
	
	/**
	 *  Список групп пользователей, имеющих доступ к текущему разделу сайта.
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
		$this->load->helper('url');

		$this->data['attention'] = [
			'person' => 'Сотрудник'
		];

		$this->data['backward'] = [
			'name' => 'Назад',
			'link' => '/user/admin/books',
			'addr' => 'user/admin/books'
		];
		
		$this->load->model('books_model');
		
		if ($status = $this->input->get('alert'))
		{
			if ($status == 'add')
			{
				$this->alert = set_page_alert('Работа добавлена! Здесь Вы можете изменить информацию!');
			}
			elseif ($status == 'delete')
			{
				$this->alert = set_page_alert('Работа успешно удалена!');
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 *  Список работ студентов.
	 *  
	 *  @return  void
	 */
	public function index()
	{
		// Если пользователь имеет доступ к разделу.
		if (check_access($this->access))
		{
			$this->categories = $this->books_model->_get_categories();
			
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Работы студентов — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
	
			$this->data['backward'] = [
				'name' => 'Назад',
				'link' => '/user/admin'
			];
			
			$this->data['tmpl'] = [
				'books' => get_books_data($this->books_model->_get(), $this->categories['by_id']),
				'alert' => $this->alert
			];
			
			$this->data['tmpl']['books.count'] = count($this->data['tmpl']['books']);
	
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/books/index', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}
	
	/**
	 *  Добавление нового материала.
	 *
	 *  @return  void
	 */
	public function add()
	{
		// Если пользователь имеет доступ к разделу.
		if (check_access($this->access))
		{	
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Добавить работу — Работы студентов — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
			
			// Список категорий материалов.
			$this->categories = $this->books_model->_get_categories();
			
			// Обработчик формы.
			self::form();
		
			// Данные для шаблонов.
			$this->form['categories'] = get_categories_data($this->categories['by_id'], $this->form['category']);
			
			$this->data['tmpl'] = [
				'crsf.token_name' => $this->security->get_csrf_token_name(),
				'crsf.hash'       => $this->security->get_csrf_hash(),
				'alert'           => $this->alert
			];
			
			$this->data['tmpl'] = array_merge($this->data['tmpl'], $this->form, $this->form['errors']);
	
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/books/add', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}
	
	/**
	 *  Изменение существующего материала.
	 *  
	 *  @param   integer  $book_id  [ID материала]
	 *  @return  void
	 */
	public function edit($book_id = 0)
	{
		// Если пользователь имеет доступ к разделу.
		if (check_access($this->access))
		{
			// Проверка ID материала.
			$this->book_id = (integer) $book_id;
			
			if ($this->book_id <= 0)
			{
				show_404();
			}
			
			$this->book = $this->books_model->_get($this->book_id);
			
			if (empty($this->book))
			{
				show_404();
			}

			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Редактировать работу — Работы студентов — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
			
			// Список категорий материалов.
			$this->categories = $this->books_model->_get_categories();
			
			// Обработчик формы.
			self::form();
			
			// Данные для шаблонов.
			$this->form['categories'] = get_categories_data($this->categories['by_id'], $this->form['category']);
			
			$this->data['tmpl'] = [
				'crsf.token_name' => $this->security->get_csrf_token_name(),
				'crsf.hash'       => $this->security->get_csrf_hash(),
				'alert'           => $this->alert
			];
			
			$this->data['tmpl'] = array_merge($this->data['tmpl'], $this->form, $this->form['errors']);
		
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/books/edit', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}

	/**
	 *  Удаление материала.
	 *  
	 *  @param   integer  $book_id  [ID материала]
	 *  @return  void
	 */
	public function delete($book_id = 0)
	{
		// Если пользователь имеет доступ к разделу.
		if (check_access($this->access))
		{
			// Проверка ID материала.
			$this->book_id = (integer) $book_id;
			
			if ($this->book_id <= 0)
			{
				show_404();
			}
			
			$this->book = $this->books_model->_get($this->book_id);
			
			if (empty($this->book))
			{
				show_404();
			}

			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Удалить работу — Работы студентов — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];

			// Список категорий материалов.
			$this->categories = $this->books_model->_get_categories();
	
			// Обработчик формы.
			self::form();
			
			// Данные для шаблонов.
			$this->data['tmpl'] = [
				'book'            => $this->parser->parse('blocks/books', ['books' => get_books_data([$this->book], $this->categories['by_id'])], TRUE),
				'crsf.token_name' => $this->security->get_csrf_token_name(),
				'crsf.hash'       => $this->security->get_csrf_hash(),
				'alert'           => $this->alert
			];
			
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/books/delete', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}

	// ------------------------------------------------------------------------
	
	/**
	 *  Обработчик формы с материалом.
	 *  
	 *  @return  void
	 */
	private function form()
	{
		$this->form = [
			'submit'            => FALSE,
			'category'          => $this->book['category_id'],
			'title'             => $this->book['title'],
			'year'              => $this->book['year'],
			'link'              => $this->book['link'],
			'author_firstname'  => $this->book['author_firstname'],
			'author_secondname' => $this->book['author_secondname'],
			'author_thirdname'  => $this->book['author_thirdname'],
			'speciality'        => $this->book['speciality'],
			'leader_firstname'  => $this->book['leader_firstname'],
			'leader_secondname' => $this->book['leader_secondname'],
			'leader_thirdname'  => $this->book['leader_thirdname'],
			'errors' => [
				'books[category]'           => '',
				'books[title]'              => '',
				'books[year]'               => '',
				'books[link]'               => '',
				'books[author][firstname]'  => '',
				'books[author][secondname]' => '',
				'books[author][thirdname]'  => '',
				'books[author][speciality]' => '',
				'books[leader][firstname]'  => '',
				'books[leader][secondname]' => '',
				'books[leader][thirdname]'  => ''
			]
		];
		
		// Добавление и редактирования работ.		
		if (($this->input->post('add') || $this->input->post('edit')) && check_access($this->access))
		{
			// Статус формы.
			$this->form['submit'] = TRUE;

			// Форма.
			$form = (array) $this->input->post('books');

			// Категория работы.
			if (!empty($form['category']))
			{
				$this->form['category'] = (integer) $form['category'];
			}
			
			// Тема.
			if (!empty($form['title']))
			{
				$this->form['title'] = htmlspecialchars(trim((string) $form['title']), ENT_QUOTES);
			}
			
			// Год публикации.
			if (!empty($form['year']))
			{
				$this->form['year'] = (integer) $form['year'];
			}
			
			// Ссылка на работу.
			if (!empty($form['link']))
			{
				$this->form['link'] = htmlspecialchars(trim((string) $form['link']), ENT_QUOTES);
			}
			
			// Автор.
			if (!empty($form['author']['firstname']))
			{
				$this->form['author_firstname'] = htmlspecialchars(trim((string) $form['author']['firstname']), ENT_QUOTES);
			}
			
			if (!empty($form['author']['secondname']))
			{
				$this->form['author_secondname'] = htmlspecialchars(trim((string) $form['author']['secondname']), ENT_QUOTES);
			}
			
			if (!empty($form['author']['thirdname']))
			{
				$this->form['author_thirdname'] = htmlspecialchars(trim((string) $form['author']['thirdname']), ENT_QUOTES);
			}
			
			// Специальность автора.
			if (!empty($form['author']['speciality']))
			{
				$this->form['speciality'] = htmlspecialchars(trim((string) $form['author']['speciality']), ENT_QUOTES);
			}
			
			// Руководитель.
			if (!empty($form['leader']['firstname']))
			{
				$this->form['leader_firstname'] = htmlspecialchars(trim((string) $form['leader']['firstname']), ENT_QUOTES);
			}
			
			if (!empty($form['leader']['secondname']))
			{
				$this->form['leader_secondname'] = htmlspecialchars(trim((string) $form['leader']['secondname']), ENT_QUOTES);
			}
			
			if (!empty($form['leader']['thirdname']))
			{
				$this->form['leader_thirdname'] = htmlspecialchars(trim((string) $form['leader']['thirdname']), ENT_QUOTES);
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
					$this->book_id = $this->books_model->_set($this->form['data']);
					
					// Переход на страницу редактирования.
					redirect($this->config->item('base_url').$this->data['backward']['addr'].'/edit/'.$this->book_id.'?alert=add', 'location', 301);
				}
				elseif ($this->input->post('edit'))
				{
					$this->books_model->_update($this->book_id, $this->form['data']);
					$this->alert = set_page_alert('Новая информация успешно сохранена!');
				}
			}
		}
		
		// Если запрошено удаление работы.
		if ($this->input->post('delete') && check_access($this->access))
		{
			if ($this->books_model->_unset($this->book_id))
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
		$this->form_validation->set_message('in_list', 'Необходимо выбрать, к какой категории относится работа.');
		$this->form_validation->set_message('required', 'Поле не может быть пустым.');
		$this->form_validation->set_message('greater_than', 'Минимальное значение поля — {param}.');
		
		// Правила для проверки форм.
		$this->form_validation->set_rules('books[category]', 'Категория', 'in_list['.implode(',', array_keys($this->categories['by_id'])).']');
		$this->form_validation->set_rules('books[title]', 'Тема', 'trim|required|max_length[250]|min_length[10]');
		$this->form_validation->set_rules('books[year]', 'Год публикации', 'trim|required|numeric|greater_than[1900]');
		$this->form_validation->set_rules('books[link]', 'Ссылка', 'trim|required|max_length[100]|min_length[10]');
		$this->form_validation->set_rules('books[author][firstname]', 'Автор', 'trim|required|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('books[author][secondname]', 'Автор', 'trim|required|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('books[author][thirdname]', 'Автор', 'trim|required|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('books[author][speciality]', 'Специальность', 'trim|required|max_length[100]|min_length[3]');
		$this->form_validation->set_rules('books[leader][firstname]', 'Руководитель', 'trim|required|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('books[leader][secondname]', 'Руководитель', 'trim|required|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('books[leader][thirdname]', 'Руководитель', 'trim|required|max_length[30]|min_length[3]');
		
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
			'title'             => $this->form['title'],
			'year'              => $this->form['year'],
			'link'              => $this->form['link'],
			'category_id'       => $this->form['category'],
			'author_firstname'  => $this->form['author_firstname'],
			'author_secondname' => $this->form['author_secondname'],
			'author_thirdname'  => $this->form['author_thirdname'],
			'speciality'        => $this->form['speciality'],
			'leader_firstname'  => $this->form['leader_firstname'],
			'leader_secondname' => $this->form['leader_secondname'],
			'leader_thirdname'  => $this->form['leader_thirdname']
		];
	}
}

/* End of file Books_controller.php */
/* Location: ./application/controllers/Admin/Books_controller.php */