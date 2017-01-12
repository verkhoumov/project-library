<div class="container">
	{alert}
	
	<h1 class="h3 page-header-h1">{group_name} <span class="badge" rel="tooltip" title="Всего пользователей">{users.count}</span></h1>
	
	<div class="row">
		<div class="col-sm-10">
			<p>{description}</p>
		</div>
		
		<div class="col-sm-2">
			<a href="/user/admin/{user_group}/add" class="btn btn-block btn-sm btn-success text-overflow"><span class="glyphicon glyphicon-plus icon"></span> Добавить</a>
		</div>
	</div>
	
	<div class="users-list list-group">
		{users}<div class="item admin list-group-item{online}">
			<div class="row">
				<div class="col-sm-3 col-sm-push-7" style="margin-bottom: 8px;">
					<small><span class="text-muted time" rel="tooltip" title="{reg.date}">Зарегистрирован {reg.timespan} назад</span><span class="text-muted time">,</span> <span class="text-muted time" rel="tooltip" title="{online.date}">{online.name}</span></small>
				</div>

				<div class="col-sm-7 col-sm-pull-3">
					<p class="text-muted"><i>{login}</i></p>
					<p>{name}</p>
					<p class="text-muted no-margin-bottom-sm text-overflow">{email}</p>
				</div>
				
				<div class="col-sm-2">
					<div class="row">
						<div class="col-sm-12 col-xs-6 col-sm-push-0 col-xs-push-6 edit-button">
							<a href="/user/admin/{user_group}/edit/{id}" class="btn btn-block btn-sm btn-warning text-overflow"><span class="glyphicon glyphicon-pencil icon"></span> Редактировать</a>
						</div>
						
						<div class="col-sm-12 col-xs-6 col-sm-pull-0 col-xs-pull-6">
							<a href="/user/admin/{user_group}/delete/{id}" class="btn btn-block btn-sm btn-danger text-overflow"><span class="glyphicon glyphicon-ban-circle icon"></span> Удалить</a>
						</div>
					</div>
				</div>
			</div>
		</div>{/users}
	</div>
	
	<?php if (empty($users)): ?>
	<div class="item">
		<p>Ни одного <span class="text-lowercase">{group_name_plural}</span> нет</p>
	</div>
	<?php endif; ?>
</div>