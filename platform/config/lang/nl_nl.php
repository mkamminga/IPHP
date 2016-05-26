<?php
return [
	'validator' => [
		'required' => ':field is verplicht!',
		'number' => ':field is geen getal!',
		'min' => ':field moet minimaal :size tekens lang zijn!',
		'max' => ':field mag maar :size lang zijn!',
		'array' => ':field is geen lijst!',
		'alpha_num' => ':field mag alleen letters en getallen bevatten!',

		//Custom
		'auth' => 'Gebruikersnaam en wachtwoord komen niet overeen!'
	]
];