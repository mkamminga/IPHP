@extends('layout.main')

@section('content')
    <div class="container row">        
        <div class="row">
            <div class="large-12 columns">
                <div class="panel">
                    <h1>CMS</h1>
                    <span class="text-aligned-right">Welkom {{ $user->username }}</span>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="large-3 columns">
                <div class="panel">
                    <h3>Menu</h3>
                    <ul class="side-nav">
                        <li><a href="{{ URL::route('beheer.dashboard.index') }}">Dashboard</a></li>
                        <li><a href="{{ URL::route('beheer.orders.index') }}">Orders</a></li>
                        <li><a href="{{ URL::route('beheer.products.index') }}">Products</a></li>
                        <li><a href="{{ URL::route('beheer.categories.index') }}">Categories</a></li>
                        <li><a href="{{ URL::route('beheer.users.index') }}">Gebruikers</a></li>
                        <li><a href="{{ URL::route('getUploadZip') }}">Import</a></li>
                    </ul>
                </div> 
            </div>
            
            <div class="large-9 columns">    
                <h2>@yield('title')</h2>
                <div class="content">
                    @yield('fcontent')
                </div>
            </div>    
        </div>

        <footer class="row">
            <div class="large-12 columns">
                <hr/>
                <div class="panel">
                    <div class="row">
                        <div class="large-2 small-6 columns">
                            <img src="{{ asset('images/cthulu.jpg') }}">
                        </div>

                        <div class="large-10 small-6 columns">
                            <strong>This Site Is Managed By</strong>

                            Our overlord Cthulu
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <p>&copy; Copyright Cthulu productions  </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>    
@endsection

