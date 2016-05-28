<?php namespace Donny5300\Translations\Models;

	/**
	 * Class Name
	 *
	 * @package Donny5300\Translations\Models
	 */
/**
 * Class Name
 *
 * @package Donny5300\Translations\Models
 */
class Name extends BaseModel
{
	/**
	 * @var string
	 * @todo rename to 'names'
	 */
	protected $table = 'names';

	/**
	 * @param      $request
	 * @param null $id
	 * @param null $group_id
	 * @return bool
	 */
	public function storeOrUpdate( $request, $id = null, $group_id = null )
	{
		$item = $this->withTrashed()->firstOrNew( [ 'id' => $id ] );

		$item->title    = $request->title;
		$item->group_id = $request->get( 'group_id', $group_id );

		if( $item->save() )
		{
			return $item;
		}

		return false;
	}

	/**
	 * @param      $id
	 * @param      $title
	 * @param bool $group_id
	 * @return bool
	 */
	public function updateItem( $id, $title, $group_id = false )
	{
		$item        = $this->find( $id );
		$item->title = $title;
		if( $group_id )
		{
			$item->group_id = $group_id;
		}

		if( $item->save() )
		{
			return $item;
		}

		return false;
	}

	/**
	 * @param $item
	 * @return mixed
	 */
	public function setTitleAttribute( $item )
	{
		return $this->attributes['title'] = str_replace( '-', '_', strtolower( $item ) );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function group()
	{
		return $this->hasOne( Group::class );
	}

	public function values()
	{
		return $this->hasMany( Value::class );
	}
}
