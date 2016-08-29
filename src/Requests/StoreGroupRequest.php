<?php namespace Donny5300\Translations\Requests;

use Donny5300\Translations\Repositories\GroupRepo;

class StoreGroupRequest extends Request
{
	public function __construct( GroupRepo $groupRepo )
	{
		parent::__construct();

		validator()->extend( 'max_depth', function ( $name, $groupId ) use ( $groupRepo )
		{
//			$groupRepo->getCurrentDepth($groupId);
//			dd( __LINE__ . ':[' . __FILE__ . ']', $item1, $item2, $item3 );

		} );
	}

	public function rules()
	{
		return [
			'title'    => 'required|min:2',
//			'group_id' => 'max_depth'
		];
	}
}