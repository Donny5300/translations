<?php namespace Donny5300\Translations\Controllers;

use Donny5300\Translations\Repositories\LanguageRepo;
use Donny5300\Translations\Repositories\ValueRepo;
use Donny5300\Translations\Requests\StoreLanguageRequest;
use Donny5300\Translations\Requests\UpdateLanguageRequest;

/**
 * Class LanguagesController
 *
 * @package Donny5300\Translations\Controllers
 */
class LanguagesController extends BaseController
{

	/**
	 * @var string
	 */
	protected $viewPath = 'languages';

	/**
	 * LanguagesController constructor.
	 *
	 * @param LanguageRepo $languageRepo
	 * @param ValueRepo    $valueRepo
	 */
	public function __construct( LanguageRepo $languageRepo, ValueRepo $valueRepo )
	{
		parent::__construct();

		$this->languageRepo = $languageRepo;
		$this->valueRepo    = $valueRepo;
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$languages = $this->languageRepo->all();

		return $this->output( 'index', compact( 'languages' ) );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		return $this->output( 'create', compact( 'languages' ) );
	}

	/**
	 * @param StoreLanguageRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( StoreLanguageRequest $request )
	{
		$lang = $this->languageRepo->storeOrUpdate( $request );
		$old  = $this->languageRepo->first();

		$this->valueRepo->copyToLanguage( $old->id, $lang->id );

		return $this->backWithFailed();
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit( $id )
	{
		$language = $this->languageRepo->getItem( $id );

		return $this->output( 'create', compact( 'language' ) );
	}

	/**
	 * @param UpdateLanguageRequest $request
	 * @param                       $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( UpdateLanguageRequest $request, $id )
	{
		$this->languageRepo->storeOrUpdate( $request, $id );

		return $this->backToIndex();
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy( $id )
	{
		$this->languageRepo->deleteById( $id );

		return $this->backToIndex();
	}
}