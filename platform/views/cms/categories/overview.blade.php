@extends('cms.layout.backend')
@section('title', 'Categories')
@section('fcontent')
	<a href="{{ URL::Route('beheer.categories.create') }}" class="button success">Nieuwe categorie</a>
	<table>
		<thead>
			<tr>
				<th>Acties</th>
				<th>Naam</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($categories as $category)
				<tr>
					<td>
						<a href="{{ URL::Route('beheer.categories.edit', ['id' => $category->id]) }}" class="button tiny">Bewerk</a>
						<a href="{{ URL::Route('beheer.categories.destroy', ['id' => $category->id]) }}" class="button tiny alert">Verwijder</a>
					</td>
					<td>{{ $category->name }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection