<!doctype html>
<html>
<head>
	<title><?php if (!isset($title) || $title != $__view->getInjectedVar("title")){$title=$__view->getInjectedVar("title");} ?><?php print($title); ?></title>	
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
        <?php
        $url = $this->service('url');
        
        $displayMenu = function (array $items, $class = '') use($url, &$displayMenu) {
            $output =   '<ul class="'. $class .'">' . chr(13) . chr(9);
            foreach ($items as $menu):
                $params = isset($menu->params) ? $menu->params : [];
                $subMenu = isset($menu->subMenu) ? $menu->subMenu : [];
                $output.= chr(9) . chr(9) .  '<li'. (count($subMenu) > 0 ? ' class="has-dropdown"': '') .'>'. chr(13) . chr(9). chr(9) . chr(9).  chr(9) . '<a href="'. $url->route($menu->link, $params) .'">'. $menu->name .'</a>' . chr(13) . chr(9) . chr(9);
                
                if (count($subMenu) > 0) {
                    $output.= chr(9) . chr(9) . $displayMenu($subMenu, 'dropdown') . chr(9);
                }
                
                $output.=  chr(9) . '</li>'.chr(13) . chr(9);
            endforeach;
            
            $output.= chr(9) . '</ul>' . chr(13) . chr(9);
            
            return $output;
       
        };
        
        print($displayMenu($menus, 'left'));     
        ?>
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

<div class="breadcrumb-frame">

    <?php
    if (isset($breadcrumbs)):
    ?>
    <ul class="breadcrumbs right">
        <?php
            $num = count($breadcrumbs);
            $i = 1;
            foreach ((array)$breadcrumbs as $breadcrumb):
        ?>
            <li<?php print($i == $num ? ' class="current"' : ''); ?>><a href="<?php print($breadcrumb->getUrl()); ?>"><?php print($breadcrumb->getTitle()); ?></a></li>
        <?php
            $i++;
        endforeach;
        ?>
    </ul>
    <?php
    endif;
    ?>
    <div style="clear: both"></div>
</div><?php if (!isset($categories) || $categories != $__view->getInjectedVar("categories")){$categories=$__view->getInjectedVar("categories");}if (!isset($mainCategory) || $mainCategory != $__view->getInjectedVar("mainCategory")){$mainCategory=$__view->getInjectedVar("mainCategory");}if (!isset($title) || $title != $__view->getInjectedVar("title")){$title=$__view->getInjectedVar("title");} ?>
                <?php
    $url = $this->service('url');
    ?>
    <div class="row">
        <h1>Category: <?php print($mainCategory->retreive('name')) ?></h1>
        <div class="large-12 columns">
            <div class="row">
                <div class="large-8 columns">
                    <div class="row" data-equalizer>
                        <?php
                        $mainCategoryId = $mainCategory->retreive('id');
                        foreach ($categories as $category):
                            $id = $category->id;
                            
                            $categoryUrl = $url->route('CategoryProducts', [
                                'category_id' => $mainCategoryId,
                                'sub_category_id' => $id
                            ]);
                        ?>
                            <div class="large-4 small-6 columns" id="<?php print($id) ?>" data-equalizer-watch>
                                <a href="<?php print($categoryUrl) ?>">
                                    <div style="min-height: 10em; width: 10em; background: url('<?php print(categories_images_dir . '/'. $id . '/' . $category->thumb); ?>') center no-repeat;"></div>

                                    <div class="panel">
                                        <h5><?php print($category->name); ?></h5>
                                    </div>
                                </a>
                            </div>
                        <?php
                        endforeach
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

<script src="/js/vendor/jquery.js"></script>
<script src="/js/foundation.min.js"></script>
<script src="/js/foundation.topbar.js"></script>
<script>
$(document).foundation();
$(document).ready(function() {
    $('.div-search').on('click','a.search' ,function() {
        var value = $('.input-search').val();
        if(value == '')
        {
            alert('Nothing to search please put in a word')
        }
        else {
                $.ajax({
                url: '/ajax/searchproduct/' + value,
                method: "get",
                dataType: 'json',
                success: function (data) {
                    if(data.found ==1) {
                        window.location.replace("{{url('resultspage')}}");
                    }
                    else{
                        alert(data.nothing);
                    }
                }
            })
        }
    })
});
</script>
</body>
</html>