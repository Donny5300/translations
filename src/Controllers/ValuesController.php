<?php namespace Donny5300\Translations\Controllers;


use Donny5300\Translations\Models\Group;
use Donny5300\Translations\Models\Name;
use Donny5300\Translations\Models\Value;
use Donny5300\Translations\Requests\StoreGroupRequest;
use Donny5300\Translations\Requests\UpdateGroupRequest;
use Donny5300\Translations\TranslationBuilder;

class ValuesController extends BaseController
{

	protected $viewPath = 'names';

	public function __construct( Value $value, Name $name, Group $group )
	{
		parent::__construct();

		$this->dataModel  = $value;
		$this->nameModel  = $name;
		$this->groupModel = $group;
	}

	public function index()
	{
		$groups = $this->groupModel->all();

		return $this->output( 'index', compact( 'groups' ) );
	}

	public function create()
	{
		$names = $this->nameModel->all();

		return $this->output( 'create', compact( 'names' ) );
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