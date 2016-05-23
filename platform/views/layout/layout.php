<!doctype html>
<html>
<head>
	<title><< show('title', 'Defaults')</title>	
	<meta charset="UTF-8">
	>> section('styles')
        <link rel="stylesheet" href="/css/foundation.min.css" />
        <link rel="stylesheet" href="/css/main.css" />
        <link rel="stylesheet" href="/css/foundation-icons.css" />
    << section('styles')

    << show('styles', '')
    <script src="/js/vendor/modernizr.js"></script>
</head>
<body>
<div id="main">
	>> section('breadcrumbs')
	>> uses breadcrumbs
	<?php
	if (isset($breadcrumbs) && is_array($breadcrumbs)):
	?>
	<div class="breadcrumbs">
		<?php
		foreach ($breadcrumbs as $breadcrumb) {
			print(' <a href="'. $breadcrumb->getUrl() .'" class="breadcrumb">'. $breadcrumb->getTitle() . '</a> ');
		}
		?>
	</div>
	<?php
	endif;
	?>
	<< section('breadcrumbs')

	<< show('breadcrumbs', '')

	<< show('content', '')

	>> section('scripts')
	<script src="/js/vendor/jquery.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script>
    $(document).foundation();
    </script>
    << section('scripts')
    
	<< show('scripts', '')
</div>

</body>
</html>