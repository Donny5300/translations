<?php namespace Donny5300\Translations\Controllers;

use App\Http\Controllers\Controller;
use Donny5300\ModulairRouter\FileRemover;
use Donny5300\ModulairRouter\Models;
use Illuminate\Routing\Route;
use League\Flysystem\Filesystem;

/**
 * Class AppControllersController
 *
 * @property array data
 * @package Donny5300\Routing\Controllers
 */
class BaseController extends Controller
{
	/**
	 * @var bool
	 */
	protected $packageView = true;
	/**
	 * @var string
	 */
	protected $baseLink = 'donny5300.translations::';

	/**
	 * @var string
	 */
	protected $master = 'donny5300.translations::master';

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
	 * @return string
	 */
	public function getBaseLink()
	{
		return $this->baseLink;
	}

	/**
	 * @param $route
	 *
	 * @return string
	 */
	public function getBackLink( $route )
	{
		return $route ? $route : 'translations.' . $this->viewPath . '.index';
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
	 * @param $view
	 *
	 * @return string
	 */
	public function buildPath( $view )
	{
		return $this->baseLink . $this->viewPath . '.' . $view;
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
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function backToIndex( $message = 'Item saved!', $route = false )
	{
		$message = is_null( $message ) ? 'Item saved' : 'Item deleted';

		return redirect()->route( $this->getBackLink( $route ) )->with( 'message', $message );
	}

	/**
	 * @param string $message
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function back( $message = '' )
	{
		return redirect()->back()->with( 'message', $message );
	}

	/**
	 * @param bool|false $view
	 * @param array      $data
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function output( $view = false, $data = [ ] )
	{
		$config         = $this->config;
		$data['action'] = $this->getAction();

		$this->data              = array_merge( $config, $data );
		$this->data['view_path'] = $this->buildPath( $view );

		return $this->render();
	}

	/**
	 * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function render()
	{
		if( !$this->packageView )
		{
			return $this->data;
		}

		return view( $this->master, $this->data );
	}
}