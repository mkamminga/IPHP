<!doctype html>
<html>
<head>
	<title>Bewerk order</title>	
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
                <h2> Bewerk order</h2>
                <div class="content">
                    <?php if (!isset($order) || $order != $__view->getInjectedVar("order")){$order=$__view->getInjectedVar("order");}if (!isset($errors) || $errors != $__view->getInjectedVar("errors")){$errors=$__view->getInjectedVar("errors");}if (!isset($orderStates) || $orderStates != $__view->getInjectedVar("orderStates")){$orderStates=$__view->getInjectedVar("orderStates");} ?>
				<?php
	if (isset($errors)):
		print($this->service('htmlMessages')->errors($errors));
	endif;

	$form = $this->service('form');
	$input = $this->service('input');
	$input->setModel($order);
	?>
	<form action="" method="post"> 
		<div class="row">
		    <div class="large-12 columns">
		      <label>Land: 
		    	<strong><?php print($order->getRelated('country')->retreive('name')); ?></strong>
					</label>
				</div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Woonplaats
		        <?php print($form->text('city', $input->get('city'))); ?>
		      </label>
		    </div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Adres 
		        <?php print($form->text('address', $input->get('address'))); ?>
		      </label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Postcode 
		        <?php print($form->text('zip', $input->get('zip'))); ?>
		      </label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Status 
		        <?php print($form->select('status', $orderStates, $input->get('status'))); ?>
		      </label>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<button type="submit" role="button" aria-label="submit form" class="button">Bewerk</button>
			</div>
		</div>

		<hr />
		<h3>Bestelde producten</h3>
		<table>
			<thead>
				<th>Product</th>
				<th>Stuksprijs</th>
				<th>Aantal</th>
				<th>Subtotaal</th>
			</thead>
			
			<tbody>
			<?php
			$total = 0;
			$rows = $order->getRelated('row');
			if ($rows):
				foreach ($rows as $relatedOrderRow):
					$orderrow = $relatedOrderRow->contents();
			?>
					<tr>
						<td>
							<?php print($orderrow->product->name); ?>
						</td>

						<td>
						&euro; <?php print(number_format($orderrow->price, 2, ',', '.')); ?>
						</td>

						<td>
						<?php print($orderrow->quantity); ?>
						</td>

						<td>
						&euro; <?php print(number_format($orderrow->quantity * $orderrow->price, 2, ',', '.')); ?>
						</td>
					</tr>
				<?php
						$total+= $orderrow->quantity * $orderrow->price;
					endforeach;
				endif;
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3">Totaal</td>
					<td>
					&euro; <?php print(number_format($total, 2, ',', '.')); ?>
					</td>
				</tr>
			</tfoot>	
		</table>
		<hr />

		<h3>Klant gegevens</h3>
		<table>
			<tr>
				<td>Naam</td>
				<td><?php print($order->retreive('firstname') . ' '. $order->retreive('lastname')); ?> </td>
			</tr>
			<tr>
				<td>Email</td>
				<td><?php print($order->retreive('email')); ?></td>
			</tr>
		</table>
	</form
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