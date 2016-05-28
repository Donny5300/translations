<?php namespace Donny5300\Translations\Models;

/**
 * Class Value
 *
 * @package Donny5300\Translations\Models
 */
class Value extends BaseModel
{
	/**
	 * @var string
	 */
	protected $table = 'values';

	/**
	 * @param $nameId
	 * @param $value
	 * @param $languageId
	 * @return bool
	 */
	public function storeOrUpdate( $nameId, $value, $languageId )
	{
		$item = $this->firstOrNew( [ 'name_id' => $nameId, 'language_id' => $languageId ] );

		$item->title       = $value;
		$item->name_id     = $nameId;
		$item->language_id = $languageId;

		if( $item->save() )
		{
			return $item;
		}

		return false;
	}

	/**
	 * @param $languageId
	 * @param $names
	 */
	public function storeAll( $languageId, $names )
	{
		$names->each( function ( $value ) use ( $languageId )
		{
			$value->title       = 'Leeg';
			$value->name_id     = $value->id;
			$value->language_id = $languageId;
			unset( $value->id );
		} );

		self::insert( $names->toArray() );
	}

	public function name()
	{
		return $this->belongsTo( Name::class );
	}
}
