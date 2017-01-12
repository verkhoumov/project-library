<div class="panorama">
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<h1 class="page-header-h1">Электронная библиотечная система</h1>
			</div>

			<div class="col-sm-8 panorama-align">
				<ol class="visions scrollable">
					<li class="group">
						<div class="vision">
							<div class="vision-shot">
								<div class="vision-img">
									<a class="vision-link" href="http://e.lanbook.com/" target="_blank">
										<img src="/images/slide-2.jpg" alt="Лань: Электронно-библиотечная система">
									</a>
									<a class="vision-over" href="http://e.lanbook.com/" target="_blank">
										<strong>Электронно-библиотечная система "Лань"</strong>
										<span class="vision-desc">Обеспечение вузов доступом к научной, учебной литературе.</span>
										<small class="vision-href text-overflow">http://e.lanbook.com/</small>
									</a>
								</div>
							</div>
						</div>
					</li>
					
					<li class="group">
						<div class="vision">
							<div class="vision-shot">
								<div class="vision-img">
									<a class="vision-link">
										<img src="/images/slide-3.jpg" alt="Российский экономический университет">
									</a>
									<a class="vision-over">
										<strong>Российский экономический университет</strong>
										<span class="vision-desc">Высшее учебное заведение, один из крупнейших учебных и научных центров России.</span>
										<small class="vision-href text-overflow"></small>
									</a>
								</div>
							</div>
						</div>
					</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<?php if (empty($user)): ?>
		<div class="col-sm-3 col-sm-push-9" id="login">
			<h2 class="h3 page-header">Вход</h2>
			
			<div class="panel panel-default">
				<div class="panel-body">
					<form method="POST">	
						<div class="form-group">
							{auth[login]}
							<input type="login" class="form-control" name="auth[login]" value="{login}" placeholder="Логин: demo">
						</div>
						
						<div class="form-group">
							{auth[password]}
							<input type="password" class="form-control" name="auth[password]" value="{password}" placeholder="Пароль: demo">
						</div>
						
						<input type="hidden" name="{crsf.token_name}" value="{crsf.hash}">
						<button class="btn btn-success" name="sign-in" value="1" type="submit">Войти</button>
					</form>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="col-sm-<?php echo empty($user) ? '9' : '12'; ?><?php echo empty($user) ? ' col-sm-pull-3' : ''; ?>">
			<h2 class="h3 page-header">Сводка новостей</h2>
			{news}
		</div>
	</div>
</div>