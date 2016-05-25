@extends('layout.frontend')

@section('fcontent')
    <div class="row">
        <div class="large-12 columns">
            <div class="row">
                <div id="cart-frame">
                    @include('partials.shoppingcart')
                </div>    
                
                <div class="checkout">
                    @if (count($shoppingcart) > 0)
                        <a href="{{url('checkout')}}" class="button bt-checkout">Check out</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function(){
            $(document).on('click','.tb-cart i.remove-item' ,function(){
                var id = $(this).data('id');
                $.ajax({
                    url: '/ajax/removeitem/'+id,
                    method: "get",
                    dataType: 'json',
                    success: function(data) {
                        $('.li-cart').text(""+data.pCount+ " items in your cart");
                        updateCart($('#cart-frame'));
                    }
                });
            });

            $(document).on('change','.tb-cart .cart-item-quantity' ,function(){
                
                var quantity = parseInt($(this).val());
                var id = $(this).parents('.cart-row-item').data('id');

                if (quantity >= 1) {
                    $.ajax({
                        url: 'ajax/updateitem-quantity/'+ id +'/' + quantity,
                        method: "get",
                        dataType: 'json',
                        success: function(data) {
                            $('.li-cart').text(""+data.pCount+ " items in your cart");

                            updateCart($('#cart-frame'));
                        }
                    });
                }
            });

            function updateCart (frame) {
                frame.load('/ajax/shoppingcartonly/');
            }
        });
    </script>
@endsection