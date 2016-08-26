<?php namespace Donny5300\Translations;

use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * Class TwigExtensions
 *
 * @package Donny5300\Translations
 */
class TwigExtensions
{
	/**
	 * TwigExtensions constructor.
	 *
	 * @param $twig
	 * @param $config
	 */
	public function __construct( $twig, $config )
	{
		$this->twig   = $twig;
		$this->config = $config;
		$this->registerTranslationFilter();
		$this->registerTranslationFunction();
	}

	/**
	 *
	 */
	private function registerTranslationFilter()
	{
		$config = $this->config['twig']['translate_filter'];

		if( is_array( $config ) )
		{
			foreach( $config as $filter )
			{
				$this->applyFilter( $filter );
			}

			return;
		}

		$this->applyFilter( $config );

	}

	/**
	 * @param $key
	 */
	protected function applyFilter( $key )
	{
		$filter = new Twig_SimpleFilter( $key, function ( $key )
		{
			return $this->callTranslateFunction( $key, func_get_args() );
		} );

		$this->twig->addFilter( $filter );
	}

	/**
	 * @param $key
	 */
	protected function applyFunction( $key )
	{
		$function = new Twig_SimpleFunction( $key, function ( $key )
		{
			return $this->callTranslateFunction( $key, func_get_args() );
		} );

		$this->twig->addFunction( $function );
	}

	/**
	 *
	 */
	private function registerTranslationFunction()
	{
		$config = $this->config['twig']['translate_function'];

		if( is_array( $config ) )
		{
			foreach( $config as $function )
			{
				$this->applyFunction( $function );
			}

			return;
		}

		$this->applyFunction( $config );
	}

	/**
	 * @param $key
	 * @param $func_get_args
	 *
	 * @return mixed
	 */
	private function callTranslateFunction( $key, $func_get_args )
	{
		return call_user_func_array(
			[ app( 'translations' ), 'translate' ],
			( [ $key ] + $func_get_args )
		);
	}
}