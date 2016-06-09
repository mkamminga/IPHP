>> parent('cms::layout::backend.php')

>> section('title', 'Bewerk order')

>> section('fcontent')
	>> uses order
	>> uses errors
	>> uses orderStates
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
<< section('fcontent')