@extends('cms.layout.backend')
@section('title', 'Bewerk gebruiker: '. $user->username )
@section('fcontent')
	@if (count($errors) > 0)
		<div data-alert="" class="alert-box alert">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	        
	    </div>
	@endif

	{!! Form::model($user, ['route' => ['beheer.users.update', $user->id], 'method' => 'put']) !!}
		<div class="row">
		    <div class="large-12 columns">
		      <label>Gebruikersnaam
		        {!! Form::text('username') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Wachtwoord
		        {!! Form::password('password') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Herhaal uw wachtwoord 
		        {!! Form::password('password_confirmation') !!}
		      </label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Gebruikers groep 
		        {!! Form::select('group_id', $groups) !!}
		      </label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Actief</label>

		        {!! Form::radio('active', '1') !!} Ja 
		        {!! Form::radio('active', '0') !!} Nee
		      
			</div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Voornaam
		        {!! Form::text('name') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Achternaam
		        {!! Form::text('lastname') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Land
		        {!! Form::select('country', $countries) !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Woonplaats
		        {!! Form::text('city') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Adres
		        {!! Form::text('address') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Postcode
		        {!! Form::text('zip') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Email
		        {!! Form::text('email') !!}
		      </label>
		    </div>
		</div>		

		<div class="row">
			<div class="large-12 columns">
				<button type="submit" role="button" aria-label="submit form" class="button">Verstuur</button>
			</div>
		</div>
	{!! Form::close() !!}
@endsection