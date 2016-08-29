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

		/** @var Donny5300\Translations\Builder $translator */
		$translator = app( 'translations' );
		return $translator;
	}


