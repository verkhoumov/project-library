<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Новости.
 */
class News_controller extends CI_Controller
{
	/**
	 *  Информация о новости.
	 *  
	 *  @var  array
	 */
	protected $news = [
		'title' => '',
		'message' => ''
	];
	
	/**
	 *  Группы пользователей, имеющих доступ к разделу.
	 *  
	 *  @var  array
	 */
	protected $access = [1];
	
	/**
	 *  Уведомление об ошибке.
	 *  
	 *  @var  string
	 */
	protected $alert = '';

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
			'link' => '/user/admin/news',
			'addr' => 'user/admin/news'
		];
		
		if ($status = $this->input->get('alert'))
		{
			if ($status == 'add')
			{
				$this->alert = set_page_alert('Новость добавлена! Здесь Вы можете изменить информацию!');
			}
			elseif ($status == 'delete')
			{
				$this->alert = set_page_alert('Новость успешно удалена!');
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 *  Список всех новостей сайта.
	 *  
	 *  @return  void
	 */
	public function index()
	{
		// Если пользователь имеет доступ к странице.
		if (check_access($this->access))
		{
			$this->load->model('news_model');
			
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Новости — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
	
			$this->data['backward'] = [
				'name' => 'Назад',
				'link' => '/user/admin'
			];
			
			// Данные для шаблонов.
			$this->data['tmpl'] = [
				'news'  => get_news_data($this->news_model->_get()),
				'alert' => $this->alert
			];
			
			$this->data['tmpl']['news.count'] = count($this->data['tmpl']['news']);
	
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/news/index', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}
	
	/**
	 *  Добавление новости на сайта.
	 *
	 *  @return  void
	 */
	public function add()
	{
		// Если пользователь имеет доступ к странице.
		if (check_access($this->access))
		{
			$this->load->model('news_model');
			
			// Обработчик формы.
			self::form();
			
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Добавить новость — Новости — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
			
			// Данные для шаблонов.
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
			$this->parser->parse('admin/news/add', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}
	
	/**
	 *  Изменение существующей новости.
	 *  
	 *  @param   integer  $news_id  [ID новости]
	 *  @return  void
	 */
	public function edit($news_id = 0)
	{
		// Если пользователь имеет доступ к странице.
		if (check_access($this->access))
		{
			$this->load->model('news_model');

			// Проверка новости по ID.
			$this->news_id = (integer) $news_id;
			
			if ($this->news_id <= 0)
			{
				show_404();
			}

			$this->news = $this->news_model->_get($this->news_id);
			
			if (empty($this->news))
			{
				show_404();
			}

			// Обработчик формы.
			self::form();
			
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Редактировать новость — Новости — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
			
			// Данные для шаблонов.
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
			$this->parser->parse('admin/news/edit', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}
	
	/**
	 *  Удаление новости с сайта.
	 *  
	 *  @param   integer  $news_id  [ID новости]
	 *  @return  void
	 */
	public function delete($news_id = 0)
	{
		// Если пользователь имеет доступ к странице.
		if (check_access($this->access))
		{
			$this->load->model('news_model');

			// Проверка новости по ID.
			$this->news_id = (integer) $news_id;
			
			if ($this->news_id <= 0)
			{
				show_404();
			}

			$this->news = $this->news_model->_get($this->news_id);
			
			if (empty($this->news))
			{
				show_404();
			}
			
			// Обработчик формы.
			self::form();
			
			// Параметры страницы.
			$this->data['page'] = [
				'title'       => 'Удалить новость — Новости — Панель управления',
				'description' => '',
				'mode'        => 'user-role'
			];
			
			// Данные для шаблонов.
			$this->data['tmpl'] = [
				'news'            => $this->parser->parse('blocks/news', ['news' => get_news_data([$this->news])], TRUE),
				'crsf.token_name' => $this->security->get_csrf_token_name(),
				'crsf.hash'       => $this->security->get_csrf_hash(),
				'alert'           => $this->alert
			];
			
			// Сборка страницы.
			$this->parser->parse('header', $this->data['page']);
			$this->load->view('menu', get_user());
			$this->parser->parse('blocks/attention', $this->data['attention']);
			$this->parser->parse('blocks/backward', $this->data['backward']);
			$this->parser->parse('admin/news/delete', $this->data['tmpl']);
			$this->load->view('footer');
		}
	}

	// ------------------------------------------------------------------------
	
	/**
	 *  Обработчик формы добавления новости.
	 *  
	 *  @return  void
	 */
	private function form()
	{
		$this->form = [
			'submit'  => FALSE,
			'title'   => $this->news['title'],
			'message' => $this->news['message'],
			'errors' => [
				'news[title]'   => '',
				'news[message]' => ''
			]
		];
		
		// Добавление и редактирования материалов.		
		if (($this->input->post('add') || $this->input->post('edit')) && check_access($this->access))
		{
			// Статус формы.
			$this->form['submit'] = TRUE;

			// Форма.
			$form = (array) $this->input->post('news');

			// Заголовок.
			if (isset($form['title']))
			{
				$this->form['title'] = htmlspecialchars(trim((string) $form['title']), ENT_QUOTES);
			}
			
			// Текст сообщения.
			if (isset($form['message']))
			{
				$this->form['message'] = htmlspecialchars(trim((string) $form['message']), ENT_QUOTES);
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
					$this->news_id = $this->news_model->_set($this->form['data']);
					
					// Переход на страницу редактирования.
					redirect($this->config->item('base_url').$this->data['backward']['addr'].'/edit/'.$this->news_id.'?alert=add', 'location', 301);
				}
				elseif ($this->input->post('edit'))
				{
					$this->news_model->_update($this->news_id, $this->form['data']);
					$this->alert = set_page_alert('Новая информация успешно сохранена!');
				}
			}
		}
		
		// Удаление материала.
		if ($this->input->post('delete') && check_access($this->access))
		{
			if ($this->news_model->_unset($this->news_id))
			{
				redirect($this->config->item('base_url').$this->data['backward']['addr'].'?alert=delete', 'location', 301);
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
		
		// Правила для проверки форм.
		$this->form_validation->set_rules('news[title]', 'Заголовок', 'trim|max_length[150]|min_length[10]');
		$this->form_validation->set_rules('news[message]', 'Сообщение', 'trim|max_length[1000]|min_length[10]');

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
	 *  Данные из формы.
	 *  
	 *  @return  array
	 */
	private function get_form_data()
	{
		return [
			'title'   => $this->form['title'],
			'message' => $this->form['message']
		];
	}
}

/* End of file News_controller.php */
/* Location: ./application/controllers/Admin/News_controller.php */