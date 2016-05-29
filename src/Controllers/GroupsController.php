<?php namespace Donny5300\Translations\Controllers;

use Donny5300\Translations\Models\Group;
use Donny5300\Translations\Requests\StoreGroupRequest;
use Donny5300\Translations\Requests\UpdateGroupRequest;
use Donny5300\Translations\TranslationBuilder;

/**
 * Class GroupsController
 *
 * @package Donny5300\Translations\Controllers
 */
class GroupsController extends BaseController
{

	/**
	 * @var string
	 */
	protected $viewPath = 'groups';

	/**
	 * GroupsController constructor.
	 *
	 * @param Group $group
	 */
	public function __construct( Group $group )
	{
		parent::__construct();

		$this->dataModel = $group;
		$this->groups    = ( new TranslationBuilder() )
			->setDelimeter( ' > ' )
			->setUcfirst()
			->build()
			->render();
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$groups = $this->groups;

		return $this->output( 'index', compact( 'groups' ) );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$groups = $this->groups;

		return $this->output( 'create', compact( 'groups' ) );
	}

	/**
	 * @param StoreGroupRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( StoreGroupRequest $request )
	{
//		dd('File: ' . __FILE__, 'Line: '. __LINE__ , $request->all());
		
		if( $this->dataModel->storeOrUpdate( $request ) )
		{
			return $this->backToIndex();
		}

		return $this->backWithFailed();
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit( $id )
	{
		$groups = $this->dataModel->lists( 'title', 'id' );
		$item   = $this->dataModel->findOrFail( $id );

		return $this->output( 'create', compact( 'groups', 'item' ) );
	}

	/**
	 * @param UpdateGroupRequest $request
	 * @param                    $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( UpdateGroupRequest $request, $id )
	{
		if( $this->dataModel->storeOrUpdate( $request, $id ) )
		{
			return $this->backToIndex();
		}

		return $this->backWithFailed();
	}


}