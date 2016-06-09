<!doctype html>
<html>
<head>
	<title>login</title>	
	<meta charset="UTF-8">
	    
        <link rel="stylesheet" href="/css/foundation.min.css" />
        <link rel="stylesheet" href="/css/main.css" />
        <link rel="stylesheet" href="/css/foundation-icons.css" />
        <script src="/js/vendor/modernizr.js"></script>
</head>
<body>
<div id="main">
    <?php
    $messages = $this->service('htmlMessages');
    $messages->errorClass('alert-box alert');
    ?>
	<?php if (!isset($errors) || $errors != $__view->getInjectedVar("errors")){$errors=$__view->getInjectedVar("errors");} ?>
   		
<div class="row">
    <div class="large-12 columns">
        <?php
if (isset($errors)):
    print($this->service('htmlMessages')->errors($errors));
endif;

$form = $this->service('form');
$input = $this->service('input');
?>
<h1>Login</h1>
<hr />
<form method="POST" action="">
    <div class="large-3 rows">
        <label>Gebruikersnaam
            <?php print($form->text('username', $input->raw('username'))) ?>
        </label>
    </div>

    <div class="large-3 rows">
            <label>Wachtwoord
            <?php print($form->password('password')) ?>
        </label>
    </div>

    <div class="large-3 rows">
        <button type="submit">Login</button>
    </div>
</form>
    </div>
</div>  
</div></body>
</html>