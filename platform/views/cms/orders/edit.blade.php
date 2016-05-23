@extends('cms.layout.backend')
@section('title', 'Bewerk order')

@section("scripts")
	@parent
	<script type="text/javascript" src="/js/vendor/jquery-ui.js"></script>
	<script type="text/javascript">
	$( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
	</script>
@endsection

@section("styles")
	@parent
	<link type="text/css" rel="stylesheet" href="/css/jquery-ui.min.css" />
@endsection

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

	{!! Form::model($order, ['route' => ['beheer.orders.update', $order->id], 'method' => 'put']) !!}
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
		        {!! Form::text('adres') !!}
		      </label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Zip 
		        {!! Form::text('zip') !!}
		      </label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Telefoon 
		        {!! Form::text('telephone') !!}
		      </label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Leverdatum 
		        {!! Form::text('formatted_deliver_date', Input::get('formatted_deliver_date'), ['class' => 'datepicker', 'readonly' => true]) !!}
		      </label>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
		      <label>Status 
		        {!! Form::select('status', $orderStates) !!}
		      </label>
			</div>
		</div>
		<hr />
		<table>
			<thead>
				<th>Product</th>
				<th>Stuksprijs</th>
				<th>Aantal</th>
				<th>Subtotaal</th>
			</thead>
			
			<tbody>
			@if (isset($order->rows))
				@foreach ($order->rows as $orderrow)
					<tr>
						<td>
						{{ $orderrow->product->name }}
						</td>

						<td>
						&euro; {{ number_format($orderrow->price, 2, ',', '.') }}
						</td>

						<td>
						{{ $orderrow->quantity }}
						</td>

						<td>
						&euro; {{ number_format($orderrow->quantity * $orderrow->price, 2, ',', '.') }}
						</td>
					</tr>
				@endforeach
			@endif
			</tbody>	
		</table>
		<hr />
		<table>
		@foreach ($vat as $vatTotal)
			<tr>
				<td>
				Btw {{ $vatTotal->vat }}%
				</td>

				<td>
				&euro; {{ number_format($vatTotal->total, 2, ',', '.') }}
				</td>
			</tr>
		@endforeach

			<tr>
				<td>Totaal</td>
				<td>
				&euro; {{ number_format($order->total, 2, ',', '.') }}
				</td>
			</tr>
		</table>

		<hr />

		<h3>Klant gegevens</h3>
		<table>
			<tr>
				<td>Naam</td>
				<td>{{ $order->firstname . ' '. $order->lastname }}</td>
			</tr>
			<tr>
				<td>Email</td>
				<td>{{ $order->email }}</td>
			</tr>
		</table>

		<div class="row">
			<div class="large-12 columns">
				<button type="submit" role="button" aria-label="submit form" class="button">Verstuur</button>
			</div>
		</div>
	{!! Form::close() !!}
@endsection