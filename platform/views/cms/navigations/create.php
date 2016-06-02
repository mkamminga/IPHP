>> parent('cms::layout::backend.php')

>> section('title', 'Nieuwe navigatie')

>> section('fcontent')
	>> uses errors
    <?php
    if (isset($errors)):
    	print($this->service('htmlMessages')->errors($errors));
    endif;

  	$form = $this->service('form');
		$input = $this->service('input');
		?>
	<form action="" method="post">
		<div class="row">
		    <div class="large-12 columns">
		      <label>Naam
		        <?php print($form->text('name', $input->raw('name'))); ?>
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Link
		        <?php print($form->text('link', $input->raw('link'))); ?>
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Positie
		        <?php print($form->text('position', $input->raw('position'))); ?>
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