<div class="container">
	{alert}
	
	<h1 class="h3 page-header-h1">Добавить работу</h1>
	
	<form method="POST">
		<h3 class="h4 page-header">Основная информация</h3>
		
		<div class="form-group">
			<label>Категория</label>
			{books[category]}
			<select name="books[category]" class="form-control">
				<option value="0">Выберите категорию работы</option>
				{categories}<option value="{id}"{active}>{name}</option>{/categories}
			</select>
		</div>
		
		<div class="form-group">
			<label>Тема работы</label>
			{books[title]}
			<input type="text" class="form-control" name="books[title]" value="{title}" placeholder="Сервисы по продвижению серверов многопользовательских проектов">
		</div>
		
		<div class="form-group">
			<label>Год публикации</label>
			{books[year]}
			<input type="year" class="form-control" name="books[year]" value="{year}" placeholder="2016">
		</div>
		
		<div class="form-group">
			<label>Ссылка на материал</label>
			{books[link]}
			<input type="text" class="form-control" name="books[link]" value="{link}" placeholder="http://default.ru/link/test.html">
		</div>
		
		<h3 class="h4 page-header">Дополнительная информация</h3>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label>Автор</label>
					{books[author][secondname]}
					<input type="text" class="form-control" name="books[author][secondname]" value="{author_secondname}" placeholder="Фамилия">
				</div>
				
				<div class="form-group">
					{books[author][firstname]}
					<input type="text" class="form-control" name="books[author][firstname]" value="{author_firstname}" placeholder="Имя">
				</div>
				
				<div class="form-group">
					{books[author][thirdname]}
					<input type="text" class="form-control" name="books[author][thirdname]" value="{author_thirdname}" placeholder="Отчество">
				</div>
				
				<div class="form-group">
					<label>Специальность</label>
					{books[author][speciality]}
					<input type="text" class="form-control" name="books[author][speciality]" value="{speciality}" placeholder="Прикладная информатика">
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group">
					<label>Руководитель</label>
					{books[leader][secondname]}
					<input type="text" class="form-control" name="books[leader][secondname]" value="{leader_secondname}" placeholder="Фамилия">
				</div>
				
				<div class="form-group">
					{books[leader][firstname]}
					<input type="text" class="form-control" name="books[leader][firstname]" value="{leader_firstname}" placeholder="Имя">
				</div>
				
				<div class="form-group">
					{books[leader][thirdname]}
					<input type="text" class="form-control" name="books[leader][thirdname]" value="{leader_thirdname}" placeholder="Отчество">
				</div>
			</div>
		</div>
		
		<div class="well">
			<div class="row">
				<div class="col-xs-5">
					<a href="/user/admin/books" class="btn btn-default"><span class="glyphicon glyphicon-remove icon"></span> Отменить</a>
				</div>
				
				<div class="col-xs-7 text-right">
					<input type="hidden" name="{crsf.token_name}" value="{crsf.hash}">
					<button class="btn btn-success" name="add" value="1" type="submit"><span class="glyphicon glyphicon-floppy-save icon"></span> Добавить работу</button>
				</div>
			</div>
		</div>
	</form>
</div>