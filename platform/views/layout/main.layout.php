<!doctype html>
<html class="no-js" lang="nl" dir="ltr">
<head>
	<title><< show('title', 'Defaults')</title>	
	<meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	>> section('styles')
        <link rel="stylesheet" href="/css/foundation.min.css" />
        <link rel="stylesheet" href="/css/main.css" />
    << section('styles')

    << show('styles', '')
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