<!doctype html>
<html>
<head>
	<title>Bewerk product</title>	
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
                <h2> Bewerk product</h2>
                <div class="content">
                    <?php if (!isset($product) || $product != $__view->getInjectedVar("product")){$product=$__view->getInjectedVar("product");}if (!isset($categories) || $categories != $__view->getInjectedVar("categories")){$categories=$__view->getInjectedVar("categories");}if (!isset($subCategories) || $subCategories != $__view->getInjectedVar("subCategories")){$subCategories=$__view->getInjectedVar("subCategories");}if (!isset($vatRates) || $vatRates != $__view->getInjectedVar("vatRates")){$vatRates=$__view->getInjectedVar("vatRates");}if (!isset($errors) || $errors != $__view->getInjectedVar("errors")){$errors=$__view->getInjectedVar("errors");} ?>
						<?php
	if (isset($errors)):
		print($this->service('htmlMessages')->errors($errors));
	endif;
	
	$subCategories = (array)($subCategories ?: []);

	$form = $this->service('form');
	$input = $this->service('input');
	$input->setModel($product);
	
	$baseImageDir = product_images_dir . DIRECTORY_SEPARATOR . $product->retreive('id') . DIRECTORY_SEPARATOR;
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="row">
		    <div class="large-12 columns">
		      <label>Hoofd Categorie
						<?php print($form->select('mainCategory', $categories, $input->raw('mainCategory'), ['id' => 'main-category'])); ?>
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Sub Categorie
						<?php print($form->select('Categories_id', $subCategories, $input->raw('Categories_id'), ['id' => 'sub-category'])); ?>
		      </label>
		    </div>
		</div>
		
		<hr />
		
		<div class="row">
		    <div class="large-12 columns">
		      <label>Artikelnr.
						<?php print($form->text('artikelnr', $input->raw('artikelnr'))); ?>
		      </label>
		    </div>
		</div>
		
		<div class="row">
		    <div class="large-12 columns">
		      <label>Naam
						<?php print($form->text('name', $input->raw('name'))); ?>
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Price
		        <?php print($form->text('price', $input->raw('price'))); ?>
		      </label>
		    </div>
		</div>
		
		<div class="row">
		    <div class="large-12 columns">
		      <label>Vat
		        <?php print($form->select('vat_rate_id', $vatRates, $input->raw('vat_rate_id'))); ?>
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Korte beschrijving
						<?php print($form->textarea('short_description', $input->raw('short_description'))); ?>
				</label>
			</div>
		</div>		

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Lange beschrijving
						<?php print($form->textarea('detail', $input->raw('detail'))); ?>
				</label>
			</div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Kleine afbeelding
						<?php print($form->imageupload('small_image_link')); ?>
				</label>
				<a href="<?php print($baseImageDir . $product->retreive('small_image_link')) ?>">Bekijk</a>
			</div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Grote afbeelding
						<?php print($form->imageupload('main_image_link')); ?>
						<a href="<?php print($baseImageDir . $product->retreive('main_image_link')) ?>">Bekijk</a>
				</label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
				<button type="submit" role="button" aria-label="submit form" class="button">Bewerk</button>
			</div>
		</div>
	</form>
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
	
	
<script src="/js/vendor/jquery.js"></script>
<script src="/js/foundation.min.js"></script>
<script>
$(document).foundation();
</script>
	<script>
	$(document).ready(function () {
		$("#main-category").change(function () {
			var selectedValue = $(this).val();
			
			if (selectedValue != '') {
				var url = "<?php print($url->route("ProductsSubCategories", ["id" => -1])); ?>";
				url = url.replace(/-1/g, selectedValue);
				var select = $("#sub-category");
				$.get(url, {}, function (data) {
					if (data) {
						select.html("");
						//Add all options (subcategories) to the select
						$.each(data, function (i, row) {
							select.append('<option value="'+ row["id"] +'">'+ row["name"] +'</option>'+ "\n");
						});
						
					}
				}, 'json');
			}
		});
	});	
	</script>
</div>

</body>
</html>