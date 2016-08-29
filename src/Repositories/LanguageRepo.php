<?php namespace Donny5300\Translations\Repositories;

use Donny5300\Translations\Models\Language;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

/**
 * Class LanguageRepo
 *
 * @package Donny5300\Translations\Repositories
 */
class LanguageRepo extends BaseRepository
{
	/**
	 * LanguageRepo constructor.
	 *
	 * @param Application $app
	 */
	public function __construct( Application $app )
	{
		parent::__construct( $app );
	}

	/**
	 * @return mixed
	 */
	public function getAllList()
	{
		return $this->model
			->pluck( 'short_two', 'id' )
			->toArray();
	}

	/**
	 * @param null $id
	 * @param      $title
	 * @param      $short_two
	 * @param      $short_three
	 *
	 * @return mixed
	 */
	public function storeOrUpdate( $id = null, $title, $short_two, $short_three )
	{
		$item = $this->model->firstOrNew( [ $this->idField => $id ] );

		$item->title       = $title;
		$item->short_two   = $short_two;
		$item->short_three = $short_three;

		$item->save();

		return $item;
	}

	/**
	 * @return mixed
	 */
	public function first()
	{
		return $this->model->first();
	}

	/**
	 * @return mixed
	 */
	function model()
	{
		return Language::class;
	}
}