<?php namespace Donny5300\Translations\Requests;

class StoreGroupRequest extends Request
{
	public function rules()
	{
		return [
			'title'    => 'required|min:2',
			'group_id' => 'numeric'
		];
	}
}