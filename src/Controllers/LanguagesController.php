<?php namespace Donny5300\Translations\Controllers;


use Donny5300\Translations\Models\Language;
use Donny5300\Translations\Models\Name;
use Donny5300\Translations\Models\Value;
use Donny5300\Translations\Requests\StoreLanguageRequest;
use Donny5300\Translations\Requests\UpdateLanguageRequest;
use Illuminate\Http\Request;

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
	 * @param Language $language
	 * @param Name     $name
	 * @param Value    $value
	 */
	public function __construct( Language $language, Name $name, Value $value )
	{
		parent::__construct();

		$this->dataModel  = $language;
		$this->nameModel  = $name;
		$this->valueModel = $value;
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$languages = $this->dataModel->all();

		return $this->output( 'index', compact( 'languages' ) );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$languages = $this->dataModel->lists( 'title', 'id' );

		return $this->output( 'create', compact( 'languages' ) );
	}

	/**
	 * @param StoreLanguageRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( StoreLanguageRequest $request )
	{
		if( $language = $this->dataModel->storeOrUpdate( $request ) )
		{
			$names = $this->nameModel->withTrashed()->get( [ 'id' ] );

			$this->valueModel->storeAll( $language->id, $names );

			return $this->backToIndex( 'Language stored!' );
		}

		return $this->backWithFailed();
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit( $id )
	{
		$languages = $this->dataModel->lists( 'title', 'id' );
		$item      = $this->dataModel->findOrFail( $id );

		return $this->output( 'create', compact( 'languages', 'item' ) );
	}

	/**
	 * @param UpdateLanguageRequest $request
	 * @param                       $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( UpdateLanguageRequest $request, $id )
	{
		if( $this->dataModel->storeOrUpdate( $request, $id ) )
		{
			return $this->backToIndex();
		}

		return $this->backWithFailed();
	}
}