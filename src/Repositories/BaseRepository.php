<?php namespace Donny5300\Translations\Repositories;

use Donny5300\Translations\Contracts\RepositoryContract;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * @property mixed model
 */
abstract class BaseRepository implements RepositoryContract
{
	/**
	 * BaseRepository constructor.
	 *
	 * @param Application $application
	 */
	public function __construct( Application $application )
	{
		$this->config  = config( 'translations' );
		$this->idField = $this->getIdField();
		$this->app     = $application;

		$this->makeModel();
		$this->model->setTimestamps( $this->config['database']['create_update'] );
	}

	/**
	 * @param       $id
	 * @param array $columns
	 *
	 * @return mixed
	 */
	public function getItem( $id, array $columns = [ '*' ] )
	{
		return $this->model
			->where( $this->getIdField(), '=', $id )
			->firstOrFail( $columns );
	}

	/**
	 * @return mixed
	 */
	protected function getIdField()
	{
		return $this->config['database']['id_field'];
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function deleteById( $id )
	{
		return $this->model->find( $id )->delete();
	}

	/**
	 * @throws Exception
	 */
	public function makeModel()
	{
		$this->model = $this->app->make( $this->model() );
		if( !$this->model instanceof Model )
		{
			throw new Exception( 'Function model must be a instance of [ Illuminate\Database\Eloquent\Model ]' );
		}
	}

	/**
	 * @param array $columns
	 *
	 * @return mixed
	 */
	public function all( array $columns = [ '*' ] )
	{
		return $this->model->all( $columns );
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	protected function config( $key )
	{
		return $this->config[$key];
	}

	/**
	 * @return mixed
	 */
	abstract function model();
}