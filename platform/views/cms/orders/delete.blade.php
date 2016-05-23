@extends('cms.layout.backend')
@section('title', 'Order: #'. $order->id)
@section('fcontent')
<p>Wilt u deze order permanent verwijderen?</p>
{!! Form::open(['route' => ['beheer.orders.destroy', $order->id], 'method' => 'delete']) !!}
	<button name="confirm" type="submit" value="true">Verwijder</button>
{!! Form::close() !!}
@endsection