<?php namespace Donny5300\Translations;

use Donny5300\ModulairRouter\Middleware\DevelopmentMode;
use Donny5300\Translations\Events\TranslationsUpdated;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router as IlluminateRouter;
use Donny5300\ModulairRouter\Middleware\CheckMissingRoute;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * @var
	 */
	private $config;

	/**
	 * @return string
	 */
	public function getMigrationsPath()
	{
		return $this->app->databasePath() . '/migrations';
	}

	/**
	 * Get the active router.
	 *
	 * @return Router
	 */
	protected function getRouter()
	{
		return $this->app['router'];
	}

	/**
	 * Get the config path
	 *
	 * @return string
	 */
	protected function getConfigPath()
	{
		return config_path( 'translations.php' );
	}

	/**
	 * @return string
	 */
	protected function getAssetsPath()
	{
		return public_path( 'assets' );
	}

	/**
	 * Publish the config file
	 *
	 * @param  string $configPath
	 */
	protected function publishConfig( $configPath )
	{
		$this->publishes( [ $configPath => config_path( 'translations.php' ) ], 'config' );
	}

	/**
	 * @param $middleware
	 */
	protected function registerMiddleware( $middleware )
	{
		$kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
		$kernel->pushMiddleware( $middleware );
	}

	/**
	 *
	 */
	public function boot()
	{
		$this->config = config( 'translations' );

		$assetsPath = __DIR__ . '/../assets';
		$migration  = __DIR__ . '/../migrations';
		$configPath = __DIR__ . '/../config/translations.php';

		$this->publishes( [ $configPath => $this->getConfigPath() ], 'translations-config' );
		$this->publishes( [ $assetsPath => $this->getAssetsPath() ], 'translations-assets' );
		$this->publishes( [ $migration => $this->getMigrationsPath() ], 'translations-migration' );

		$this->loadViewsFrom( __DIR__ . '/Resources/Views/', 'donny5300.translations' );

		app()->singleton( config('translations.helpers.singleton'), function ()
		{
			return new Builder;
		} );

		/**
		 * If twig is defined, then load functions
		 */
		if( app()->offsetExists( 'twig' ) )
		{
			new TwigExtensions( app( 'twig' ), $this->config );
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$configPath = __DIR__ . '/../config/translations.php';
		$this->mergeConfigFrom( $configPath, 'translations' );

		include __DIR__ . '/helpers.php';
		include __DIR__ . '/routes.php';
	}
}
