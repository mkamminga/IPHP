<?php
return [
	'validator' => [
		'required' => ':field is verplicht!',
		'number' => ':field is geen getal!',
		'min' => ':field moet minimaal :size tekens lang zijn!',
		'max' => ':field mag maar :size lang zijn!',
		'array' => ':field is geen lijst!',
		'alpha_num' => ':field mag alleen letters en getallen bevatten!',
		'mime' => ':field is niet toegestaan! Gebruik een van de volgende :types extensies!',
		'email' => ':field is geen geldig email adres!',
		//Custom
		'auth' => 'Gebruikersnaam en wachtwoord komen niet overeen!'
	],
	//Orderstates
	'orderstates' => [
		'placed' => 'Order is bevestiged',
		'payed' => 'Order is betaald',
		'fetching' => 'Order aan het verzamelen',
		'sent' => 'Order is verzonden',
		'deliverd' => 'Order is afgeleverd',
		'return' => 'Order is retour gekomen'
	]
];