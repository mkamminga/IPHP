>> parent('layout::main.layout.php')

>> section('title', 'login')
>> section('content')
    >> uses errors
<div class="row">
    <div class="large-12 columns">

        <?php
        if (isset($errors) && count((array)$errors) > 0):
        ?>
            <div data-alert="" class="alert-box alert">
                <ul>
                    <?php
                    foreach ($errors as $error):
                    ?>
                        <li><?php print($error); ?></li>
                    <?php
                    endforeach;
                    ?>
                </ul>
                
            </div>
        <?php
        endif;
        ?>
        <h1>Login</h1>
        <?php
        $input = $this->service('input');
        ?>
        <hr />
        <form method="POST" action="">
            <div class="large-3 rows">
                Username
                <input type="text" name="username" value="<?php print($input->raw('username')) ?>">
            </div>

            <div class="large-3 rows">
                Password
                <input type="password" name="password" id="password">
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</div>  
<< section('content')