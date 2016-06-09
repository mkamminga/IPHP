<?php
if (isset($errors)):
    print($this->service('htmlMessages')->errors($errors));
endif;

$form = $this->service('form');
$input = $this->service('input');
?>
<h1>Login</h1>
<hr />
<form method="POST" action="">
    <div class="large-3 rows">
        <label>Gebruikersnaam
            <?php print($form->text('username', $input->raw('username'))) ?>
        </label>
    </div>

    <div class="large-3 rows">
            <label>Wachtwoord
            <?php print($form->password('password')) ?>
        </label>
    </div>

    <div class="large-3 rows">
        <button type="submit">Login</button>
    </div>
</form>