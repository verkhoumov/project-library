<div class="container">
	<h1 class="h3 page-header-h1">{title}</h1>
	
	<p>Поиск позволяет получить наиболее релевантные запросу работы. Здесь можно найти любой интересующий Вас материал.</p>

	<form method="GET">
		<div class="well">
			<div class="form-group">
				{search[query]}
				<input type="text" class="form-control visible-xs" name="search[query]" value="{query}" placeholder="Название работы, автор или руководитель, целиком или только часть">
				<div class="input-group hidden-xs">
					<input type="text" class="form-control" name="search[query]" value="{query}" placeholder="Название работы, автор или руководитель, целиком или только часть">
					<div class="input-group-btn">
						<!--<input type="hidden" name="{crsf.token_name}" value="{crsf.hash}">-->
						<button class="btn btn-block btn-success" type="submit">Найти работы</button>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group no-margin-bottom-sm">
						{search[year]}
						<select name="search[year]" class="form-control">
							<option value="0">Любой год публикации</option>
							{years}<option value="{year}"{active}>{year}</option>{/years}
						</select>
					</div>
				</div>

				<div class="col-sm-8">
					<div class="form-group no-margin-bottom-sm">
						{search[speciality]}
						<select name="search[speciality]" class="form-control">
							<option value="all">Любая специальность</option>
							{specialities}<option value="{speciality}"{active}>{speciality}</option>{/specialities}
						</select>
					</div>
				</div>
			</div>

			<button class="btn btn-block btn-success visible-xs" type="submit">Найти работы</button>
		</div>
	</form>

	<p>{result}</p>

	<div class="books-list list-group">
		{books}<div class="item list-group-item">
			<p><i>{category}, {year}</i></p>
			<p><strong>{name}</strong></p>
			<p class="text-overflow"><a href="{link}" target="_blank">{link}</a></p>

			<div class="row">
				<div class="col-sm-6">
					<p class="no-margin-bottom-sm"><span class="text-muted">Автор:</span><br>{author}<br>({speciality})</p>
				</div>

				<div class="col-sm-6">
					<p class="no-margin-bottom"><span class="text-muted">Руководитель:</span><br>{leader}</p>
				</div>
			</div>
		</div>{/books}
	</div>
</div>
		