{news}<div class="item">
	<small class="text-muted time" rel="tooltip" title="{date}">{timespan} назад</small>
	<h3 class="h4">{title}</h3>
	<p class="no-margin-bottom">{message}</p>
</div>{/news}

<?php if (empty($news)): ?>
<div class="item">
	<p>Новостей нет</p>
</div>
<?php endif; ?>