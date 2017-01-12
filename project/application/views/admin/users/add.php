<div class="container">
	{alert}
	
	<h1 class="h3 page-header-h1">Добавить <span class="text-lowercase">{group_name_plural}</span></h1>
	
	<form method="POST">
		<h3 class="h4 page-header">Аккаунт</h3>

		<div class="form-group">
			<label>Логин</label>
			{user[login]}
			<input type="text" class="form-control" name="user[login]" value="{login}" placeholder="Dmitriy">
		</div>
		
		<div class="form-group">
			<label>Пароль</label>
			{user[password]}
			<input type="year" class="form-control" name="user[password]" value="{password}" placeholder="H2vAdK284fFh">
		</div>

		<div class="form-group">
			<label>E-mail</label>
			{user[email]}
			<input type="year" class="form-control" name="user[email]" value="{email}" placeholder="default@yandex.ru">
		</div>

		<h3 class="h4 page-header">ФИО</h3>
		
		<div class="form-group">
			{user[secondname]}
			<input type="text" class="form-control" name="user[secondname]" value="{secondname}" placeholder="Фамилия">
		</div>
		
		<div class="form-group">
			{user[firstname]}
			<input type="text" class="form-control" name="user[firstname]" value="{firstname}" placeholder="Имя">
		</div>
		
		<div class="form-group">
			{user[thirdname]}
			<input type="text" class="form-control" name="user[thirdname]" value="{thirdname}" placeholder="Отчество">
		</div>
		
		<div class="well">
			<div class="row">
				<div class="col-xs-5">
					<a href="/user/admin/{user_group}" class="btn btn-default"><span class="glyphicon glyphicon-remove icon"></span> Отменить</a>
				</div>
				
				<div class="col-xs-7 text-right">
					<input type="hidden" name="{crsf.token_name}" value="{crsf.hash}">
					<button class="btn btn-success" name="add" value="1" type="submit"><span class="glyphicon glyphicon-floppy-save icon"></span> Добавить</button>
				</div>
			</div>
		</div>
	</form>
</div>