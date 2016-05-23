<div class="fixed"  style="background-color: white">
    <div class="large-2 small-6 columns">
        <img src="{{asset('images/logo.png')}}" style="width:100px;height:75px;">
    </div>
    <div class="row">

        <div class="small-2 large-2 columns">
            <h1 style="color: #ffcc00">Goldenfingers</h1>
        </div>
        <div class="large-3 columns">
            <a href="/shoppingcart">
                <div class="panel callout radius">
                    <h6 class="li-cart">{{ $pCount }} items in your cart</h6>
                </div>
            </a>
        </div>
    </div>

    <nav class="top-bar" data-topbar role="navigation">
        <ul class="title-area">
            <li class="name">
                <h1><a href="{{url('categories')}}">GoldenFingers</a></h1>
            </li>
        
            <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
        </ul>

        <section class="top-bar-section">
        <!-- Left Nav Section -->
        <ul class="left">
            @foreach ($menus as $menu)
                <li><a href="{{url($menu->link)}}">{{ $menu->name }}</a></li>
            @endforeach
        </ul>
        
        <!-- Right Nav Section -->
        <ul class="right">
            @if (!$user)
                <li>
                    <a href="/login">Log in</a>
                </li>
            @else
                <li><a href="#">Welkom: {{ $user->name  . ' '. $user->lastname}}</a></li> 
                <li><a href="/logout">Log out</a></li>
            @endif    
            <li class="has-form">
                <div class="row collapse div-search">
                    <div class="large-8 small-9 columns">
                        <input class="input-search" type="text" placeholder="Products">
                    </div>
                    <div class="large-4 small-3 columns">
                        <a href="#" class="alert button expand search">Search</a>
                    </div>
                </div>
            </li>
        </ul>

        </section>
    </nav>
    {!! Breadcrumbs::renderIfExists() !!}

</div>

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.div-search').on('click','a.search' ,function() {
                var value = $('.input-search').val();
                if(value == '')
                {
                    alert('Nothing to search please put in a word')
                }
                else {
                        $.ajax({
                        url: '/ajax/searchproduct/' + value,
                        method: "get",
                        dataType: 'json',
                        success: function (data) {
                            if(data.found ==1) {
                                window.location.replace("{{url('resultspage')}}");
                            }
                            else{
                                alert(data.nothing);
                            }
                        }
                    })
                }
            })
        });

    </script>
    @endsection