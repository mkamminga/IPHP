<!doctype html>
<html>
<head>
	<title><< show('title', 'Defaults')</title>	
	<meta charset="UTF-8">
	>> section('styles')
        <!--<link rel="stylesheet" href="/css/foundation.min.css" />-->
        <link rel="stylesheet" href="/app.css" />
        <link rel="stylesheet" href="/css/main.css" />
        <link rel="stylesheet" href="/css/foundation-icons.css" />
    << section('styles')

    << show('styles', '')
    <script src="/js/vendor/modernizr.js"></script>
</head>
<body>
<div id="main">
    <?php
    $messages = $this->service('htmlMessages');
    $messages->errorClass('callout alert');
    $messages->warningClass('callout warning');
    ?>
	<< show('content', '')
</div>

<< show('scripts', '')
</body>
</html>