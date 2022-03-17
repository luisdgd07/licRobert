<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de Archivos - RConsultores</title>

	<link href="{{asset('css/app.css')}}" rel="stylesheet">
	<link href="{{asset('css/uploadfile.css')}}" rel="stylesheet">
	<script src="{{asset('js/bundle.js')}}"></script>
	<script src="{{asset('js/jquery.form.min.js')}}"></script>
	<script src="{{asset('js/jquery.uploadfile.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>
	<body>
		<div class="cb-slideshow">
			<div class="imagen-fondo">
				<span>Image 01</span>
			</div>
		</div>
		<div class="container contenedor-principal">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle Navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#"><img alt="Brand" src=" {{asset('img/logo-pretty.png')}} " height="45"></a>
					</div>


					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
						<li id="working-dir"></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							@if (Auth::guest())
							<li><a href="{{ url('/auth/login') }}">Login</a></li>
							@else
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									@if(Auth::user()->isAdmin())
										<li><a href="{{ url('/auth/register') }}">Crear Usuario</a></li>
										<li><a href="{{ url('/') }}">Listar Principal</a></li>
										<li><a href="{{ url('/users') }}">Listar Usuarios</a></li>
									@endif
								</ul>
							</li>
							<li><a href="{{ url('/auth/logout') }}">Cerrar Sesión</a></li>
							@endif
						</ul>

					</div>
				</div>
			</nav>
			@yield('content')
		</div>

	</body>
	</html>
