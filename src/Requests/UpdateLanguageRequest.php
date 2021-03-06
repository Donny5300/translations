<?php namespace Donny5300\Translations\Requests;

/**
 * Class StoreLanguageRequest
 *
 * @package Donny5300\Translations\Requests
 */
class UpdateLanguageRequest extends Request
{
	/**
	 * @return array
	 */
	public function rules()
	{
		return [
			'title'       => 'required|min:2',
			'short_two'   => 'required|size:2|unique:languages,short_two,' . $this->get( 'id' ),
			'short_three' => 'required|size:3',
		];
	}
}