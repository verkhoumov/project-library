<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
	        </button>
	        
			<a class="navbar-brand" href="/">
				<img alt="Brand" src="/images/logo.png" width="100%" title="Azarov Igor" alt="Azarov Igor brand logotype">
			</a>
		</div>
		
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="/">Главная</a></li>
				<li><a href="/about">О нас</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ресурсы <span class="caret"></span></a>
					
					<ul class="dropdown-menu">
						<li><a href="http://www.gup.ru/" target="_blank">РЭУ</a></li>
						<li><a href="http://e.lanbook.com/" target="_blank">Подписные электронные каталоги</a></li>
					</ul>
				</li>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<?php if (!empty($user['id'])): ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user icon"></span> <?php echo get_user_name($user); ?> <span class="caret"></span></a>
					
					<ul class="dropdown-menu">
						<?php if ($user['group_id'] == 1): ?>
						<li><a href="/user/admin"><span class="glyphicon glyphicon-cog icon"></span> Панель управления</a></li>
						<?php endif; ?>
						
						<li><a href="/user/reader"><span class="glyphicon glyphicon-lamp icon"></span> Читальный зал</a></li>
						<li><a href="/user/sign-out"><span class="glyphicon glyphicon-log-out icon"></span> Выход</a></li>
					</ul>
				</li>
				<?php else: ?>
				<li><a href="/#login"><span class="glyphicon glyphicon-log-in icon"></span> Войти</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
