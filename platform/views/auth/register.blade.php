@extends('layout.frontend')
@section('title', 'Register')
@section('fcontent')
<div class="row">
    <div class="large-12 columns">
        @if (count($errors) > 0)
            <div data-alert="" class="alert-box alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                
            </div>
        @endif

        <h1>Register</h1>
        <form method="POST" action="/register">
            {!! csrf_field() !!}

            <div>
                Username
                <input type="text" name="username" value="{{ old('username') }}">
            </div>

            <div>
                Password
                <input type="password" name="password">
            </div>

            <div>
                Confirm Password
                <input type="password" name="password_confirmation">
            </div>

            <div>
                Firstname
                <input type="text" name="name" value="{{ old('name') }}">
            </div>

            <div>
                Lastname
                <input type="text" name="lastname" value="{{ old('lastname') }}">
            </div>

            <div>
                Email
                <input type="email" name="email" value="{{ old('email') }}">
            </div>

             <div>
                Country
                <select name="country">           
                    @foreach($countries as $code => $country)

                        <option value="{{ $code }}"{{ ($code == old('country') ? ' selected="selected"' : '') }}>{{ $country['title'] }}</option>
                    @endforeach
                </select>
            </div>

            
            <div>
                Residence
                <input type="text" name="city" value="{{ old('city') }}">
            </div>
            
            <div>
                Address
                <input type="text" name="address" value="{{ old('address') }}">
            </div>
            
            <div>
                Zip
                <input type="text" name="zip" value="{{ old('zip') }}">
            </div>

            <div>
                <button type="submit">Register</button>
            </div>
        </form>
    </div>
</div>
@endsection