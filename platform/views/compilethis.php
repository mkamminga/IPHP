>> parent('layout.php')
>> section('title', '>> uses title {{ title }}')
>> section('content')
	>> uses data
	>> parent
<div class="content">
	<p>Content</p>
	<?php
	var_dump($data);
	?>
</div>
<< section('content')