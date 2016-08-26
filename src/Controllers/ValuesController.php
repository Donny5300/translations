<?php namespace Donny5300\Translations\Controllers;

use Donny5300\Translations\Repositories\ValueRepo;

/**
 * Class ValuesController
 *
 * @package Donny5300\Translations\Controllers
 */
class ValuesController extends BaseController
{
	/**
	 * @var string
	 */
	protected $viewPath = 'names';

	/**
	 * ValuesController constructor.
	 *
	 * @param ValueRepo $valueRepo
	 */
	public function __construct( ValueRepo $valueRepo )
	{
		parent::__construct();

		$this->valueRepo = $valueRepo;
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy( $id )
	{
		$this->valueRepo->deleteById( $id );

		return $this->backToIndex();
	}
}