@extends('layout.frontend')
@section('title', 'Products')
@section('fcontent')
    <div class="row">
        <div class="large-12 columns">
            <div class="row">
                <h1>Products</h1>
                <div id="detail-page" class="large-4 small-12 columns">

                    <img id="img-detail" src="{{ relative_images_path() . '/'. $first->artikelnr . '/' . $first->main_image_link }} ">

                    <div class="hide-for-small panel">
                        <h3 id="name">{{ $first->name }}</h3>
                        <h5 id="detail" class="subheader">{{ $first->detail }}</h5>
                        <h5 id="price">&#8364;{{ $first->price }}</h5>
                    </div>
                </div>


                <div class="large-8 columns">
                    <div class="row">
                        @foreach($products as $product)

                            <div class="large-4 small-6 columns" >
                                <img class="detail" style="width:300px;height:175px;" data-id="{{$product->id}}" src="{{ relative_images_path() . '/'. $product->artikelnr . '/' . $product->small_image_link }}">
                                <div class="panel" id="p-panel-{{$product->id}}">
                                    <h5>{{$product->name}}</h5>
                                    <p><b style="float:left">&#8364;{{$product->price}}</b> <a style="float:right" href="#"> <i class="fi-shopping-cart small add" data-id="{{$product->id}}"></i></a> </p>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        {!! $products->render() !!}
    </div>

@endsection

@section('scripts')
    @parent
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
@endsection