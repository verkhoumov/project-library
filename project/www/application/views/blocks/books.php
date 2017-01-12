{books}<div class="item">
	<div class="row">
		<div class="col-sm-6 col-sm-push-6 text-sm-right"><p><small class="text-muted time" rel="tooltip" title="{date}">{timespan} назад</small></p></div>
		<div class="col-sm-6 col-sm-pull-6"><p><i>{category}, {year}</i></p></div>
	</div>

	<p><span class="text-muted">Тема:</span><br><strong>{name}</strong></p>

	<div class="row">
		<div class="col-sm-6">
			<p class="no-margin-bottom-sm"><span class="text-muted">Автор:</span><br>{author}, {speciality}</p>
		</div>
		<div class="col-sm-6">
			<p class="no-margin-bottom"><span class="text-muted">Руководитель:</span><br>{leader}</p>
		</div>
	</div>
</div>{/books}

<?php if (empty($books)): ?>
<div class="item">
	<p>Новостей нет</p>
</div>
<?php endif; ?>