@extends('cms.layout.backend')
@section('title', 'Gebruikers')
@section('fcontent')
	{!! Form::open(['method' => 'get']) !!}
		<div class="row">
			<div class="large-12 columns">
				<div class="row collapse">
					<div class="small-10 columns">
					{!! Form::text('username', Input::get('username'), ['placeholder' => 'Username']) !!}
					</div>

					<div class="small-2 columns">
						<button type="submit" class="button postfix">Search</button>
					</div>
				</div>
			</div>
		</div>
	{!! Form::close() !!}
	<a href="{{ URL::Route('beheer.users.create') }}" class="button success">Nieuwe gebruiker</a>

	@if (count($users) > 0)
		<table>
			<thead>
				<tr>
					<th>Acties</th>
					<th>Gebruikersnaam</th>
					<th>Bevoegdheid</th>
					<th>Aangemaakt op</th>
					<th>Naam</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($users as $user)
					<tr>
						<td>
							<a href="{{ URL::Route('beheer.users.edit', ['id' => $user->id]) }}" class="button tiny">Bewerk</a>
							<a href="{{ URL::Route('beheer.users.destroy', ['id' => $user->id]) }}" class="button tiny alert">Verwijder</a>
						</td>
						<td>{{ $user->username }}</td>
						<td>{{ $user->group->description }}</td>
						<td>{{ $user->created_at }}</td>
						<td>{{ $user->name . ' ' . $user->last_name }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		{!! $users->render() !!}
	@endif
@endsection