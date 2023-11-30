<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="{{ $description }}" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link type="text/css" rel="stylesheet" href="/css/main.css" />
	<script type="text/javascript" src="/js/main.js"></script>
</head>
<body>
	<div id="conteiner">
		<header>
			<div id="logo_box">
				<a id="logo" href="/">LOGO</a>
			</div>
			<div id="menu_box">
				<nav>
					<ul class="navbar-nav">
						<li class="nav-item">
						<a class="nav-link" href="#">Main</a>
						</li>
						<li class="nav-item">
						<a class="nav-link" href="#">Events</a>
						</li>
						<li class="nav-item active">
						<a class="nav-link" href="#">Calendar</a>
						</li>
						<li class="nav-item">
						<a class="nav-link" href="#">FAQ</a>
						</li>
					</ul>
				</nav>
			</div>
		</header>
	{{ $center }}
		<footer>
			<p><a id="logo" href="/">LOGO</a></p>
			<div id="footer_menu">
				<nav>
					<ul class="navbar-nav">
						<li class="nav-item">
						<a class="nav-link" href="#">Main</a>
						</li>
						<li class="nav-item">
						<a class="nav-link" href="#">Events</a>
						</li>
						<li class="nav-item">
						<a class="nav-link" href="#">Calendar</a>
						</li>
						<li class="nav-item">
						<a class="nav-link" href="#">FAQ</a>
						</li>
					</ul>
				</nav>
			</div>
			<p>© 2022 All rights reserved</p>
		</footer>
    </div>
</body>
</html>