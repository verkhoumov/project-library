{users}<div class="item">
	<p><small class="text-muted time" rel="tooltip" title="{reg.date}">Зарегистрирован {reg.timespan} назад</small></p>
	<p>{name} <span class="text-muted">({login})</span></p>
	<p class="no-margin-bottom"><span class="text-muted">{email}</span></p>
</div>{/users}

<?php if (empty($users)): ?>
<div class="item">
	<p>Новостей нет</p>
</div>
<?php endif; ?>