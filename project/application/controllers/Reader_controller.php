<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Читатель.
 */
class Reader_controller extends CI_Controller
{
	/**
	 *  Список материалов.
	 *  
	 *  @var  array
	 */
	protected $books = [];

	/**
	 *  Список годов материалов.
	 *  
	 *  @var  array
	 */
	protected $years = [];

	/**
	 *  Список специальностей студентов.
	 *  
	 *  @var  array
	 */
	protected $specialities = [];
	
	/**
	 *  Группы пользователей, имеющие доступ к текущему разделу сайта.
	 *  
	 *  @var  array
	 */
	protected $access = [1, 2];

	// ------------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		// Библиотека для парсинга страниц.
		$this->load->library('parser');

		// Новости.
		$this->load->model('news_model');
		$this->data['news'] = get_news_data($this->news_model->_get(0, 5));
	}
	
	/**
	 *  Читатель: главная страница.
	 *  
	 *  @return  void
	 */
	public function index()
	{
		// Если доступ к разделу разрешён.
		if (check_access($this->access))
		{
			// Пользователь.
			$this->user = get_user();
			
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Читальный зал',
				'description' => '',
				'mode'        => 'user-role'
			];
	
			$this->data['attention'] = [
				'person' => $this->user['user']['group']
			];
	
			$this->data['tmpl'] = [
				'news'  => $this->parser->parse('blocks/news', $this->data, TRUE)
			];
	
			// Компоновка шаблонов.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', $this->user);
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('reader/index', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}

	/**
	 *  Читатель: Поиск работ.
	 *  
	 *  @param   string  $mode  [Категория материалов]
	 *  @return  void
	 */
	public function search($mode = '')
	{
		// Если доступ к разделу разрешён.
		if (check_access($this->access))
		{
			// Режим доступа.
			$mode = (string) $mode;
			
			// Пользователь.
			$this->user = get_user();
	
			// Загрузка данных.
			$this->load->model('books_model');
			$this->categories   = $this->books_model->_get_categories();
			$this->years        = $this->books_model->_get_years($this->categories['by_code'][$mode]['id']);
			$this->specialities = $this->books_model->_get_specialities($this->categories['by_code'][$mode]['id']);
	
			// Страница не найдена.
			if (!array_key_exists($mode, $this->categories['by_code']))
			{
				show_404();
			}
	
			// Обработчик формы поиска.
			self::form();
	
			// Инициализация поиска материалов.
			if ($this->form['submit'])
			{
				$this->books = get_books_data($this->books_model->_search($this->form, $this->categories['by_code'][$mode]['id']), $this->categories['by_id']);
			}
	
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => ($this->categories['by_code'][$mode]['search']) . ' — Читальный зал',
				'description' => '',
				'mode'        => 'user-role'
			];
	
			$this->data['attention'] = [
				'person' => $this->user['user']['group']
			];
	
			// Кнопка возврата.
			$this->data['backward'] = [
				'name' => 'Назад',
				'link' => '/user/reader'
			];
	
			// Данные.
			$this->data['tmpl'] = [
				'title'           => $this->categories['by_code'][$mode]['search'],
				'news'            => $this->parser->parse('blocks/news', $this->data, TRUE),
				'books'           => $this->books,
				'result'          => self::get_result_data($this->books),
				'crsf.token_name' => $this->security->get_csrf_token_name(),
				'crsf.hash'       => $this->security->get_csrf_hash(),
				'years'           => get_years_data($this->years, $this->form['year']),
				'specialities'    => get_specialities_data($this->specialities, $this->form['speciality'])
			];
	
			$this->data['tmpl'] = array_merge($this->data['tmpl'], $this->form, $this->form['errors']);
	
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('reader/search', $this->data['tmpl']);
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
		// Исходные данные формы.
		$this->form = [
			'submit'     => FALSE,
			'query'      => '',
			'year'       => 0,
			'speciality' => 'all',
			'errors'     => [
				'search[query]'      => '',
				'search[year]'       => '',
				'search[speciality]' => ''
			]
		];

		// Если форма была отправлена.
		if ($this->input->get('search') && check_access($this->access))
		{
			// Статус формы.
			$this->form['submit'] = TRUE;

			// Форма.
			$form = (array) $this->input->get('search');

			// Строка запроса.
			if (isset($form['query']))
			{
				$this->form['query'] = htmlspecialchars(trim((string) $form['query']), ENT_QUOTES);
			}

			// Год публикации.
			if (!empty($form['year']))
			{
				$this->form['year'] = (integer) $form['year'];
			}

			// Специальность.
			if (isset($form['speciality']))
			{
				$this->form['speciality'] = (string) $form['speciality'];
			}

			// Вывод ошибок.
			if (!self::validation())
			{
				$this->form['errors'] = set_errors_wrap($this->form['errors']);
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
		$this->form_validation->set_message('max_length', 'Максимальное количество символов в запросе — {param}.');
		$this->form_validation->set_message('min_length', 'Минимальное количество символов в запросе — {param}.');
		$this->form_validation->set_message('in_list', 'Выбрано несуществующее значение.');
		
		// Правила для проверки форм.
		$this->form_validation->set_rules('search[query]', 'Строка запроса', 'trim|max_length[30]|min_length[3]');
		$this->form_validation->set_rules('search[year]', 'Год публикации', 'in_list[0,'.implode(',', $this->years).']');
		$this->form_validation->set_rules('search[speciality]', 'Специальность', 'in_list[all,'.implode(',', $this->specialities).']');

		// Проверка формы.
		if ($this->form_validation->run() === FALSE)
		{
			// Объединение ошибок.
			$this->form['errors'] = array_merge($this->form['errors'], $this->form_validation->error_array());
			
			return FALSE;
		}

		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 *  Строка результата поиска работ.
	 *  
	 *  @param   array  $data  [Список найденных работ]
	 *  @return  string
	 */
	private function get_result_data($data)
	{
		$data = (array)	$data;
		$result = '';

		if (!empty($data))
		{
			$count = count($data);

			$result .= get_word_plural($count, ['Найдена', 'Найдено', 'Найдено']);
			$result .= ' '.$count.' ';
			$result .= get_word_plural($count, ['работа', 'работы', 'работ']);
			$result .= ':';
		}
		else
		{
			if ($this->input->get('search'))
			{
				$result = 'Ни одной работы не найдено. Измените условия поиска.';
			}
		}

		return $result;
	}
}

/* End of file Reader_controller.php */
/* Location: ./application/controllers/Reader_controller.php */