@extends('layout.frontend')

@section('fcontent')

<div class="row">
    <div class="large-12 columns">
        <div class="row">
            <div>
                <h3>Please enter your shipping info</h3>
            </div>
            @if (count($errors) > 0)
                <div data-alert="" class="alert-box alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif
            <div class="large-12 rows">
              {!! Form::model($order, array('url' =>'postCheckout')) !!}
                <div class='large-6 rows'>
                  <label><b>Firstname</b></label>
                  {!! Form::text('firstname') !!} 
                </div>

                <div class='large-6 rows'>
                  <label><b>Lastname</b></label>
                  {!! Form::text('lastname') !!} 
                </div>

                <div class='large-6 rows'>
                  <label><b>Country</b></label>
                  {!! Form::select('country', $countries) !!} 
                </div>

                <div class='large-6 rows'>
                  <label><b>Residence</b></label>
                  {!! Form::text('city') !!} 
                </div>

                <div class='large-6 rows'>
                  <label><b>Address</b></label>
                  {!! Form::text('adres') !!} 
                </div>

                <div class='large-6 rows'>
                  <label><b>Zip</b></label>
                  {!! Form::text('zip') !!} 
                </div>

                <div class='large-6 rows'>
                  <label><b>Email</b></label>
                  {!! Form::email('email') !!} 
                </div>

                <div class='large-6 rows'>
                  <label><b>Tel</b></label>
                  {!! Form::tel('telephone') !!} 
                </div>

                <div class='large-6 rows'>
                    <button type="submit" role="button" aria-label="submit form" class="button">Confirm</button>
                </div>
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection


