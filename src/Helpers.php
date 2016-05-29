<?php


	use Donny5300\Translations\TranslationBuilder;

	if( !function_exists( 'translate' ) )
	{

		/**
		 * @param $key
		 * @return mixed|string
		 * @throws \Illuminate\Contracts\Container\BindingResolutionException
		 */
		function translate( $key )
		{
			$arguments       = func_get_args();
			$translations    = app( TranslationBuilder::class )
				->build()
				->getTranslations();
			$currentLanguage = app( 'app' )->getLocale();

			if( array_key_exists( $currentLanguage, $translations ) )
			{
				if( array_key_exists( $key, $translations[$currentLanguage] ) )
				{
					$translation = $translations[$currentLanguage][$key];
					foreach( $arguments as $key => $value )
					{
						if( $key !== 0 )
						{
							$translation = str_replace( ( '$' . $key ), $value, $translation );
						}
					}

					return $translation;
				}
			}

			return $currentLanguage . ':' . $key;

		}
	}