>> parent('layout::frontend.php')

>> section('title', 'login')
>> section('fcontent')
<h1>Home</h1>

<p>Welkom in de geweldige webshop van goldfingers!!!</p>

<?php
$hex = bin2hex('ë');
$ord = hexdec($hex);
print($ord);
?>
<< section('fcontent')