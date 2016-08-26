<?php namespace Donny5300\Translations\Controllers;

use Donny5300\Translations\Repositories\GroupRepo;
use Donny5300\Translations\Repositories\LanguageRepo;
use Donny5300\Translations\Repositories\NameRepo;
use Donny5300\Translations\Repositories\ValueRepo;
use Donny5300\Translations\Requests\StoreGroupRequest;
use Donny5300\Translations\Requests\UpdateGroupRequest;

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
	 * @param GroupRepo    $groupRepo
	 * @param LanguageRepo $languageRepo
	 * @param NameRepo     $nameRepo
	 * @param ValueRepo    $valueRepo
	 */
	public function __construct( GroupRepo $groupRepo, LanguageRepo $languageRepo, NameRepo $nameRepo, ValueRepo $valueRepo )
	{
		parent::__construct();
		$this->groupRepo    = $groupRepo;
		$this->languageRepo = $languageRepo;
		$this->valueRepo    = $valueRepo;
		$this->nameRepo     = $nameRepo;
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$groups   = app( 'translations' )->getGroupList();
		$progress = app( 'translations' )->getTranslationProgress();

		return $this->output( 'index', compact( 'groups', 'progress' ) );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$groups = app( 'translations' )->setDelimeter(' > ')->setUcFirst()->getGroupList();

		return $this->output( 'create', compact( 'groups' ) );
	}

	/**
	 * @param StoreGroupRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( StoreGroupRequest $request )
	{
		$this->groupRepo->storeOrUpdate( $request );

		return $this->backToIndex();
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit( $id )
	{
		$groups = app('translations')->getGroupList();
		$group  = $this->groupRepo->getItem( $id )
			->load( 'names.values.language' );

		$names = $group->names;

		return $this->output( 'create', compact( 'groups', 'group', 'names' ) );
	}

	/**
	 * @param UpdateGroupRequest $request
	 * @param                    $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( UpdateGroupRequest $request, $id )
	{
		$this->groupRepo->storeOrUpdate( $request, $id );
		$this->nameRepo->updateAll( $request->get( 'names', [ ] ) );
		$this->valueRepo->updateAll( $request->get( 'values', [ ] ) );

		return $this->backToIndex();
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy( $id )
	{
		$this->groupRepo->deleteById( $id );

		return $this->backToIndex();
	}

}