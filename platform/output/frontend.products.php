<!doctype html>
<html>
<head>
	<title>Products</title>	
	<meta charset="UTF-8">
	    
        <link rel="stylesheet" href="/css/foundation.min.css" />
        <link rel="stylesheet" href="/css/main.css" />
        <link rel="stylesheet" href="/css/foundation-icons.css" />
        <script src="/js/vendor/modernizr.js"></script>
</head>
<body>
<div id="main">
	<?php if (!isset($menus) || $menus != $__view->getInjectedVar("menus")){$menus=$__view->getInjectedVar("menus");}if (!isset($breadcrumbs) || $breadcrumbs != $__view->getInjectedVar("breadcrumbs")){$breadcrumbs=$__view->getInjectedVar("breadcrumbs");}if (!isset($userGuard) || $userGuard != $__view->getInjectedVar("userGuard")){$userGuard=$__view->getInjectedVar("userGuard");} ?>
            <div style="background-color: white">
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
        <ul class="left">
            <li><a href="<?php print($this->service('url')->route('Home'));?>">Home</a>
            <?php
            foreach ($menus as $menu):
            ?>
                <li><a href="<?php print($menu->retreive('link')); ?>"><?php print($menu->retreive('name')); ?></a></li>
            <?php
            endforeach;
            ?>
        </ul>
    
        <!-- Right Nav Section -->
        <ul class="right">
            <?php
            if (!$userGuard->loggedIn()):
            ?>
                <li>
                    <a href="/login">Log in</a>
                </li>
            <?php
            else:
            ?>
                <li><a href="#">Welkom: <?php print($userGuard->getUsername()); ?></a></li> 
                <li><a href="/logout">Log out</a></li>
            <?php
            endif;
            ?>
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

<div class="breadcrumbs">
<?php
if (isset($breadcrumbs)):
?>
<ul class="right">
<?php
    foreach ((array)$breadcrumbs as $breadcrumb):
?>
    <li><a href="<?php print($breadcrumb->getUrl()); ?>"><?php print($breadcrumb->getTitle()); ?></a></li>
<?php
    endforeach;
?>
</ul>
<?php
endif;
?>
</div><?php if (!isset($products) || $products != $__view->getInjectedVar("products")){$products=$__view->getInjectedVar("products");} ?>
        <div class="row">
        <div class="large-12 columns">
            <div class="row">
                <h1>Products</h1>
                <div class="large-8 columns">
                    <div class="row">
                        <?php
                        $products = (array)$products;
                        foreach($products as $product):
                        ?>
                            <div class="large-4 small-6 columns" >
                                <img class="detail" style="width:300px;height:175px;" data-id="{{$product->id}}" src="{{ relative_images_path() . '/'. $product->artikelnr . '/' . $product->small_image_link }}">
                                <div class="panel" id="p-panel-{{$product->id}}">
                                    <h5>{{$product->name}}</h5>
                                    <p><b style="float:left">&#8364;{{$product->price}}</b> <a style="float:right" href="#"> <i class="fi-shopping-cart small add" data-id="{{$product->id}}"></i></a> </p>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>

                </div>
            </div>
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