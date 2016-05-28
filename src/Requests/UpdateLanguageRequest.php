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
			'title'             => 'required|min:2',
			'title_short_two'   => 'required|size:2',
			'title_short_three' => 'required|size:3',
		];
	}
}