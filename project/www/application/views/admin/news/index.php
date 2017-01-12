<div class="container">
	{alert}
	
	<h1 class="h3 page-header-h1">Новости <span class="badge" rel="tooltip" title="Всего новостей">{news.count}</span></h1>
	
	<div class="row">
		<div class="col-sm-10">
			<p>Вы можете добавлять, редактировать и удалять новости.</p>
		</div>
		
		<div class="col-sm-2">
			<a href="/user/admin/news/add" class="btn btn-block btn-sm btn-success text-overflow"><span class="glyphicon glyphicon-plus icon"></span> Добавить</a>
		</div>
	</div>
	
	<div class="news-list list-group">
		{news}<div class="item admin list-group-item">
			<div class="row">
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-2 col-sm-push-10 text-sm-right">
							<p><small class="text-muted time" rel="tooltip" title="{date}">{timespan} назад</small></p>
						</div>
						
						<div class="col-sm-10 col-sm-pull-2">
							<h3 class="h4">{title}</h3>
						</div>

						<div class="col-sm-12">
							<p class="no-margin-bottom-sm">{message}</p>
						</div>
					</div>
				</div>
				
				<div class="col-sm-2">
					<div class="row">
						<div class="col-sm-12 col-xs-6 col-sm-push-0 col-xs-push-6 edit-button">
							<a href="/user/admin/news/edit/{id}" class="btn btn-block btn-sm btn-warning text-overflow"><span class="glyphicon glyphicon-pencil icon"></span> Редактировать</a>
						</div>
						
						<div class="col-sm-12 col-xs-6 col-sm-pull-0 col-xs-pull-6">
							<a href="/user/admin/news/delete/{id}" class="btn btn-block btn-sm btn-danger text-overflow"><span class="glyphicon glyphicon-ban-circle icon"></span> Удалить</a>
						</div>
					</div>
				</div>
			</div>
		</div>{/news}
	</div>
	
	<?php if (empty($news)): ?>
	<div class="news">
		<p>Новостей нет</p>
	</div>
	<?php endif; ?>
</div>
		