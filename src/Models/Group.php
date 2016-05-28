<?php namespace Donny5300\Translations\Models;

/**
 * Class Group
 *
 * @package Donny5300\Translations\Models
 */
class Group extends BaseModel
{
	/**
	 * @var string
	 */
	protected $table = 'groups';

	/**
	 *
	 */
	public static function boot()
	{
		parent::boot();

		self::deleting( function ( $item )
		{
			$item->load( [ 'groups' => function ( $q )
			{
				$q->delete();
			}, 'groups.names'       => function ( $q )
			{
				$q->delete();
			}
			] );
		} );
	}

	/**
	 * @param      $request
	 * @param null $id
	 * @return bool|\Illuminate\Database\Eloquent\Model
	 */
	public function storeOrUpdate( $request, $id = null )
	{
		$item = $this->withTrashed()->firstOrNew( [ 'id' => $id ] );

		$item->title       = $request->title;
		$item->group_id    = $request->group_id == 0 ? null : $request->group_id;
		$item->description = $request->description;

		if( $item->save() )
		{
			return $item;
		}

		return false;
	}

	/**
	 * @return mixed
	 */
	public function groups()
	{
		return $this->hasMany( self::class )
			->select( [ 'id', 'title', 'group_id' ] )
			->with( [ 'names' => function ( $q )
			{
				$q->select( [ 'id', 'title', 'group_id' ] );
			}
			] );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function names()
	{
		return $this->hasMany( Name::class, 'group_id' );
	}

}
