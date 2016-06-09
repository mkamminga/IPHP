<!doctype html>
<html>
<head>
	<title>Orders</title>	
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
                <h2> Orders</h2>
                <div class="content">
                    <?php if (!isset($orders) || $orders != $__view->getInjectedVar("orders")){$orders=$__view->getInjectedVar("orders");} ?>
		<?php
	$orders = (array)$orders;
	if (count($orders) > 0):
	?>
		<table>
			<thead>
				<tr>
					<th>Acties</th>
					<th>#</th>
					<th>Totaal prijs</th>
					<th>Geplaatst op</th>
					<th>Geplaatst door</th>
					<th>Status</th>
				</tr>
			</thead>

			<tbody>
				<?php
				$url = $this->service('url');
				foreach ($orders as $orderModel):
					$order = $orderModel->contents();
					$id = $order->id;
				?>
					<tr>
						<td>
							<a href="<?php print($url->route('OrdersShowEdit', ['id' => $id])); ?>" class="button tiny">Inzien</a>
							<a href="<?php print($url->route('OrdersShowDelete', ['id' => $id])); ?>" class="button tiny alert">Verwijder</a>
						</td>
						<td><?php print($id); ?></td>
						<td>&euro; <?php print(number_format($order->total, 2, ',', '.')); ?></td>
						<td><?php print($order->created_at); ?></td>
						<td><?php print($order->user->username); ?></td>
						<td><?php print($order->status); ?></td>
					</tr>
				<?php
				endforeach;
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