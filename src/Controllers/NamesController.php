<?php namespace Donny5300\Translations\Controllers;

use Donny5300\Translations\Repositories\GroupRepo;
use Donny5300\Translations\Repositories\LanguageRepo;
use Donny5300\Translations\Repositories\NameRepo;
use Donny5300\Translations\Repositories\ValueRepo;
use Donny5300\Translations\Requests\StoreNamesRequest;

/**
 * Class NamesController
 *
 * @package Donny5300\Translations\Controllers
 */
class NamesController extends BaseController
{

	/**
	 * @var string
	 */
	protected $viewPath = 'names';


	public function __construct( NameRepo $nameRepo, GroupRepo $groupRepo, LanguageRepo $languageRepo, ValueRepo $valueRepo )
	{
		parent::__construct();

		$this->groupRepo    = $groupRepo;
		$this->nameRepo     = $nameRepo;
		$this->languageRepo = $languageRepo;
		$this->valueRepo    = $valueRepo;
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$names = app( 'translations' )
			->setUcFirst( false )
			->setDelimeter( '.' )
			->getNamesList();

		$groups = $this->builder->getGroupList();

		return $this->output( 'index', compact( 'groups', 'names' ) );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$groups    = app( 'translations' )->getGroupList();
		$languages = $this->languageRepo->all();

		return $this->output( 'create', compact( 'groups', 'languages' ) );
	}

	/**
	 * @param StoreNamesRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( StoreNamesRequest $request )
	{
		$nameId = $this->nameRepo->storeOrUpdate( $request );
		$this->valueRepo->storeOrUpdate( $request, $nameId );

		return $this->backToIndex();
	}
}