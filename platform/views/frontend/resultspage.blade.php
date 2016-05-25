@extends('layout.frontend')
@section('fcontent')
    <div class="row">
        <div class="large-12 columns">
            <div class="row">

                <div id="detail-page" class="large-4 small-12 columns">

                    <img src="{{ relative_images_path() . '/'. $first->artikelnr . '/' . $first->main_image_link }} ">

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
                                <img class="detail" data-id="{{$product->id}}" src="{{ relative_images_path() . '/'. $product->artikelnr . '/' . $product->small_image_link }}">
                                <div class="panel">

                                    <h5>{{$product->name}}</h5>
                                    <h6 class="subheader">&#8364;{{$product->price}}</h6>
                                    <h6><a href="#"> <i class="fi-shopping-cart small add" data-id="{{$product->id}}"></i></a> </h6>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                $('#detail').html(product['detail']);;
                                $('#price').html('&#8364;'+product['price'])
                            }
                        }
                );

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
                            }
                        }
                );

            });
        });
    </script>
@endsection