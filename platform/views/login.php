>> parent('layout::frontend.php')

>> section('title', 'login')
>> section('fcontent')

<div class="row">
    <div class="large-12 columns">

        <?php
        if (isset($errors) && count($errors) > 0):
        ?>
            <div data-alert="" class="alert-box alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                
            </div>
        <?php
        endif;
        ?>
        <h1>Login</h1>
        <a href="<?php print($this->service('url')->route('LoginGet')); ?>">URL</a>
        <?php
        $input = $this->service('input');
        ?>
        <form method="POST" action="">
            <div class="large-3 rows">
                Username
                <input type="text" name="username" value="<?php print($input->escaped('username')) ?>">
            </div>

            <div class="large-3 rows">
                Password
                <input type="password" name="password" id="password">
            </div>

            <div>
                <input type="checkbox" name="remember"> Remember Me
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>
        <hr />
        <a class="small button secondary" href="/register">New user? Register here!</a>
    </div>
</div>  
<< section('fcontent')