>> parent('layout::main.layout.php')

>> section('title', 'Pagina niet gevonden!')
>> section('content')
<div class="row">
    <div class="row">
        <div class="small-2 large-2 columns">
            <h1 style="color: #ffcc00">Goldenfingers</h1>
        </div>
    </div>
    <div class="row">
        <div class="small-10 large-centered columns">
            <h1>O, nee!</h1>
            <hr />
            <?php
            print($this->service('htmlMessages')->warning('Er is iets mis gegaan', 'De pagina die u zocht bestaat niet!'));
            ?>
            <a href="/">Klik op deze link voor de home pagina.</a>
        </div>
    </div>
</div>
<< section('content')