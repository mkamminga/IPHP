>> parent('layout::frontend.php')

>> section('title', 'login')
>> section('fcontent')
    >> uses errors
<div class="row">
    <div class="large-12 columns">
        >> partial('partials::login.php')
        <hr />
        <?php
        $url = $this->service('url');
        ?>
        <a class="small button secondary" href="<?php print($url->route('RegisterGet')) ?>">Nieuwe gebruiker? Registreer hier!</a>
    </div>
</div>  
<< section('fcontent')