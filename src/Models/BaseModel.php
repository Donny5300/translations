<?php namespace Donny5300\Translations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BaseModel
 *
 * @package Donny5300\Translations\Models
 */
class BaseModel extends Model
{
	use SoftDeletes;
	/**
	 * @var array
	 */
	protected $fillable = [ 'id', 'title', 'name_id', 'language_id' ,'group_id'];

	/**
	 * @var array
	 */
	private static $methods = [ 'created', 'updated', 'deleting' ];


	/**
	 *
	 */
	public static function boot()
	{
		parent::boot();

		foreach( self::$methods as $method )
		{
			self::$method( function ( $item ) use ( $method )
			{
				app( 'cache' )->forget( 'translation_groups' );
				app( 'cache' )->forget( 'translations' );
			} );
		}
	}
}