@extends('cms.layout.backend')
@section('title', 'Product: '. $product->name)
@section('fcontent')
<p>Wilt u dit product permanent verwijderen?</p>
{!! Form::open(['route' => ['beheer.products.destroy', $product->id], 'method' => 'delete']) !!}
	<button name="confirm" type="submit" value="true">Verwijder</button>
{!! Form::close() !!}
@endsection