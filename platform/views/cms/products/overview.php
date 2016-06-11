>> parent('cms::layout::backend.php')

>> section('title', 'Producten')

>> section('fcontent')
	>> uses products
	<?php
	$url 	= $this->service('url');
	$form 	= $this->service('form');
	$input 	= $this->service('input');
	?>
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
<< section('fcontent')