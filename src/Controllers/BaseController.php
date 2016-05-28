<?php namespace Donny5300\Translations\Controllers;

use App\Http\Controllers\Controller;
use Donny5300\ModulairRouter\FileRemover;
use Donny5300\ModulairRouter\Models;
use Illuminate\Routing\Route;
use League\Flysystem\Filesystem;

/**
 * Class AppControllersController
 *
 * @package Donny5300\Routing\Controllers
 */
class BaseController extends Controller
{
	/**
	 * @var string
	 */
	protected $baseLink = 'donny5300.translations::';
	/**
	 * @var string
	 */
	protected $baseRoute = 'donny5300.';

	/**
	 * @var
	 */
	protected $viewPath;

	/**
	 * @var
	 */
	protected $dataModel;

	/**
	 * BaseController constructor.
	 */
	public function __construct()
	{
		$this->config = config( 'translations' );
		$this->route  = app( Route::class );
	}

	/**
	 * @param bool|false $view
	 * @param array      $data
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function output( $view = false, $data = [ ] )
	{
		$config         = $this->config;
		$data['action'] = $this->getAction();

		$data = array_merge( $config, $data );

		$data['view'] = view( $this->buildPath( $view ), $data );

		return view( 'donny5300.translations::master', $data );
	}

	/**
	 * @param $view
	 * @return string
	 */
	public function buildPath( $view )
	{
		if( array_key_exists( $this->viewPath, $this->config['system_views'] ) && $config = $this->config['system_views'][$this->viewPath] )
		{
			return $config . '.' . $view;
		}

		return $this->baseLink . $this->viewPath . '.' . $view;
	}

	/**
	 * @return string
	 */
	public function getBaseLink()
	{
		return $this->baseLink;
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function backWithFailed()
	{
		return redirect()->back()->with( 'message', 'Could not create item' );
	}

	/**
	 * @param string $message
	 * @param bool   $route
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function backToIndex( $message = 'Item saved!', $route = false )
	{
		$message = is_null( $message ) ? 'Item saved' : 'Item deleted';

		return redirect()->route( $this->getBackLink( $route ) )->with( 'message', $message );
	}

	/**
	 * @param $route
	 * @return string
	 */
	public function getBackLink( $route )
	{
		if( $route )
		{
			return $route;
		}

		$view = $this->viewPath;
		if( array_key_exists( $this->viewPath, $this->config['system_views'] ) )
		{
			$view = $this->config['system_views'][$this->viewPath];
		}

		return $this->baseRoute . 'translations.' . $view . '.index';

	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		$route  = explode( '@', $this->route->getActionName() );
		$action = last( $route );

		if( $action == 'edit' )
		{
			return 'update';
		}
		elseif( $action == 'create' )
		{
			return 'store';
		}

		return 'index';

	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy( $id )
	{
		if( $this->dataModel->find( $id )->delete() )
		{
			return $this->backToIndex( 'Item is deleted' );
		}

		return $this->backWithFailed();

	}

	public function back($message = '')
	{
		return redirect()->back()->with( 'message', $message );
	}
}