@extends('cms.layout.backend')
@section('title', 'Gebruiker: '. $user->name)
@section('fcontent')
<p>Wilt u deze gebruiker permanent verwijderen?</p>
{!! Form::open(['route' => ['beheer.users.destroy', $user->id], 'method' => 'delete']) !!}
	<button name="confirm" type="submit" value="true">Verwijder</button>
{!! Form::close() !!}
@endsection