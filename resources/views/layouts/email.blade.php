<!DOCTYPE html>
<html>
<head>
	<title>{{ isset($title) ? $title : config('app.name', 'Laravel') }}</title>
</head>
<body>
	<div style="width: 600px; margin: auto;">
		@include('includes.header.email')

		@yield('content')

		@include('includes.footer.email')
	</div>
</body>
</html>