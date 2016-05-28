<?php namespace Donny5300\Translations\Models;

class Language extends BaseModel
{
	protected $table = 'languages';

	/**
	 *
	 */
	public static function boot()
	{
		parent::boot();

		self::deleting( function ( $item )
		{
			$item->load( [ 'values' => function ( $q )
			{
				$q->delete();
			}
			] );
		} );


	}

	public function storeOrUpdate( $request, $id = null )
	{
		$item                    = $this->withTrashed()->firstOrNew( [ 'id' => $id ] );
		$item->title             = $request->title;
		$item->title_short_two   = $request->title_short_two;
		$item->title_short_three = $request->title_short_three;

		if( $item->save() )
		{
			return $item;
		}

		return false;
	}

	public function values()
	{
		return $this->hasMany( Value::class );
	}

	public function names()
	{
		return $this->hasMany( Name::class );
	}

}
