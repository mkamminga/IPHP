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
	<?php if (!isset($menus) || $menus != $__view->getInjectedVar("menus")){$menus=$__view->getInjectedVar("menus");}if (!isset($breadcrumbs) || $breadcrumbs != $__view->getInjectedVar("breadcrumbs")){$breadcrumbs=$__view->getInjectedVar("breadcrumbs");}if (!isset($userGuard) || $userGuard != $__view->getInjectedVar("userGuard")){$userGuard=$__view->getInjectedVar("userGuard");}if (!isset($cartCount) || $cartCount != $__view->getInjectedVar("cartCount")){$cartCount=$__view->getInjectedVar("cartCount");} ?>
                <?php
$url = $this->service('url');
?>
<div style="background-color: white">
    <div class="large-2 small-6 columns">
        <img src="/images/logo.png" style="width:100px;height:75px;">
    </div>
    <div class="row">

        <div class="small-2 large-2 columns">
            <h1 style="color: #ffcc00">Goldenfingers</h1>
        </div>
        <div class="large-3 columns">
            <a href="<?php print($url->route('CartOverview')); ?>">
                <div class="panel callout radius">
                    <h6 class="li-cart"><strong id="cart-count"><?php print($cartCount); ?></strong> producten in winkelwagen!</h6>
                </div>
            </a>
        </div>
    </div>

    <nav class="top-bar" data-topbar role="navigation">
        <ul class="title-area">
            <li class="name">
                <h1><a href="/">GoldenFingers</a></h1>
            </li>
        
            <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
        </ul>

        <section class="top-bar-section">
            <!-- Left Nav Section -->
            <?php
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
                        <form action="<?php print($url->route('ProductSearch')); ?>" method="get">
                            <div class="large-8 small-9 columns">
                                <input name="q" type="text" placeholder="Products">
                            </div>
                            <div class="large-4 small-3 columns">
                                <button type="submit" class="alert expand search">Search</button>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </section>
    </nav>
</div>

<?php
//display breadcrumbs
if (isset($breadcrumbs) && !empty($breadcrumbs)):
?>
<div class="breadcrumb-frame clearfix">


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
</div>
<?php
endif;
?>
<div class="row">
    <h1>Home</h1>

    <p>Welkom in de geweldige webshop van goldfingers!!!</p>
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
<?php
$url = $this->service('url');
?>
<script src="/js/vendor/jquery.js"></script>
<script src="/js/foundation.min.js"></script>
<script src="/js/foundation.topbar.js"></script>
<script>
$(document).foundation();

$(".add-product-to-cart").click(function () {
    var url = '<?php print($url->route('CartItemPost')); ?>';
    var params={"id" : $(this).data('id'), "quantity" : 1};
    $.post(url, params, function (result) {
        if (result) {
            if (result.status == "success") {
                updateCartCount(result.data.count);
            }
        }
    }, 'json');
});

function updateCartCount (num) {
    console.log("Update to: "+ num);
    $("#cart-count").html(num);
}
</script>
</body>
</html>