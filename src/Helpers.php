<?php
	if( !function_exists( 'translate' ) )
	{

		/**
		 * @param $key
		 *
		 * @return mixed|string
		 * @throws \Illuminate\Contracts\Container\BindingResolutionException
		 */
		function translate( $key )
		{
			return call_user_func_array(
					[ app( 'translations' ), 'translate' ],
					( [ $key ] + func_get_args() )
			);
		}
	}

	if( !function_exists( 'translator' ) )
	{
		/**
		 * @return \Donny5300\Translations\Builder
		 */
		function translator()
		{
			return app( config('translations.helpers.singleton') );
		}

	}

	if( !function_exists( 'generate_uuid' ) )
	{
		/**
		 * @param bool|false $prefix
		 * @param bool|false $braces
		 *
		 * @return string
		 */
		function generate_uuid( $prefix = false, $braces = false )
		{
			mt_srand( (double) microtime() * 10000 );
			$charid = strtoupper( md5( uniqid( $prefix === false ? rand() : $prefix, true ) ) );
			$hyphen = chr( 45 ); // "-"
			$uuid   = substr( $charid, 0, 8 ) . $hyphen
					. substr( $charid, 8, 4 ) . $hyphen
					. substr( $charid, 12, 4 ) . $hyphen
					. substr( $charid, 16, 4 ) . $hyphen
					. substr( $charid, 20, 12 );

			// Add brackets or not? "{" ... "}"
			return $braces ? chr( 123 ) . $uuid . chr( 125 ) : $uuid;
		}
	}


