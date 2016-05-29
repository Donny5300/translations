<?php namespace Donny5300\Translations\Controllers;


use Donny5300\Translations\Models\Group;
use Donny5300\Translations\Models\Language;
use Donny5300\Translations\Models\Name;
use Donny5300\Translations\Models\Value;
use Donny5300\Translations\Requests\StoreGroupRequest;
use Donny5300\Translations\Requests\StoreNamesRequest;
use Donny5300\Translations\Requests\UpdateGroupRequest;
use Donny5300\Translations\Requests\UpdateNamesRequest;
use Donny5300\Translations\TranslationBuilder;

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

	/**
	 * NamesController constructor.
	 *
	 * @param Name     $name
	 * @param Group    $group
	 * @param Language $language
	 * @param Value    $value
	 */
	public function __construct( Name $name, Group $group, Language $language, Value $value )
	{
		parent::__construct();

		$this->dataModel     = $name;
		$this->groupModel    = $group;
		$this->languageModel = $language;
		$this->valueModel    = $value;
		$this->groups        = ( new TranslationBuilder() )
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
		$groups = $this->dataModel->whereNull( 'group_id' )->get();

		return $this->output( 'index', compact( 'groups' ) );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$groups    = $this->groups;
		$languages = $this->languageModel->all();

		return $this->output( 'create', compact( 'groups', 'languages' ) );
	}

	/**
	 * @param StoreNamesRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( StoreNamesRequest $request )
	{
		if( $name = $this->dataModel->storeOrUpdate( $request ) )
		{
			foreach( $request->get( 'translation_values', [ ] ) as $languageId => $value )
			{
				$this->valueModel->storeOrUpdate( $name->id, $value, $languageId );
			}

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
		$group     = $this->groupModel->with( 'names.values' )->find( $id );
		$languages = $this->languageModel->lists( 'title', 'id' )->toArray();
		$groups    = $this->groups;

		return $this->output( 'edit', compact( 'group', 'languages', 'groups' ) );
	}

	/**
	 * @param UpdateNamesRequest $request
	 * @param                    $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( UpdateNamesRequest $request, $id )
	{
		$groups = $request->get( 'group_id', [ ] );

		foreach( $request->get( 'title', [ ] ) as $key => $value )
		{
			$this->dataModel->updateItem( $key, $value, $this->getGroupId( $key, $groups ) );
		}


		foreach( $request->get( 'values', [ ] ) as $key => $language )
		{
			foreach( $language as $languageKey => $value )
			{
				$this->valueModel->storeOrUpdate( $key, $value, $languageKey );

			}
		}

		return $this->backToIndex( 'Items are saved!' );
	}

	/**
	 * @param $key
	 * @param $groups
	 * @return bool
	 */
	public function getGroupId( $key, $groups )
	{
		if( array_key_exists( $key, $groups ) )
		{
			return $groups[$key];
		}

		return false;

	}
}