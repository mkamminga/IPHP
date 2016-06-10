<!doctype html>
<html>
<head>
	<title>Pagina niet gevonden!</title>	
	<meta charset="UTF-8">
	    
        <!--<link rel="stylesheet" href="/css/foundation.min.css" />-->
        <link rel="stylesheet" href="/css/app.css" />
        <link rel="stylesheet" href="/css/main.css" />
        <link rel="stylesheet" href="/css/foundation-icons.css" />
        <script src="/js/vendor/modernizr.js"></script>
</head>
<body>
<div id="main">
    <?php
    $messages = $this->service('htmlMessages');
    $messages->errorClass('callout alert');
    $messages->warningClass('callout warning');
    ?>
	
<div class="row">
    <div class="row">
        <div class="small-2 large-2 columns">
            <h1 style="color: #ffcc00">Goldenfingers</h1>
        </div>
    </div>
    <div class="row">
        <div class="small-10 large-centered columns">
            <h1>O, nee!</h1>
            <hr />
            <?php
            print($this->service('htmlMessages')->warning('Er is iets mis gegaan', 'De pagina die u zocht bestaat niet!'));
            ?>
            <a href="/">Klik op deze link voor de home pagina.</a>
        </div>
    </div>
</div>
</div></body>
</html>