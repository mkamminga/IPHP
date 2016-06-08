<!doctype html>
<html>
<head>
	<title>Winkelmandje</title>	
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
                        <div class="large-8 small-9 columns">
                            <input class="input-search" type="text" placeholder="Products">
                        </div>
                        <div class="large-4 small-3 columns">
                            <a href="#" class="alert button expand search">Search</a>
                        </div>
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
?><?php if (!isset($shoppingcart) || $shoppingcart != $__view->getInjectedVar("shoppingcart")){$shoppingcart=$__view->getInjectedVar("shoppingcart");}if (!isset($shoppingcart) || $shoppingcart != $__view->getInjectedVar("shoppingcart")){$shoppingcart=$__view->getInjectedVar("shoppingcart");} ?>
        <?php
    $url = $this->service('url');
    ?>
    <div class="row">
        <div class="large-12 columns">
            <div class="row">
                <div id="cart-frame">
                    <h2 class="h2-state">Cart</h2>
<?php
if (count($shoppingcart) > 0):
?>
<form method="post" action="" onsubmit="return false">
    <table class="t-shoppingcart">
        <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Acties</th>
        </tr>
        </thead>

        <tbody class="tb-cart">
        <?php
        $items = $shoppingcart->getItems();
        foreach($items as $item):
            $product = $item->getProduct()->contents();
        ?>
            <tr data-id="<?php print($product->id); ?>" class="cart-row-item">
                <td>
                    <a class="th" href="#"><img style="max-height: 80px; max-width: 80px;" src="<?php print(product_images_dir . '/'. $product->id . '/' . $product->small_image_link); ?>"></a>
                </td>

                <td>
                    <?php print($product->name); ?>
                </td>

                <td class="td-quantity">
                    <input type="text" name="quantityof_<?php print($product->id); ?>" data-id="<?php print($product->id); ?>" value="<?php print($item->getQuantity()); ?>" class="cart-item-quantity small" style="width: 3em;" />
                </td>

                <td>
                    &euro; <?php print(number_format($product->price, 2, ',', '.')); ?>
                </td>

                <td>  &euro; <?php print(number_format($item->total(), 2, ',', '.')); ?></td>

                <td>
                    <a href="#"><i class="fi-x remove-item" data-id="<?php print($product->id) ?>"></i></a>
                </td>
                
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Totalprice</strong></td>
                <td colspan="2">&euro; <?php print(number_format($shoppingcart->getTotal(), 2, ',', '.')); ?></td>
            </tr>
        </tfoot>
    </table>
</form>
<?php
endif;
?>
                </div>    
                
                <div class="checkout">
                    <?php
                    if (count($shoppingcart) > 0):
                    ?>
                        <a href="<?php print($url->route('CheckoutShow')); ?>" class="button bt-checkout">Check out</a>
                    <?php
                    endif;
                    ?>
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
    <script>
        $(document).ready(function(){
            $(document).on('click','.tb-cart i.remove-item' ,function(){
                var id = $(this).data('id');
                var url = '<?php print($url->route('CartItemRemove')); ?>';
                var params={"id" : $(this).data('id'), "quantity" : 1};

                $.post(url, params, function(result) {
                    if (result){
                        if (result.status == 'success'){
                            updateCartCount(result.data.count);
                            loadCart();
                        }
                    }
                }, 'json');
            });
            //Update cart quantity
            $(document).on('change','.tb-cart .cart-item-quantity' ,function(){
                var quantity = parseInt($(this).val());
                var id = $(this).data('id');
                var url = '<?php print($url->route('CartItemQuantityUpdate')); ?>';
                var params={"id" : id, "quantity" : quantity};

                if (quantity >= 1) {
                    $.post(url, params, function(result) {
                        if (result){
                            if (result.status == 'success'){
                                updateCartCount(result.data.count);
                                //reload cart
                                loadCart();
                            }
                        }
                    }, 'json');
                }
            });
        });

        function loadCart () {
            var url = '<?php print($url->route('CartShowAjaxCart')); ?>';
            $("#cart-frame").load(url);
        }
    </script>
</body>
</html>