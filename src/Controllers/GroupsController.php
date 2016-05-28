<?php namespace Donny5300\Translations\Controllers;

use Donny5300\Translations\Models\Group;
use Donny5300\Translations\Requests\StoreGroupRequest;
use Donny5300\Translations\Requests\UpdateGroupRequest;
use Donny5300\Translations\TranslationBuilder;

class GroupsController extends BaseController
{

	protected $viewPath = 'groups';

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

	public function index()
	{
		$groups = $this->groups;

		return $this->output( 'index', compact( 'groups' ) );
	}

	public function create()
	{
		$groups = $this->groups;

		return $this->output( 'create', compact( 'groups' ) );
	}

	public function store( StoreGroupRequest $request )
	{
		if( $this->dataModel->storeOrUpdate( $request ) )
		{
			return $this->backToIndex();
		}

		return $this->backWithFailed();
	}

	public function edit( $id )
	{
		$groups = $this->dataModel->lists( 'title', 'id' );
		$item   = $this->dataModel->findOrFail( $id );

		return $this->output( 'create', compact( 'groups', 'item' ) );
	}

	public function update( UpdateGroupRequest $request, $id )
	{
		if( $this->dataModel->storeOrUpdate( $request, $id ) )
		{
			return $this->backToIndex();
		}

		return $this->backWithFailed();
	}


}