<?php namespace Donny5300\Translations\Controllers;

use Donny5300\Translations\Models\Group;
use Donny5300\Translations\Models\Language;
use Donny5300\Translations\Models\Name;
use Donny5300\Translations\Models\Value;
use Donny5300\Translations\Requests\StoreGroupRequest;
use Donny5300\Translations\Requests\UpdateGroupRequest;
use Donny5300\Translations\TranslationBuilder;

/**
 * Class CleanUpController
 *
 * @package Donny5300\Translations\Controllers
 */
class CleanUpController extends BaseController
{
	/**
	 * @var string
	 */
	protected $viewPath = 'clean_up';

	/**
	 * CleanUpController constructor.
	 *
	 * @param Language $lang
	 * @param Name     $name
	 * @param Group    $group
	 * @param Value    $value
	 */
	public function __construct( Language $lang, Name $name, Group $group, Value $value )
	{
		parent::__construct();
		$this->lang  = $lang;
		$this->name  = $name;
		$this->group = $group;
		$this->value = $value;

	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function cleanUp()
	{
		return $this->output( 'clean' );
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete()
	{
		$this->lang->onlyTrashed()->forceDelete();
		$this->name->onlyTrashed()->forceDelete();
		$this->group->onlyTrashed()->forceDelete();
		$this->value->onlyTrashed()->forceDelete();

		app( 'cache' )->forget( 'translation_groups' );


		return $this->back( 'Items are deleted!' );
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function restore()
	{
		$this->lang->onlyTrashed()->restore();
		$this->name->onlyTrashed()->restore();
		$this->group->onlyTrashed()->restore();
		$this->value->onlyTrashed()->restore();

		app( 'cache' )->forget( 'translation_groups' );

		return $this->back( 'Items are restored!' );
	}

}