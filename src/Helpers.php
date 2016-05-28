<?php


	if( !function_exists( 'translate' ) )
	{

		function translate( $key )
		{
			$translations    = app( 'translations' )['translations'];
			$currentLanguage = app( 'app' )->getLocale();
			if( array_key_exists( $currentLanguage, $translations ) )
			{
				if( array_key_exists( $key, $translations[$currentLanguage] ) )
				{
					return $translations[$currentLanguage][$key];
				}
			}

			return $currentLanguage . ':' . $key;

		}
	}