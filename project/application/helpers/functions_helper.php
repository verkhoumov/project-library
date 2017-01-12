<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Обработка данных новостей.
 *  @param   array   $data  [Данные]
 *  @return  array
 */
function get_news_data($data = [])
{
	$data = (array) $data;
	$result = [];

	if (!empty($data))
	{
		foreach ($data as $key => $news)
		{
			$datetime = strtotime($news['date_add']);

			$result[$key] = $news;

			$result[$key] = [
				'id'       => $news['id'],
				'title'    => htmlspecialchars_decode(trim((string) $news['title']), ENT_QUOTES),
				'message'  => htmlspecialchars_decode(trim((string) $news['message']), ENT_QUOTES),
				'date'     => get_date($datetime, '@d @t2, @y', 'b'),
				'timespan' => get_timespan($datetime, time(), 1, true)
			];
		}
	}

	return $result;
}

/**
 *  Обработка данных работ студентов.
 *  @param   array   $data  [Данные]
 *  @return  array
 */
function get_books_data($data = [], $categories = [])
{
	$data       = (array) $data;
	$categories = (array) $categories;
	
	$result = [];

	if (!empty($data))
	{	
		foreach ($data as $key => $books)
		{
			$datetime = strtotime($books['date_add']);
			$author = implode(' ', [$books['author_secondname'], $books['author_firstname'], $books['author_thirdname']]);
			$leader = implode(' ', [$books['leader_secondname'], $books['leader_firstname'], $books['leader_thirdname']]);

			$result[$key] = $books;
			
			$result[$key] = [
				'id'                => $books['id'],
				'year'              => (integer) $books['year'],
				'date'              => get_date($datetime, '@d @t2, @y', 'b'),
				'timespan'          => get_timespan($datetime, time(), 1, true),
				'category'          => $categories[$books['category_id']]['name'],
				'name'              => htmlspecialchars_decode(trim((string) $books['title']), ENT_QUOTES),
				'link'              => htmlspecialchars_decode(trim((string) $books['link']), ENT_QUOTES),
				'author'            => htmlspecialchars_decode(trim((string) $author), ENT_QUOTES),
				'leader'            => htmlspecialchars_decode(trim((string) $leader), ENT_QUOTES),
				'speciality'        => htmlspecialchars_decode(trim((string) $books['speciality']), ENT_QUOTES),
				'author_firstname'  => htmlspecialchars_decode(trim((string) $books['author_firstname']), ENT_QUOTES),
				'author_secondname' => htmlspecialchars_decode(trim((string) $books['author_secondname']), ENT_QUOTES),
				'author_thirdname'  => htmlspecialchars_decode(trim((string) $books['author_thirdname']), ENT_QUOTES),
				'leader_firstname'  => htmlspecialchars_decode(trim((string) $books['leader_firstname']), ENT_QUOTES),
				'leader_secondname' => htmlspecialchars_decode(trim((string) $books['leader_secondname']), ENT_QUOTES),
				'leader_thirdname'  => htmlspecialchars_decode(trim((string) $books['leader_thirdname']), ENT_QUOTES)
			];
		}
	}

	return $result;
}

/**
 *  Категории материалов.
 *  @param   array    $categories  [Список категорий]
 *  @param   integer  $active_id   [Активная категория]
 *  @return  array
 */
function get_categories_data($categories, $active_id = 0)
{
	$categories = (array) $categories;
	$active_id = (integer) $active_id;
	
	$result = [];
	
	if (!empty($categories))
	{
		foreach ($categories as $key => $value)
		{
			$result[$key] = $value;

			$result[$key]['id'] = $key;
			$result[$key]['active'] = ($active_id > 0 && $key == $active_id) ? ' selected="selected"' : '';
		}
	}
	
	return $result;
}

/**
 *  Список пользователей.
 *  @param   array   $data   [Пользователи]
 *  @param   string  $group  [Группы]
 *  @return  array
 */
function get_users_data($data = [], $group = '')
{
	$data = (array) $data;
	$group = (string) $group;
	$result = [];

	if (!empty($data))
	{
		foreach ($data as $key => $user)
		{
			$registration = strtotime($user['date_add']);
			$online = strtotime($user['date_online']);
			$name = implode(' ', [$user['secondname'], $user['firstname'], $user['thirdname']]);

			$result[$key] = $user;

			$result[$key] = [
				'id'              => $user['id'],
				'login'           => htmlspecialchars_decode(trim((string) $user['login']), ENT_QUOTES),
				'name'            => htmlspecialchars_decode(trim((string) $name), ENT_QUOTES),
				'email'           => htmlspecialchars_decode(trim((string) $user['email']), ENT_QUOTES),
				'reg.date'        => get_date($registration, '@d @t2, @y', 'b'),
				'reg.timespan'    => get_timespan($registration, time(), 1, true),
				'online.date'     => get_date($online, '@d @t2, @y', 'b'),
				'online.timespan' => get_timespan($online, time(), 1, true),
				'user_group'      => $group,
				'online'          => ''
			];

			if ($online < 0)
			{
				$result[$key]['online.date'] = 'Ни разу не был в сети';
				$result[$key]['online.name'] = '<span class="text-danger">не активирован</span>';
				$result[$key]['online'] = ' noactive';
			}
			elseif ($online < (time() - 60 * 15))
			{
				$result[$key]['online.name'] = 'был в сети '.$result[$key]['online.timespan'].' назад';
			}
			else
			{
				$result[$key]['online.date'] = 'В сети';
				$result[$key]['online.name'] = '<span class="text-success">прямо сейчас на сайте</span>';
				$result[$key]['online'] = ' online';
			}
		}
	}

	return $result;
}

/**
 *  Список доступных годов публикации материалов.
 *  @param   array    $data    [Список годов]
 *  @param   integer  $active  [Активный год]
 *  @return  array
 */
function get_years_data($data = [], $active = 0)
{
	$data = (array) $data;
	$active = (integer) $active;
	$result = [];

	if (!empty($data))
	{
		foreach ($data as $key => $value)
		{
			$year = (integer) $value;

			$result[$year] = [
				'year' => $year,
				'active' => $year == $active ? ' selected="selected"' : ''
			];
		}
	}

	return $result;
}

/**
 *  Список доступных специальностей.
 *  @param   array   $data    [Специальности]
 *  @param   string  $active  [Активная]
 *  @return  array
 */
function get_specialities_data($data = [], $active = '')
{
	$data = (array) $data;
	$active = (string) $active;
	$result = [];

	if (!empty($data))
	{
		foreach ($data as $key => $value)
		{
			$speciality = (string) $value;
			
			$result[$speciality] = [
				'speciality' => $speciality,
				'active' => $speciality == $active ? ' selected="selected"' : ''
			];
		}
	}

	return $result;
}

/**
 *  Разница между датами.
 *  @param   integer  $seconds  [Текущее кол-во секунд]
 *  @param   string   $time     [Меньшее кол-во секунд]
 *  @param   integer  $units    [Число элементов]
 *  @param   boolean  $extra    [Форма названий дат]
 *  @return  string
 */
function get_timespan($seconds = 1, $time = '', $units = 7, $extra = false)
{
	is_numeric($seconds) OR $seconds = 1;
	is_numeric($time) OR $time = time();
	is_numeric($units) OR $units = 7;

	$extra_map = [
		'week'   => '',
		'minute' => '',
		'second' => ''
	];

	// Год.
	$seconds = ($time <= $seconds) ? 1 : $time - $seconds;

	$str   = [];
	$years = floor($seconds / 31557600);

	if ($years > 0)
	{
		$str[] = $years.get_word_plural($years, [' год', ' года', ' лет']);
	}

	// Месяц.
	$seconds -= $years * 31557600;
	$months  = floor($seconds / 2629743);

	if (count($str) < $units && ($years > 0 OR $months > 0))
	{
		if ($months > 0)
		{
			$str[] = $months.get_word_plural($months, [' месяц', ' месяца', ' месяцев']);
		}

		$seconds -= $months * 2629743;
	}

	// Неделя.
	$weeks = floor($seconds / 604800);

	if (count($str) < $units && ($years > 0 OR $months > 0 OR $weeks > 0))
	{
		if ($weeks > 0)
		{
			if ($extra)
			{
				$extra_map['week'] = ' неделю';
			}

			$str[] = $weeks.get_word_plural($weeks, [' неделя', ' недели', ' недель'], $extra_map['week']);
		}

		$seconds -= $weeks * 604800;
	}

	// День.
	$days = floor($seconds / 86400);

	if (count($str) < $units && ($months > 0 OR $weeks > 0 OR $days > 0))
	{
		if ($days > 0)
		{
			$str[] = $days.get_word_plural($days, [' день', ' дня', ' дней']);
		}

		$seconds -= $days * 86400;
	}

	// Час.
	$hours = floor($seconds / 3600);

	if (count($str) < $units && ($days > 0 OR $hours > 0))
	{
		if ($hours > 0)
		{
			$str[] = $hours.get_word_plural($hours, [' час', ' часа', ' часов']);
		}

		$seconds -= $hours * 3600;
	}

	// Минута.
	$minutes = floor($seconds / 60);

	if (count($str) < $units && ($days > 0 OR $hours > 0 OR $minutes > 0))
	{
		if ($minutes > 0)
		{
			if ($extra)
			{
				$extra_map['minute'] = ' минуту';
			}

			$str[] = $minutes.get_word_plural($minutes, [' минута', ' минуты', ' минут'], $extra_map['minute']);
		}

		$seconds -= $minutes * 60;
	}

	// Секунда.
	if (count($str) === 0)
	{
		if ($extra)
		{
			$extra_map['second'] = ' секунду';
		}

		$str[] = $seconds.get_word_plural($seconds, [' секунда', ' секунды', ' секунд'], $extra_map['second']);
	}

	return implode(', ', $str);
}

/**
 *  Формирование даты.
 *
 *  $format (по-умолчанию: [@d @t2] = [сегодня в 16:22]):
 *  @d - day (сегодня, 17 ноября)
 *  @t1 - time (18:35)
 *  @t2 - time (в 18:35)
 *  @y - year (2014)
 *
 *  $size (по-умолчанию: [b] = [Сегодня]):
 *  s - small (сегодня)
 *  b - big (Сегодня)
 *
 *  @param $time integer / Временная метка
 *  @param $format string / Формат получения результата
 *  @param $size string / Размер первой буквы
 *
 *  @return string
 */
function get_date($time, $format = '@d @t2', $size = 'b')
{
	// Исходные данные.
	$date_diff = 0;
	$start_day = mktime(0, 0, 0, date('m'), date('d'), date('Y')) + 60*60*2;

	// Входные данные.
	$time = (int) $time;
	$format = (string) $format;
	$size = (string) $size;

	$time += $date_diff;

	/*
	 *  Проверка данных.
	 */
	if ($format == '' || preg_match('#[^dty12\@\s\,]+#i', $format))
	{
		return false;
	}

	if ($size != 'b' && $size != 's')
	{
		$size = 'b';
	}

	/*
	 *  Если требуется указать день, проводим анализ временной метки
	 *  и выбираем наиболее подходящее слово.
	 */
	if (strpos($format, '@d') !== false)
	{
		// Например: сегодня.
		if ($time > ($start_day - 86400) && $time < ($start_day + 86400 * 2))
		{
			// Время, когда заканчивается вчерашний, текущий и 
			// завтрашний дни.
			$end_day_previous = $start_day;
			$end_day_current = $start_day + 86400;
			$end_day_next = $start_day + 86400 * 2;

			$days = [
				['s_name' => 'вчера', 'b_name' => 'Вчера', 'time' => $end_day_previous],
				['s_name' => 'сегодня', 'b_name' => 'Сегодня', 'time' => $end_day_current],
				['s_name' => 'завтра', 'b_name' => 'Завтра', 'time' => $end_day_next]
			];

			// Выбираем нужный день и формат его написания.
			foreach ($days as $day_name)
			{
				if ($time <= $day_name['time'])
				{
					$current_name = $day_name[$size.'_name'];
					$format = str_replace('@d', $current_name, $format);

					break;
				}
			}
		}

		// Например: 17 ноября.
		else
		{
			$months = [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

			// Название месяца, указанного во временной метке.
			$current_month_number = date('n', $time);
			$current_month = $months[$current_month_number];

			// Число, указанное во временной метке.
			$current_month_day = date('j', $time).' ';

			// Формирование результата.
			$format = str_replace('@d', $current_month_day.$current_month, $format);
		}
	}

	/*
	 *  Определение формата основной временной метки: года, часы и минуты.
	 */
	$year = date('Y', $time);
	$hour = date('H:i', $time);
	$tmpl_timestamp = ['@t1' => $hour, '@t2' => 'в '.$hour, '@y' => $year];

	foreach ($tmpl_timestamp as $pattern => $timestamp)
	{
		$format = str_replace($pattern, $timestamp, $format);
	}

	return $format;
}

/**
 *  Склонение слова по числу.
 *  @param   integer  $n      [Число]
 *  @param   array    $words  [Список слов]
 *  @param   string   $third  [Особое склонение]
 *  @return  string
 */
function get_word_plural($n, $words = [], $third = '')
{
	$n = abs($n) % 100;
	$n1 = $n % 10;
	$third = (string) $third;

	if ($n == 0 or $n1 == 0) 
	{
		return $words[2];
	}

	if ($n > 10 && $n < 20)
	{
		return $words[2];
	}

	if ($n1 > 1 && $n1 < 5)
	{
		return $words[1];
	}

	if ($n1 == 1)
	{
		if ($third != '')
		{
			return $third;
		}

		return $words[0];
	}

	return $words[2];
}

/**
 *  Обёртка для ошибок, выводимых на экран при обработке форм.
 *  @param   array/string   $errors  [Список ошибок]
 *  @param   string         $left    [Левая обёртка]
 *  @param   string         $right   [Правая обёртка]
 *  @return  array
 */
function set_errors_wrap($errors, $left = '<div class="alert alert-danger" role="alert">', $right = '</div>')
{
	$left = (string) $left;
	$right = (string) $right;

	$result = [];

	if (!empty($errors))
	{
		// Если передан список ошибок в виде массива.
		if (is_array($errors))
		{
			foreach ($errors as $error_id => $error_message)
			{
				$result[$error_id] = get_wrapped_error($error_message, $left, $right);
			}
		}

		// Если передана только 1 ошибка в виде строки.
		elseif (is_string($errors))
		{
			$result = get_wrapped_error($errors, $left, $right);
		}
	}

	return $result;
}

/**
 *  Обернуть ошибку в оболочку.
 *  @param   string  $error  [Текст ошибки]
 *  @param   string  $left   [Левая часть оболочки]
 *  @param   string  $right  [Правая часть оболочки]
 *  @return  string
 */
function get_wrapped_error($error, $left, $right)
{
	return ($error != '' ? $left.$error.$right : '');
}

/**
 *  Вывод сообщения об ошибке.
 *  
 *  @param   string  $message  [Текст сообщения]
 *  @param   string  $type     [Тип уведомления]
 *  @return  string
 */
function set_page_alert($message = '', $type = 'success')
{
	$message = (string) $message;
	$type = (string) $type;
	
	$result = '<div class="alert alert-'.$type.'" role="alert">';
	$result .= $message;
	$result .= '</div>';
	
	return $result;
	
}

/**
 *  Информация о пользователе.
 *  
 *  @return  array
 */
function get_user()
{
	$result = [];
	$CI = &get_instance();
	
	$user = $CI->config->item('user');
	
	if (!empty($user))
	{
		$result['user'] = (array) $user;
	}
	
	return $result;
}

/**
 *  Имя пользователя.
 *  
 *  @param   array  $user  [Информация о пользователе]
 *  @return  string
 */
function get_user_name($user = [])
{
	$user = (array) $user;
	$result = '';
	
	if (!empty($user))
	{
		$result = $user['login'];
		
		if (!empty($user['firstname']))
		{
			$result = htmlspecialchars(trim((string) $user['firstname']), ENT_QUOTES);
			
			if (!empty($user['secondname']))
			{
				$result .= ' ';
				$result .= htmlspecialchars(trim((string) $user['secondname']), ENT_QUOTES);
			}
		}
	}
	
	return $result;
}

/**
 *  Хеширование пароля.
 *  
 *  @param   string  $password  [Исходный пароль]
 *  @return  string
 */
function get_password_hash($password = '')
{
	$password = trim((string) $password);
	
	if ($password == '')
	{
		return '';
	}
	
	return md5('hUw6P?s@Xs'.$password);
}

/**
 *  Нормализация логина для работы с БД.
 *  
 *  @param   string  $login  [Логин]
 *  @return  string
 */
function get_login($login = '')
{
	$login = (string) $login;
	
	if ($login == '')
	{
		return '';
	}
	
	return mb_strtolower($login, 'UTF-8');
}

/**
 *  Проверка наличия прав доступа к разделу сайта.
 *  
 *  @param   array    $groups  [Группы пользователей]
 *  @param   boolean  $show    [Уведомление об ошибке]
 *  @return  boolean
 */
function check_access($groups, $show = true)
{
	$groups = (array) $groups;
	
	$user = get_user();
	
	if (empty($user))
	{
		if ($show)
		{
			show_noaccess_page('nologin');
		}
		
		return false;
	}
	else
	{
		$user = $user['user'];
		
		if (!in_array($user['group_id'], $groups))
		{
			if ($show)
			{
				show_noaccess_page('noaccess');
			}
			
			return false;
		}
	}
	
	return true;
}

/**
 *  Вывод уведомления об ошибке.
 *  
 *  @param   string  $file  [Имя шаблона]
 *  @return  void
 */
function show_noaccess_page($file = 'noaccess')
{
	$file = (string) $file;
	
	$CI = &get_instance();
	$CI->load->library('parser');
	
	$page = [
		'title'       => 'Доступ ограничен',
		'description' => '',
		'mode'        => ''
	];
	
	$CI->parser->parse('header', $page);
	$CI->load->view('menu', get_user());
	$CI->load->view($file);
	$CI->load->view('footer');
}

/* End of file functions_helper.php */
/* Location: ./application/helpers/functions_helper.php */