<!doctype html>
<html>
<head>
	<title>Producten</title>	
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
	<?php if (!isset($userGuard) || $userGuard != $__view->getInjectedVar("userGuard")){$userGuard=$__view->getInjectedVar("userGuard");} ?>
        <div class="container row">        
        <div class="row">
            <div class="large-12 columns">
                <div class="panel">
                    <h1>CMS</h1>
                    <span class="text-aligned-right">Welkom <?php print($userGuard->getUsername()); ?></span>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="large-3 columns">
                <div class="panel">
                    <h3>Menu</h3>
                    <?php
                    $url = $this->service('url');
                    ?>
                    <ul class="side-nav">
                        <li><a href="<?php print($url->route('Dashboard')); ?>">Dashboard</a></li>
                        <li><a href="<?php print($url->route('NavigationOverview')); ?>">Navigatie</a></li>
                        <li><a href="{{ URL::route('beheer.orders.index') }}">Orders</a></li>
                        <li><a href="<?php print($url->route('ProductsOverview')); ?>">Products</a></li>
                        <li><a href="<?php print($url->route('CategoriesOverview')); ?>">Categories</a></li>
                        <li><a href="{{ URL::route('beheer.users.index') }}">Gebruikers</a></li>
                    </ul>
                </div> 
            </div>
            
            <div class="large-9 columns">    
                <h2> Producten</h2>
                <div class="content">
                    <?php if (!isset($products) || $products != $__view->getInjectedVar("products")){$products=$__view->getInjectedVar("products");} ?>
		<?php
	$url 	= $this->service('url');
	$form 	= $this->service('form');
	$input 	= $this->service('input');
	?>
	<form action="" method="get">
		<div class="row">
			<div class="large-12 columns">
				<div class="row collapse">
					<div class="small-10 columns">
					<?php print($form->text('artikelnr', $input->escaped('artikelnr'), ['placeholder' => 'Article number'])); ?>
					</div>

					<div class="small-2 columns">
						<button type="submit" class="button postfix">Zoek</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<a href="<?php print($url->route('ProductsShowAdd')) ?>" class="button success">Nieuw product</a>
	<?php
	$products = (array)$products;
	if (count($products) > 0):
	?>
		<table>
			<thead>
				<tr>
					<th>Acties</th>
					<th>Naam</th>
					<th>Artikelnr.</th>
					<th>Price</th>
					<th>Categorie</th>
				</tr>
			</thead>

			<tbody>
				<?php
				foreach ($products as $product):
					$id = $product->retreive('id');
				?>
					<tr>
						<td>
							<a href="<?php print($url->route('ProductsShowEdit', ['id' => $id])) ?>" class="button tiny">Bewerk</a>
							<a href="<?php print($url->route('ProductsShowDelete', ['id' => $id])) ?>" class="button tiny alert">Verwijder</a>
						</td>
						<td><?php print($product->retreive('name')); ?></td>
						<td><?php print($product->retreive('artikelnr')); ?></td>
						<td>&euro; <?php print(number_format($product->retreive('price'), 2, ',', '.')); ?></td>
						<td><?php print($product->getRelated('category')->retreive('name')); ?></td>
					</tr>
				<?php
				endforeach
				?>
			</tbody>
		</table>
	<?php
	endif;
	?>
                </div>
            </div>    
        </div>

        <footer class="row">
            <div class="large-12 columns">
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
            </div>
        </footer>
    </div>    
</div>
<script src="/js/vendor/jquery.js"></script>
<script src="/js/foundation.min.js"></script>
<script>
$(document).foundation();
</script>
</body>
</html>