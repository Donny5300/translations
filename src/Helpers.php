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
			return app( 'translations' );
		}

	}


