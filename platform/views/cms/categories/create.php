>> parent('cms::layout::backend.php')

>> section('title', 'Nieuwe categorie')

>> section('fcontent')
	>> uses parents
	>> uses errors
	<?php
	if (isset($errors)):
		print($this->service('htmlMessages')->errors($errors));
	endif;

  $form = $this->service('form');
	$input = $this->service('input');
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
		    </div>
		</div>
		
		<div class="row">
			<div class="large-12 columns">
				<button type="submit" role="button" aria-label="submit form" class="button">Verstuur</button>
			</div>
		</div>
	</form>
<< section('fcontent')