<!doctype html>
<html>
<head>
	<title><?php if (!isset($title) || $title != $__view->getInjectedVar("title")){$title=$__view->getInjectedVar("title");} ?><?php print($title); ?></title>	
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
        <?php if (!isset($categories) || $categories != $__view->getInjectedVar("categories")){$categories=$__view->getInjectedVar("categories");}if (!isset($mainCategory) || $mainCategory != $__view->getInjectedVar("mainCategory")){$mainCategory=$__view->getInjectedVar("mainCategory");}if (!isset($title) || $title != $__view->getInjectedVar("title")){$title=$__view->getInjectedVar("title");} ?>
            <?php
$url = $this->service('url');
?>

<h1>Category: <?php print($mainCategory->retreive('name')) ?></h1>
<div class="row" data-equalizer>
    <?php
    $mainCategoryId = $mainCategory->retreive('id');

    $categories = (array)$categories;
    if (count($categories) > 0):
        foreach ($categories as $category):
            $id = $category->id;
            
            $categoryUrl = $url->route('CategoryProducts', [
                'category_id' => $mainCategoryId,
                'sub_category_id' => $id
            ]);
    ?>
        <div class="large-4 small-3 columns">
    <div class="callout" data-equalizer-watch>
        <h5><?php print($category->name); ?></h5>
        <a href="<?php print($categoryUrl) ?>">
            <img class="thumbnail" src="<?php print(categories_images_dir . '/'. $category->id . '/' . $category->thumb); ?>" alt="<?php print($category->name); ?>">
        </a>
    </div>
</div>
    <?php
        endforeach;
    else:
        print($this->service('htmlMessages')->warning('Geen subcategorieën', 'Geen subcategorieën gevonden voor hoofdcategorie: '. $mainCategory->retreive('id') .'!'));
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
</body>
</html>