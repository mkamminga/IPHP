>> parent('cms::layout::backend.php')

>> section('title', 'Nieuwe navigatie')

>> section('fcontent')
	>> uses errors
	>> uses navigation
    <?php
    if (isset($errors) && count((array)$errors) > 0):
    ?>
        <div data-alert="" class="alert-box alert">
            <ul>
                <?php
                foreach ($errors as $error):
                ?>
                    <li><?php print($error); ?></li>
                <?php
                endforeach;
                ?>
            </ul>
            
        </div>
    <?php
    endif;
    ?>
	<?php
	$input = $this->service('input');
	$input->setModel($navigation);
	?>
	<form action="" method="post">
		<div class="row">
		    <div class="large-12 columns">
		      <label>Naam
		        <input type="text" name="name" value="<?php print($input->raw('name')) ?>">
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Link
		        <input type="text" name="link" value="<?php print($input->raw('link')) ?>">
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Positie
		        <input type="text" name="position" value="<?php print($input->raw('position')) ?>">
		      </label>
		    </div>
		</div>

		<div class="row">
			<div class="large-12 columns">
				<button type="submit" role="button" aria-label="submit form" class="button">Bewerk</button>
			</div>
		</div>
	</form>
<< section('fcontent')