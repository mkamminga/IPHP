@extends('layout.frontend')

@section('fcontent')
    <div class="row">
        <div class="large-12 columns">
            <h1>Thank you for your purchase!</h1>
            <h3>A confirmation email has been sent to your email adress</h3>
            <h5>Got to <a href="{{url('categories')}}">home</a></h5>
        </div>
    </div>


@endsection

@section('scripts')
    @parent
    <script>
            $( window ).load(function() {
                $('.li-cart').text("0 items in your cart");
            });

    </script>
    @endsection
