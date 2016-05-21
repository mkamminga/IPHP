<!doctype html>
<html>
<head>
	<title><< show('title', 'Default')</title>	
	<meta charset="UTF-8">
</head>
<body>
<div id="main">
	>> section('breadcrumbs')
	>> uses breadcrumbs
		<div class="breadcrumbs">
		<?php
		if (isset($breadcrumbs) && is_array($breadcrumbs)) {
			foreach ($breadcrumbs as $breadcrumb) {
				print(' <a href="'. $breadcrumb->getUrl() .'">'. $breadcrumb->getTitle() . '</a> ');
			}
		}
		?>
		</div>
	<< section('breadcrumbs')

	<< show('breadcrumbs', '')

	>> section('content')
		<h1>Master lqyout</h1>
	<< section('content')
	<< show('content', 'de')

	>> section('scripts')
		<script type="text/javascript">var a = 1;</script>
		<script type="text/javascript">var b = 2;</script>
	<< section('scripts')
	<< show('scripts', '')
</div>

</body>
</html>