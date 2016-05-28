<?php

	Route::group( [ 'middleware' => 'web' ], function ()
	{
		Route::get( 'system/translations/groups', [ 'as' => 'donny5300.translations.groups.index', 'uses' => 'Donny5300\Translations\Controllers\GroupsController@index' ] );
		Route::get( 'system/translations/groups/create', [ 'as' => 'donny5300.translations.groups.create', 'uses' => 'Donny5300\Translations\Controllers\GroupsController@create' ] );
		Route::post( 'system/translations/groups/create', [ 'as' => 'donny5300.translations.groups.store', 'uses' => 'Donny5300\Translations\Controllers\GroupsController@store' ] );
		Route::get( 'system/translations/groups/{any}/edit', [ 'as' => 'donny5300.translations.groups.edit', 'uses' => 'Donny5300\Translations\Controllers\GroupsController@edit' ] );
		Route::post( 'system/translations/groups/{any}/edit', [ 'as' => 'donny5300.translations.groups.update', 'uses' => 'Donny5300\Translations\Controllers\GroupsController@update' ] );
		Route::post( 'system/translations/groups/{any}', [ 'as' => 'donny5300.translations.groups.destroy', 'uses' => 'Donny5300\Translations\Controllers\GroupsController@destroy' ] );

		Route::get( 'system/translations/languages', [ 'as' => 'donny5300.translations.languages.index', 'uses' => 'Donny5300\Translations\Controllers\LanguagesController@index' ] );
		Route::get( 'system/translations/languages/create', [ 'as' => 'donny5300.translations.languages.create', 'uses' => 'Donny5300\Translations\Controllers\LanguagesController@create' ] );
		Route::post( 'system/translations/languages/create', [ 'as' => 'donny5300.translations.languages.store', 'uses' => 'Donny5300\Translations\Controllers\LanguagesController@store' ] );
		Route::get( 'system/translations/languages/{any}/edit', [ 'as' => 'donny5300.translations.languages.edit', 'uses' => 'Donny5300\Translations\Controllers\LanguagesController@edit' ] );
		Route::post( 'system/translations/languages/{any}/edit', [ 'as' => 'donny5300.translations.languages.update', 'uses' => 'Donny5300\Translations\Controllers\LanguagesController@update' ] );
		Route::post( 'system/translations/languages/{any}', [ 'as' => 'donny5300.translations.languages.destroy', 'uses' => 'Donny5300\Translations\Controllers\LanguagesController@destroy' ] );

		Route::get( 'system/translations/names', [ 'as' => 'donny5300.translations.names.index', 'uses' => 'Donny5300\Translations\Controllers\NamesController@index' ] );
		Route::get( 'system/translations/names/create', [ 'as' => 'donny5300.translations.names.create', 'uses' => 'Donny5300\Translations\Controllers\NamesController@create' ] );
		Route::post( 'system/translations/names/create', [ 'as' => 'donny5300.translations.names.store', 'uses' => 'Donny5300\Translations\Controllers\NamesController@store' ] );
		Route::get( 'system/translations/names/{any}/edit', [ 'as' => 'donny5300.translations.names.edit', 'uses' => 'Donny5300\Translations\Controllers\NamesController@edit' ] );
		Route::post( 'system/translations/names/{any}/edit', [ 'as' => 'donny5300.translations.names.update', 'uses' => 'Donny5300\Translations\Controllers\NamesController@update' ] );
		Route::post( 'system/translations/names/{any}', [ 'as' => 'donny5300.translations.names.destroy', 'uses' => 'Donny5300\Translations\Controllers\NamesController@destroy' ] );

		Route::get( 'system/translations/values', [ 'as' => 'donny5300.translations.values.index', 'uses' => 'Donny5300\Translations\Controllers\ValuesController@index' ] );
		Route::get( 'system/translations/values/create', [ 'as' => 'donny5300.translations.values.create', 'uses' => 'Donny5300\Translations\Controllers\ValuesController@create' ] );
		Route::post( 'system/translations/values/create', [ 'as' => 'donny5300.translations.values.store', 'uses' => 'Donny5300\Translations\Controllers\ValuesController@store' ] );
		Route::get( 'system/translations/values/{any}/edit', [ 'as' => 'donny5300.translations.values.edit', 'uses' => 'Donny5300\Translations\Controllers\ValuesController@edit' ] );
		Route::post( 'system/translations/values/{any}/edit', [ 'as' => 'donny5300.translations.values.update', 'uses' => 'Donny5300\Translations\Controllers\ValuesController@update' ] );
		Route::post( 'system/translations/values/{any}', [ 'as' => 'donny5300.translations.values.destroy', 'uses' => 'Donny5300\Translations\Controllers\ValuesController@destroy' ] );

		Route::get( 'system/translations/clean-up', [ 'as' => 'donny5300.translations.clean', 'uses' => 'Donny5300\Translations\Controllers\CleanUpController@cleanUp' ] );
		Route::get( 'system/translations/clean-up/restore-all', [ 'as' => 'donny5300.translations.clean', 'uses' => 'Donny5300\Translations\Controllers\CleanUpController@restore' ] );
		Route::get( 'system/translations/clean-up/delete-all', [ 'as' => 'donny5300.translations.clean', 'uses' => 'Donny5300\Translations\Controllers\CleanUpController@delete' ] );
		Route::get( 'translations', 'Donny5300\Translations\Controllers\TranslationsController@translations' );
	} );


