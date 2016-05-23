@extends('cms.layout.backend')
@section('title', 'Nieuw product')
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

	{!! Form::open(['route' => ['beheer.products.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
		<div class="row">
		    <div class="large-12 columns">
		      <label>Categorie
		        {!! Form::select('category', $categories) !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Artikelnr.
		        {!! Form::text('artikelnr') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Naam
		        {!! Form::text('name') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Price
		        {!! Form::text('price') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Korte beschrijving
					{!! Form::textarea('short_description', Input::old('short_description')) !!}
				</label>
			</div>
		</div>		

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Lange beschrijving
					{!! Form::textarea('detail', Input::old('detail')) !!}
				</label>
			</div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Kleine afbeelding
					{!! Form::file('small_image_link') !!}
				</label>
			</div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      	<label>Grote afbeelding
					{!! Form::file('main_image_link') !!}
				</label>
			</div>
		</div>

		<div class="row">
		    <div class="large-12 columns">
		      <label>Vat
		        {!! Form::select('vat', $vat) !!}
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