@extends('cms.layout.backend')
@section('title', 'Orders')
@section('fcontent')

	{!! Form::open(['method' => 'get']) !!}
		<div class="row">
			<div class="large-12 columns">
				<div class="row collapse">
					<div class="small-10 columns">
					{!! Form::text('order_nr', Input::get('order_nr'), ['placeholder' => 'Order number']) !!}
					</div>

					<div class="small-2 columns">
						<button type="submit" class="button postfix">Search</button>
					</div>
				</div>
			</div>
		</div>
	{!! Form::close() !!}

	@if (count($orders) > 0)
		<table>
			<thead>
				<tr>
					<th>Acties</th>
					<th>#</th>
					<th>Totaal prijs</th>
					<th>Geplaatst op</th>
					<th>Leveren op</th>
					<th>Status</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($orders as $order)
					<tr>
						<td>
							<a href="{{ URL::Route('beheer.orders.edit', ['id' => $order->id]) }}" class="button tiny">Inzien</a>
							<a href="{{ URL::Route('beheer.orders.destroy', ['id' => $order->id]) }}" class="button tiny alert">Verwijder</a>
						</td>
						<td>{{ $order->id }}</td>
						<td>&euro; {{ number_format($order->total, 2, ',', '.') }}</td>
						<td>{{ $order->formatted_create_date }}</td>
						<td>{{ $order->formatted_deliver_date }}</td>
						<td>{{ (array_key_exists($order->status, $orderStates) ? $orderStates[$order->status] : $order->status) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		{!! $orders->render() !!}
	@endif
@endsection