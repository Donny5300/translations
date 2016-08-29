<?php namespace Donny5300\Translations\Repositories;

use Donny5300\Translations\Models\Name;
use Illuminate\Http\Request;

/**
 * Class NameRepo
 *
 * @package Donny5300\Translations\Repositories
 */
class NameRepo extends BaseRepository
{

	/**
	 * @return mixed
	 */
	public function getAll()
	{
		return $this->model->all();
	}

	/**
	 * @param array $names
	 */
	public function updateAll( array $names )
	{
		foreach( $names as $nameId => $name )
		{
			$item        = $this->model->find( $nameId );
			$item->title = $name;
			$item->save();
		}
	}

	/**
	 * @param Request $request
	 * @param null    $id
	 *
	 * @return mixed
	 */
	public function storeOrUpdate( $id = null, $title, $groupId )
	{
		$item = $this->model->firstOrNew( [ $this->getIdField() => $id ] );

		$item->title    = $title;
		$item->group_id = $groupId;

		$item->save();

		return $item->id;
	}

	/**
	 * @return mixed
	 */
	function model()
	{
		return Name::class;
	}
}