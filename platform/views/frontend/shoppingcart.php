>> parent('layout::frontend.php')

>> section('title', 'Winkelmandje')
>> section('fcontent')
    >> uses shoppingcart
    <?php
    $url = $this->service('url');
    ?>
    <div class="row">
        <div class="large-12 columns">
            <div class="row">
                <div id="cart-frame">
                    >> partial('partials::shoppingcart.php')
                </div>    
                
                <div class="checkout">
                    <?php
                    if (count($shoppingcart) > 0):
                    ?>
                        <a href="<?php print($url->route('CheckoutShow')); ?>" class="button bt-checkout">Check out</a>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
<< section('fcontent')

>> section('scripts')
    >> parent
    <script>
        $(document).ready(function(){
            $(document).on('click','.tb-cart i.remove-item' ,function(){
                var id = $(this).data('id');
                var url = '<?php print($url->route('CartItemRemove')); ?>';
                var params={"id" : $(this).data('id'), "quantity" : 1};

                $.post(url, params, function(result) {
                    if (result){
                        if (result.status == 'success'){
                            updateCartCount(result.data.count);
                            loadCart();
                        }
                    }
                }, 'json');
            });
            //Update cart quantity
            $(document).on('change','.tb-cart .cart-item-quantity' ,function(){
                var quantity = parseInt($(this).val());
                var id = $(this).data('id');
                var url = '<?php print($url->route('CartItemQuantityUpdate')); ?>';
                var params={"id" : id, "quantity" : quantity};

                if (quantity >= 1) {
                    $.post(url, params, function(result) {
                        if (result){
                            if (result.status == 'success'){
                                updateCartCount(result.data.count);
                                //reload cart
                                loadCart();
                            }
                        }
                    }, 'json');
                }
            });
        });

        function loadCart () {
            var url = '<?php print($url->route('CartShowAjaxCart')); ?>';
            $("#cart-frame").load(url);
        }
    </script>
<< section('scripts')