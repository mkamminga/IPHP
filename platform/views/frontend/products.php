>> parent('layout::frontend.php')
>> section('title', 'Products')
>> section('fcontent')
    >> uses products
    <div class="row">
        <div class="large-12 columns">
            <div class="row">
                <h1>Products</h1>
                <div class="large-8 columns">
                    <div class="row">
                        <?php
                        $products = (array)$products;
                        foreach($products as $product):
                        ?>
                            <div class="large-4 small-6 columns" >
                                <img class="detail" style="width:300px;height:175px;" data-id="{{$product->id}}" src="{{ relative_images_path() . '/'. $product->artikelnr . '/' . $product->small_image_link }}">
                                <div class="panel" id="p-panel-{{$product->id}}">
                                    <h5>{{$product->name}}</h5>
                                    <p><b style="float:left">&#8364;{{$product->price}}</b> <a style="float:right" href="#"> <i class="fi-shopping-cart small add" data-id="{{$product->id}}"></i></a> </p>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
<< section('fcontent')

>> section('scripts')
    >> parent
    <script>
        $(document).ready(function(){
            $('.detail').on('click' ,function(){
                var product = null;
                var id = $(this).data('id');
                $.ajax({
                    url: '/ajax/products/'+id,
                    method: "get",
                    dataType: 'json',
                     success: function(data){
                         product = data.product;
                         $('#name').text(product['name']);
                         $('#detail').html(product['detail']);
                         $('#price').html('&#8364;'+product['price']);
                         var src = ("{{ relative_images_path()}}"+ "/"+ product['artikelnr'] + "/"+ product['main_image_link']);
                         $('#img-detail').attr('src',src);
                    }
                });

            });

            $('.add').on('click' ,function(){
                var pCount = 0;
                var id = $(this).data('id');
                $.ajax({
                    url: '/ajax/shoppingcart/'+id,
                    method: "get",
                    dataType: 'json',
                    success: function(data){
                        pCount = data.pCount;
                        $('.li-cart').text("" + pCount + " items in your cart");
                        $('#p-panel-'+id).append('<div data-alert class="alert-box success radius" id="div-temp">Added to cart</div>');
                        setTimeout(
                                function()
                                {
                                    $("#div-temp").fadeOut( "slow", function() {
                                        $('#div-temp').remove();
                                    });

                                }, 1000);
                    }
                });
            });
        });
    </script>
>> section('scripts')