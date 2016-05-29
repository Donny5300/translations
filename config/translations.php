<?php

	use App\Http\Controllers\Controller;

	return [

		'system_views'      => [
			'namespaces'  => false,
			'modules'     => false,
			'controllers' => false,
			'methods'     => false,
			'exceptions'  => false,
			'system'      => false,
			'sync'        => false,
		],
		'translations_path' => url( '/translations' )

	];