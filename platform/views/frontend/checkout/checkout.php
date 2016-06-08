>> parent('layout::frontend.php')

>> section('title', 'Bestelgegevens')
>> section('fcontent')
    >> uses errors
    >> uses order
    >> uses countries
    <?php
    if (isset($errors)):
      print($this->service('htmlMessages')->errors($errors));
    endif;

    $form = $this->service('form');
    $input = $this->service('input');
	  $input->setModel($order);
    ?>
<div class="row">
    <h1>Bestelgegevens</h1>
    <form action="" method="post">
        <div class='large-6 rows'>
          <label>Voornaam
            <?php print($form->text('firstname', $input->get('firstname'))); ?>
          </label>
        </div>

        <div class='large-6 rows'>
          <label>Achternaam 
            <?php print($form->text('lastname', $input->get('lastname'))); ?>
          </label>
        </div>

        <div class='large-6 rows'>
          <label>
            Land
            <?php print($form->select('country_id', $countries, $input->raw('country_id'))); ?>
          </label>
        </div>

        <div class='large-6 rows'>
          <label>Woonplaats
            <?php print($form->text('city', $input->get('city'))); ?>
          </label>
        </div>

        <div class='large-6 rows'>
          <label>Adres
            <?php print($form->text('address', $input->get('address'))); ?>
          </label>
        </div>

        <div class='large-6 rows'>
          <label>Postcode
            <?php print($form->text('zip', $input->get('zip'))); ?>
          </label>
        </div>

        <div class='large-6 rows'>
            <label>Email
            <?php print($form->text('email', $input->get('email'))); ?>
          </label>
        </div>

        <div class='large-6 rows'>
          <label>Telefoon
            <?php print($form->text('telephone', $input->get('telephone'))); ?>
          </label>
        </div>

        <div class='large-6 rows'>
            <button type="submit" role="button" aria-label="submit form" class="button">Bevestig!</button>
        </div>
    </form>
</div>

<< section('fcontent')


