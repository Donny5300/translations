<?php namespace Donny5300\Translations\Requests;


class StoreNamesRequest extends Request
{
	public function rules()
	{
		return [
			'title' => 'required|unique:names,title,null,title,group_id,' . $this->get( 'group_id' )
		];
	}
}