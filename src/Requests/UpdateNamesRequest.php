<?php namespace Donny5300\Translations\Requests;


class UpdateNamesRequest extends Request
{

	public function rules()
	{
		return [
			'title' => 'unique_array'
		];

	}

	public function all()
	{
		return parent::all();
	}
}