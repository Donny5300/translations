<?php namespace Donny5300\Translations\Repositories;

use Donny5300\Translations\Models\Name;
use Donny5300\Translations\Models\Value;
use Illuminate\Http\Request;

/**
 * Class ValueRepo
 *
 * @package Donny5300\Translations\Repositories
 */
class ValueRepo extends BaseRepository
{
	/**
	 * @param $values
	 */
	public function updateAll( $values )
	{
		foreach( $values as $valueId => $value )
		{
			$item        = $this->model->find( $valueId );
			$item->title = $value;
			$item->save();
		}

	}

	/**
	 * @param Request $request
	 * @param         $nameId
	 * @param null    $id
	 */
	public function storeOrUpdate( Request $request, $nameId, $id = null )
	{
		foreach( array_filter( $request->get( 'translation_values', [ ] ) ) as $languageId => $value )
		{
			$item        = $this->model->firstOrNew( [ $this->getIdField() => $id, 'language_id' => $languageId, 'name_id' => $nameId ] );
			$item->title = $value;
			$item->save();
		}
	}

	/**
	 * @param $oldLanugage
	 * @param $newLanguage
	 */
	public function copyToLanguage( $oldLanugage, $newLanguage )
	{
		$items = $this->model->whereLanguageId( $oldLanugage )->get( [ 'language_id', 'name_id' ] );

		$items->each( function ( $item ) use ( $newLanguage )
		{
			$item->language_id = $newLanguage;
		} );

		$this->model->insert( $items->toArray() );

	}

	/**
	 * @return mixed
	 */
	function model()
	{
		return Value::class;
	}
}