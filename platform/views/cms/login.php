>> parent('layout::main.layout.php')

>> section('title', 'login')
>> section('content')
   	>> uses errors
	
<div class="row">
    <div class="large-12 columns">
        >> partial('partials::login.php')
    </div>
</div>  
<< section('content')