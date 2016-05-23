@extends('cms.layout.backend')
@section('title', 'Nieuwe categorie')
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
	{!! Form::model($category, ['route' => ['beheer.categories.update', $category->id], 'method' => 'put']) !!}
		<div class="row">
		    <div class="large-12 columns">
		      <label>Naam
		        {!! Form::text('name') !!}
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