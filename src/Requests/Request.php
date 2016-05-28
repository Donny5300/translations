<?php namespace Donny5300\Translations\Requests;


use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{

	public function __construct()
	{

		parent::__construct();

		validator()->extend( 'unique_array', function ( $attributes, $values )
		{
			$array = [ ];

			foreach( $values as $key => $value )
			{
				if( in_array( $value, $array ) )
				{
					return false;
				}

				$array[] = $value;
			}

			return true;
		} );
	}

	public function authorize()
	{
		return true;
	}


}