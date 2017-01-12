<div class="container">
	{alert}
	
	<h1 class="h3 page-header-h1">Редактировать новость</h1>
	
	<form method="POST">
		<div class="form-group">
			<label>Заголовок</label>
			{news[title]}
			<input type="text" class="form-control" name="news[title]" value="{title}" placeholder="Марию Шарапову обвинили в употреблении допинга">
		</div>
		
		<div class="form-group">
			<label>Текст</label>
			{news[message]}
			<textarea class="form-control" name="news[message]" rows="6" placeholder="Сегодня утром стало известно, что Мария Шарапова принимала запрещённый препарат Мелдронат. Мы будем держать вас в курсе всех новостей этого часа!">{message}</textarea>
		</div>

		<div class="well">
			<div class="row">
				<div class="col-xs-5">
					<a href="/user/admin/news" class="btn btn-default"><span class="glyphicon glyphicon-remove icon"></span> Отменить</a>
				</div>
				
				<div class="col-xs-7 text-right">
					<input type="hidden" name="{crsf.token_name}" value="{crsf.hash}">
					<button class="btn btn-success" name="edit" value="1" type="submit"><span class="glyphicon glyphicon-floppy-disk icon"></span> Сохранить</button>
				</div>
			</div>
		</div>
	</form>
</div>