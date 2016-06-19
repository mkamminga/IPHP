>> parent('cms::layout::backend.php')

>> section('title', 'Orders')

>> section('fcontent')
	>> uses orders

	<?php
	$orders = (array)$orders;

	$translator = $this->service('translator');
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
						<td><?php print((isset($order->user) ? $order->user->username : '-')); ?></td>
						<td><?php print($translator->get('orderstates', $order->status)); ?></td>
					</tr>
				<?php
				endforeach;
				?>
			</tbody>
		</table>
	<?php
	endif;
	?>
<< section('fcontent')