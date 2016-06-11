>> section('shoppingcart')
    <?php
    $this->service('htmlMessages')->warningClass('callout warning');
    ?>
   >> partial('partials::shoppingcart.php')
<< section('shoppingcart')

<< show('shoppingcart', '')