<!doctype html>
<html>
<head>
	<title>Categorie bewerken</title>	
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
                        <li><a href="<?php print($url->route('OrdersOverview')); ?>">Orders</a></li>
                        <li><a href="<?php print($url->route('ProductsOverview')); ?>">Products</a></li>
                        <li><a href="<?php print($url->route('CategoriesOverview')); ?>">Categories</a></li>
                        <li><a href="{{ URL::route('beheer.users.index') }}">Gebruikers</a></li>
                    </ul>
                </div> 
            </div>
            
            <div class="large-9 columns">    
                <h2> Categorie bewerken</h2>
                <div class="content">
                    <?php if (!isset($parents) || $parents != $__view->getInjectedVar("parents")){$parents=$__view->getInjectedVar("parents");}if (!isset($category) || $category != $__view->getInjectedVar("category")){$category=$__view->getInjectedVar("category");}if (!isset($errors) || $errors != $__view->getInjectedVar("errors")){$errors=$__view->getInjectedVar("errors");} ?>
			    <?php
    if (isset($errors)):
    	print($this->service('htmlMessages')->errors($errors));
    endif;

    $form = $this->service('form');
	$input = $this->service('input');
	$input->setModel($category);
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="row">
		    <div class="large-12 columns">
		      <label>Naam
		        <?php print($form->text('name', $input->raw('name'))); ?>
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Hoofdcategorie
		        <?php print($form->select('Parent_id', $parents, $input->raw('Parent_id'))); ?>
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Afbeelding
		        <?php print($form->imageupload('image')); ?>
		      </label>

		      <a href="<?php print(categories_images_dir . DIRECTORY_SEPARATOR . $category->retreive('id') . DIRECTORY_SEPARATOR . $category->retreive('thumb')) ?>">Bekijk</a>
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
</div>
<script src="/js/vendor/jquery.js"></script>
<script src="/js/foundation.min.js"></script>
<script>
$(document).foundation();
</script>
</body>
</html>