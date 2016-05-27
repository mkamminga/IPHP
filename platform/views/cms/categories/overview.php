>> parent('cms::layout::backend.php')

>> section('title', 'Categories')

>> section('fcontent')
	>> uses categories
	<?php
	$url = $this->service('url');
	?>
	<a href="<?php print($url->route('CategoriesShowAdd')) ?>" class="button success">Nieuwe categorie</a>
	<table>
		<thead>
			<tr>
				<th>Acties</th>
				<th>Naam</th>
				<th>Hoofd</th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($categories as $category):
				$id = $category->retreive('id');
			?>
				<tr>
					<td>
						<a href="<?php print($url->route('CategoriesShowEdit', ['id' => $id])) ?>" class="button tiny">Bewerk</a>
						<a href="<?php print($url->route('CategoriesShowDelete', ['id' => $id])) ?>" class="button tiny alert">Verwijder</a>
					</td>
					<td><?php print($category->retreive('name')); ?></td>
					<td><?php print($category->retreive('main')); ?></td>
				</tr>
			<?php
			endforeach;
			?>
		</tbody>
	</table>
<< section('fcontent')