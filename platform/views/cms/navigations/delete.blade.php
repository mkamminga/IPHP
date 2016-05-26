@extends('cms.layout.backend')
@section('title', 'Categorie: '. $category->name)
@section('fcontent')
<p>Wilt u deze categorie permanent verwijderen?</p>
{!! Form::open(['route' => ['beheer.categories.destroy', $category->id], 'method' => 'delete']) !!}
	<button name="confirm" type="submit" value="true">Verwijder</button>
{!! Form::close() !!}
@endsection