<!doctype html>
<html>
<head>
	<title>Navigatie</title>	
	<meta charset="UTF-8">
	    
        <link rel="stylesheet" href="/css/foundation.min.css" />
        <link rel="stylesheet" href="/css/main.css" />
        <link rel="stylesheet" href="/css/foundation-icons.css" />
        <script src="/js/vendor/modernizr.js"></script>
</head>
<body>
<div id="main">
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
                        <li><a href="{{ URL::route('beheer.products.index') }}">Products</a></li>
                        <li><a href="{{ URL::route('beheer.categories.index') }}">Categories</a></li>
                        <li><a href="{{ URL::route('beheer.users.index') }}">Gebruikers</a></li>
                    </ul>
                </div> 
            </div>
            
            <div class="large-9 columns">    
                <h2> Navigatie</h2>
                <div class="content">
                    <?php if (!isset($navigations) || $navigations != $__view->getInjectedVar("navigations")){$navigations=$__view->getInjectedVar("navigations");} ?>
		<?php
	$url = $this->service('url');
	?>
	<a href="<?php print($url->route('NavigationShowAdd')); ?>" class="button success">Nieuwe Navigatie</a>
	<table>
		<thead>
			<tr>
				<th>Acties</th>
				<th>Naam</th>
				<th>Link naar</th>
				<th>Positie</th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($navigations as $navigation):
				$id = $navigation->retreive('id');
			?>
				<tr>
					<td>
						<a href="<?php print($url->route('NavigationShowEdit', ['id' => $id])) ?>" class="button tiny">Bewerk</a>
						<a href="<?php print($url->route('NavigationShowDelete', ['id' => $id])) ?>" class="button tiny alert">Verwijder</a>
					</td>
					<td><?php print($navigation->retreive('name')); ?></td>
					<td><?php print($navigation->retreive('link')); ?></td>
					<td><?php print($navigation->retreive('position')); ?></td>
				</tr>
			<?php
			endforeach;
			?>
		</tbody>
	</table>
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

</body>
</html>