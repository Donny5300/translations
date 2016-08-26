<?php

	if( config( 'translations.system' ) )
	{
		Route::group( [ 'middleware' => 'web', 'namespace' => 'Donny5300\Translations\Controllers', 'prefix' => 'system/translations' ], function ()
		{
			Route::get( 'groups', [ 'as' => 'translations.groups.index', 'uses' => 'GroupsController@index' ] );
			Route::get( 'groups/create', [ 'as' => 'translations.groups.create', 'uses' => 'GroupsController@create' ] );
			Route::post( 'groups/create', [ 'as' => 'translations.groups.store', 'uses' => 'GroupsController@store' ] );
			Route::get( 'groups/{any}/edit', [ 'as' => 'translations.groups.edit', 'uses' => 'GroupsController@edit' ] );
			Route::post( 'groups/{any}/edit', [ 'as' => 'translations.groups.update', 'uses' => 'GroupsController@update' ] );
			Route::post( 'groups/{any}', [ 'as' => 'translations.groups.destroy', 'uses' => 'GroupsController@destroy' ] );

			Route::get( 'languages', [ 'as' => 'translations.languages.index', 'uses' => 'LanguagesController@index' ] );
			Route::get( 'languages/create', [ 'as' => 'translations.languages.create', 'uses' => 'LanguagesController@create' ] );
			Route::post( 'languages/create', [ 'as' => 'translations.languages.store', 'uses' => 'LanguagesController@store' ] );
			Route::post( 'languages/{any}', [ 'as' => 'translations.languages.destroy', 'uses' => 'LanguagesController@destroy' ] );
			Route::get( 'languages/{any}/edit', [ 'as' => 'translations.languages.edit', 'uses' => 'LanguagesController@edit' ] );
			Route::post( 'languages/{any}/edit', [ 'as' => 'translations.languages.update', 'uses' => 'LanguagesController@update' ] );

			Route::get( 'names', [ 'as' => 'translations.names.index', 'uses' => 'NamesController@index' ] );
			Route::get( 'names/create', [ 'as' => 'translations.names.create', 'uses' => 'NamesController@create' ] );
			Route::post( 'names/create', [ 'as' => 'translations.names.store', 'uses' => 'NamesController@store' ] );
			Route::get( 'names/{any}/edit', [ 'as' => 'translations.names.edit', 'uses' => 'NamesController@edit' ] );
			Route::post( 'names/{any}/edit', [ 'as' => 'translations.names.update', 'uses' => 'NamesController@update' ] );
			Route::post( 'names/{any}', [ 'as' => 'translations.names.destroy', 'uses' => 'NamesController@destroy' ] );

			Route::post( 'values/{any}', [ 'as' => 'translations.values.destroy', 'uses' => 'ValuesController@destroy' ] );

			# Route::get( 'missing', [ 'as' => 'translations.missing_translations', 'uses' => 'MissingTranslationsController@index' ] );
			# Route::post( 'missing', [ 'as' => 'translations.missing_translations', 'uses' => 'MissingTranslationsController@store' ] );
		} );
	}

	if( $translationUrl = config( 'translations.translations_path' ) )
	{
		Route::get( $translationUrl, 'Donny5300\Translations\Controllers\TranslationsController@translations' );
	}


