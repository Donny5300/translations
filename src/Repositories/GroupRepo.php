<?php namespace Donny5300\Translations\Repositories;

use Donny5300\Translations\Models\Group;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

/**
 * Class LanguageRepo
 *
 * @package Donny5300\Translations\Repositories
 */
class GroupRepo extends BaseRepository
{
	/**
	 * LanguageRepo constructor.
	 *
	 * @param Application $app
	 */
	public function __construct( Application $app )
	{
		parent::__construct( $app );
	}

	/**
	 * @param string $value
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function getAllList( $value = 'title', $key = 'id' )
	{
		return $this->model->pluck( $value, $key );
	}

	/**
	 * @return mixed
	 */
	public function getGroupTree()
	{
		return $this->model
			->whereNull( 'group_id' )
			->with( $this->getGroupDepth() )
			->get()
			->toArray();
	}

	public function getCurrentDepth( $groupId )
	{
		$relations = app('translations');
		$record = $this->model->whereGroupId( $groupId )->with()->first();

		dd( __LINE__ . ':[' . __FILE__ . ']' , $record);


	}

	/**
	 * @return string
	 */
	private function getGroupDepth()
	{
		$groups = '';
		for( $i = 0; $i <= $this->config( 'depth' ); $i++ )
		{
			$groups .= 'groups.';
		}

		return substr( $groups, 0, -1 );
	}

	/**
	 * @param Request $request
	 * @param null    $id
	 *
	 * @return mixed
	 */
	public function storeOrUpdate( Request $request, $id = null )
	{
		$item = $this->model->firstOrNew( [ $this->idField => $id ] );

		$item->title    = $request->get( 'title' );
		$item->group_id = empty( $request->group_id ) ? null : $request->group_id;

		return $item->save();
	}

	/**
	 * @return mixed
	 */
	function model()
	{
		return Group::class;
	}
}