>> parent('cms::products::form.defaults.php')

>> section('title', 'Nieuw product')

>> section('fcontent')
	>> uses categories
	>> uses subCategories
	>> uses vatRates
	>> uses errors
	<?php
	if (isset($errors)):
		print($this->service('htmlMessages')->errors($errors));
	endif;
	
	$subCategories = (array)($subCategories ?: []);

	$form = $this->service('form');
	$input = $this->service('input');
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
			</div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Grote afbeelding
						<?php print($form->imageupload('main_image_link')); ?>
				</label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
				<button type="submit" role="button" aria-label="submit form" class="button">Verstuur</button>
			</div>
		</div>
	</form>
<< section('fcontent')