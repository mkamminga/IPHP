>> parent('cms::layout::backend.php')

>> section('title', 'Bevestiging verwijdering')

>> section('fcontent')
	>> uses name
<p>Wilt u '<?php print($name); ?>' permanent verwijderen?</p>
<form action="" method="post">
	<button name="confirm" type="submit" value="true">Bevestig</button>
</form>
<< section('fcontent')