<!doctype html>
<html>
<head>
	<title>Winkelmandje</title>	
	<meta charset="UTF-8">
	    
        <!--<link rel="stylesheet" href="/css/foundation.min.css" />-->
        <link rel="stylesheet" href="/foundation/dist/assets/css/app.css" />
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
	<?php if (!isset($menus) || $menus != $__view->getInjectedVar("menus")){$menus=$__view->getInjectedVar("menus");}if (!isset($breadcrumbs) || $breadcrumbs != $__view->getInjectedVar("breadcrumbs")){$breadcrumbs=$__view->getInjectedVar("breadcrumbs");}if (!isset($userGuard) || $userGuard != $__view->getInjectedVar("userGuard")){$userGuard=$__view->getInjectedVar("userGuard");}if (!isset($cartCount) || $cartCount != $__view->getInjectedVar("cartCount")){$cartCount=$__view->getInjectedVar("cartCount");} ?>
                <?php
$url = $this->service('url');
?>
<div class="top">
    <div class="row">
        <div class="small-2 large-2 columns">
            <h1 style="color: #ffcc00">Goldenfingers</h1>
        </div>
        <div class="large-3 columns">
            <div class="callout radius">
                <a href="<?php print($url->route('CartOverview')); ?>"><h6 class="li-cart"><span class="warning badge" id="cart-count"><?php print($cartCount); ?></span> producten in winkelwagen!</h6></a>
            </div>
        </div>
    </div>

    <div class="top-bar">
        <div class="top-bar-left">
        <!-- Left Nav Section -->
        <?php
        $menu = $this->service('menu');

        $menu->setMenuClass(['dropdown', 'menu']);
        $menu->setMenuAttrs(['data-dropdown-menu']);
        $menu->setSubMenuClass(['menu']);

        print($menu->displayMenu($menus));
        ?>
        </div>
        <div class="top-bar-right">
            <form action="<?php print($url->route('ProductSearch')); ?>" method="get">
                <!-- Right Nav Section -->
                <ul class="menu">
                    <?php
                    if (!$userGuard->loggedIn()):
                    ?>
                        <li>
                            <a href="/login">Log in</a>
                        </li>
                    <?php
                    else:
                    ?>
                        <li>Welkom: <?php print($userGuard->getUsername()); ?></li> 
                        <li><a href="/logout">Log out</a></li>
                    <?php
                    endif;
                    ?>
                    <li><input name="q" type="text" placeholder="Products"></li>
                    <li><button type="submit" class="button expand search">Search</button></li>
                </ul>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="small-12 large-centered columns">
        <?php
        //display breadcrumbs
        if (isset($breadcrumbs) && !empty($breadcrumbs)):
        ?>
        <div class="clearfix">

            <nav aria-label="You are here:" role="navigation" class="float-right">
                <ul class="breadcrumbs">
                <?php
                $num = count($breadcrumbs);
                $i = 1;
                foreach ((array)$breadcrumbs as $breadcrumb):
                ?>
                    <li<?php print($i == $num ? ' class="current"' : ''); ?>>
                        <?php
                        if ($i == $num):
                        ?>
                        <?php print($breadcrumb->getTitle()); ?>
                        <?php
                        else:
                        ?>
                        <a href="<?php print($breadcrumb->getUrl()); ?>"><?php print($breadcrumb->getTitle()); ?></a>
                        <?php
                        endif;
                        ?>
                    </li>
                <?php
                    $i++;
                endforeach;
                ?>
                </ul>
            </nav>
        </div>
        <?php
        endif;
        ?>
        <?php if (!isset($shoppingcart) || $shoppingcart != $__view->getInjectedVar("shoppingcart")){$shoppingcart=$__view->getInjectedVar("shoppingcart");}if (!isset($shoppingcart) || $shoppingcart != $__view->getInjectedVar("shoppingcart")){$shoppingcart=$__view->getInjectedVar("shoppingcart");} ?>
    <?php
$url = $this->service('url');
?>
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
        <footer>
            <hr/>
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
        </footer>
    </div>
</div>
</div>
    
<?php
$url = $this->service('url');
?>
<script src="/js/vendor/jquery.js"></script>
<!--<script src="/js/foundation.min.js"></script>
<script src="/js/foundation.topbar.js"></script>-->
<script src="/foundation/dist/assets/js/app.js"></script>
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