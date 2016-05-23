@extends('cms.layout.backend')
@section('title', 'Upload zip')
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

	{!! Form::open(['route' => ['postUploadZip'], 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
		<div class="row">
		    <div class="large-12 columns">
		      <label>Categorie
		        {!! Form::file('zip') !!}
		      </label>
		    </div>
		</div>

		<div class="row">
			<div class="large-12 columns">
				<button type="submit" role="button" aria-label="submit form" class="button">Verstuur</button>
			</div>
		</div>
	{!! Form::close() !!}
	<hr />
	<a class="button small secondary" href="{{ URL::route('postUploadZip') }}">Next</a>
@endsection