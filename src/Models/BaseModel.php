<?php namespace Donny5300\Translations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

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
	private static $methods = [ 'created', 'updated', 'deleting' ];
	/**
	 * @var array
	 */
	protected $fillable = [ 'id', 'uuid' ];

	protected $appends = [ 'id_field' ];

	public function getIdFieldAttribute()
	{
		$type = config( 'translations.database.id_field' );
		if( array_key_exists( $type, $this->attributes ) )
		{
			return $this->attributes['id_field'] = $this->attributes[$type];
		}
	}

	/**
	 *
	 */
	public function setUuidAttribute()
	{
		$this->attributes['uuid'] = generate_uuid();
	}

	/**
	 * @param $timestamps
	 */
	public function setTimestamps( $timestamps )
	{
		$this->timestamps = $timestamps;
	}

	/**
	 *
	 */
	public static function boot()
	{
		parent::boot();

		$config = config( 'translations.cache' );

		if( $config['auto_clear'] )
		{
			foreach( self::$methods as $method )
			{
				self::$method( function () use ( $config )
				{
					cache()->forget( $config['key'] );
				} );
			}
		}

	}
}