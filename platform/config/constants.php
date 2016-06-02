<?php
$public = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
$images_dir = DIRECTORY_SEPARATOR . 'images';
return [
	'paths' => [
		'public'	=> [
			'public_path' => $public,
			'images' => [
				'images_dir' => $images_dir,
				'product_images_dir' => $images_dir . DIRECTORY_SEPARATOR . 'products',
				'categories_images_dir' => $images_dir . DIRECTORY_SEPARATOR . 'categories'
			]
		],
		'config_path' => __DIR__
	]	
];