<?php
	return [
			'debug'             => config( 'app.debug' ), # If debug enabled, cache is disabled
			'system'            => true, # If enabled, the build in translation interface is available
			# at /system/translations/languages
			'translations_path' => '/translations', # Path for a JSON feed
			'database'          => [
					'id_field'      => 'id', # Will be used for first or new
					'create_update' => true # Set Eloquent timestamps
			],
			'depth'             => 3, # The maxdepth of the groups
			'list'              => [
					'uc_first'  => true, # Set all groups title's to uc_first()
					'delimeter' => ' > ', # Used for displaying groups
			],
			'cache'             => [
					'key'        => 'translations', # Cache Key
					'auto_clear' => true, # Auto clear the cache when a model is updated/created
			],
			'twig'              => [
					'translate_filter'   => [ 't' ], # Filter names for twig
					'translate_function' => [ 'translate' ] # Function names for twig
			],
			'helpers'           => [
					'singleton'  => 'translations', # Specify a string for the singleton instance to
					# use app('translations') as example
					'translator' => true, # Enable to use the translator() function. Same as using the singleton instance
			]
	];