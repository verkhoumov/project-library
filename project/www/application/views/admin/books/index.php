<div class="container">
	{alert}
	
	<h1 class="h3 page-header-h1">Работы студентов <span class="badge" rel="tooltip" title="Всего работ">{books.count}</span></h1>
	
	<div class="row">
		<div class="col-sm-10">
			<p>Список работ студентов: курсовые, научные и выпускные квалификационные работы. Рекомендуется просматривать страницу с компьютера.</p>
		</div>
		
		<div class="col-sm-2">
			<a href="/user/admin/books/add" class="btn btn-block btn-sm btn-success text-overflow"><span class="glyphicon glyphicon-plus icon"></span> Добавить</a>
		</div>
	</div>
	
	<div class="books-list list-group">
		{books}<div class="item admin list-group-item">
			<div class="row">
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-6 col-sm-push-6 text-sm-right">
							<p><small class="text-muted time" rel="tooltip" title="{date}">{timespan} назад</small></p>
						</div>

						<div class="col-sm-6 col-sm-pull-6">
							<p>{category}, {year}</p>
						</div>
					</div>

					<p><strong>{name}</strong></p>
					<p class="text-overflow"><a href="{link}" target="_blank">{link}</a></p>

					<div class="row">
						<div class="col-sm-6">
							<p class="no-margin-bottom-sm"><span class="text-muted">Автор:</span><br>{author}<br>({speciality})</p>
						</div>

						<div class="col-sm-6">
							<p class="no-margin-bottom-sm"><span class="text-muted">Руководитель:</span><br>{leader}</p>
						</div>
					</div>
				</div>

				<div class="col-sm-2">
					<div class="row">
						<div class="col-sm-12 col-xs-6 col-sm-push-0 col-xs-push-6 edit-button">
							<a href="/user/admin/books/edit/{id}" class="btn btn-block btn-sm btn-warning text-overflow"><span class="glyphicon glyphicon-pencil icon"></span> Редактировать</a>
						</div>
						
						<div class="col-sm-12 col-xs-6 col-sm-pull-0 col-xs-pull-6">
							<a href="/user/admin/books/delete/{id}" class="btn btn-block btn-sm btn-danger text-overflow"><span class="glyphicon glyphicon-ban-circle icon"></span> Удалить</a>
						</div>
					</div>
				</div>
			</div>
		</div>{/books}
	</div>
	
	<?php if (empty($books)): ?>
	<div class="books">
		<p>Работ нет</p>
	</div>
	<?php endif; ?>
</div>
		