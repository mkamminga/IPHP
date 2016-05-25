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
	
<div class="fixed"  style="background-color: white">
    <div class="large-2 small-6 columns">
        <img src="/images/logo.png" style="width:100px;height:75px;">
    </div>
    <div class="row">

        <div class="small-2 large-2 columns">
            <h1 style="color: #ffcc00">Goldenfingers</h1>
        </div>
        <div class="large-3 columns">
            <a href="/shoppingcart">
                <div class="panel callout radius">
                    <h6 class="li-cart">{{ $pCount }} items in your cart</h6>
                </div>
            </a>
        </div>
    </div>

    <nav class="top-bar" data-topbar role="navigation">
        <ul class="title-area">
            <li class="name">
                <h1><a href="{{url('categories')}}">GoldenFingers</a></h1>
            </li>
        
            <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
        </ul>

        <section class="top-bar-section">
        <!-- Left Nav Section -->
        <!--<ul class="left">
            @foreach ($menus as $menu)
                <li><a href="{{url($menu->link)}}">{{ $menu->name }}</a></li>
            @endforeach
        </ul>-->
        
        <!-- Right Nav Section -->
        <!--<ul class="right">
            @if (!$user)
                <li>
                    <a href="/login">Log in</a>
                </li>
            @else
                <li><a href="#">Welkom: {{ $user->name  . ' '. $user->lastname}}</a></li> 
                <li><a href="/logout">Log out</a></li>
            @endif    
            <li class="has-form">
                <div class="row collapse div-search">
                    <div class="large-8 small-9 columns">
                        <input class="input-search" type="text" placeholder="Products">
                    </div>
                    <div class="large-4 small-3 columns">
                        <a href="#" class="alert button expand search">Search</a>
                    </div>
                </div>
            </li>
        </ul>-->

        </section>
    </nav>
</div>

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
        <a href="<?php print($this->service('url')->route('LoginGet')); ?>">URL</a>
        <?php
        $input = $this->service('input');
        ?>
        <form method="POST" action="">
            <div class="large-3 rows">
                Username
                <input type="text" name="username" value="<?php print($input->escaped('username')) ?>">
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
<footer class="row">
    <div class="large-12 columns"><hr/>
        <div class="panel">
            <div class="row">

                <div class="large-2 small-6 columns">
                    <img src="/images/cthulu.jpg">
                </div>

                <div class="large-10 small-6 columns">
                    <strong>This Site Is Managed By</strong>

                    Our overlord Cthulu
                </div>

            </div>
        </div>
        <div class="row">


            <div class="large-6 columns">
                <p>&copy; Copyright Cthulu productions  </p>
            </div>


        </div>
    </div>
</footer>
	</div>

</body>
</html>