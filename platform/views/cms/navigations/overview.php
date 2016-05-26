>> parent('cms::layout::backend.php')

>> section('title', 'Navigatie')

>> section('fcontent')
	>> uses navigations
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
<< section('fcontent')