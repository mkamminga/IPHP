>> parent('layout::frontend.php')

>> section('title', 'login')
>> section('fcontent')
    >> uses errors
    >> uses countries
<div class="row">
    <div class="large-12 columns">
        <?php
        if (isset($errors)):
            print($this->service('htmlMessages')->errors($errors));
        endif;

        $form = $this->service('form');
        $input = $this->service('input');
        ?>
        <h1>Register</h1>
        <form method="post" action="">
            <div class='large-6 rows'>
               <label>Gebruikersnaam
                    <?php print($form->text('username', $input->get('username'))); ?>
                </label>
            </div>

            <div class='large-6 rows'>
                <label>Wachtwoord
                    <?php print($form->password('password')); ?>
                </label>
            </div>

            <div class='large-6 rows'>
                <label>Herhaal wachtwoord
                    <?php print($form->password('password_confirmation')); ?>
                </label>
            </div>
            <hr />
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

            <div class="large-3 rows">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</div>
<< section('fcontent')