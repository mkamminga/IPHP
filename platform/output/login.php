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
		<?php if (!isset($breadcrumbs) || $breadcrumbs != $__view->getInjectedVar("breadcrumbs")){$breadcrumbs=$__view->getInjectedVar("breadcrumbs");} ?>
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
		
	<div class="row">
    <div class="large-12 columns">

    <?php
    if (isset($errors) && count($errors) > 0):
    ?>
        <div data-alert="" class="alert-box alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            
        </div>
    <?php
    endif;
    ?>
        <h1>Login</h1>
        <form method="POST" action="">
            <div class="large-3 rows">
                Username
                <input type="text" name="username" value="">
            </div>

            <div class="large-3 rows">
                Password
                <input type="password" name="password" id="password">
            </div>

            <div>
                <input type="checkbox" name="remember"> Remember Me
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>
        <hr />
        <a class="small button secondary" href="/register">New user? Register here!</a>
    </div>
</div>  
	    
	
	<script src="/js/vendor/jquery.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script>
    $(document).foundation();
    </script>
    </div>

</body>
</html>