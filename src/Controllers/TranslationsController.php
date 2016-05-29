<?php namespace Donny5300\Translations\Controllers;

use App\Http\Controllers\Controller;
use Donny5300\Translations\TranslationBuilder;

/**
 * Class TranslationsController
 *
 * @package Donny5300\Translations\Controllers
 */
class TranslationsController extends Controller
{
	public function __construct()
	{
		$this->groups = ( new TranslationBuilder() )
			->build()
			->getTranslations();

	}

	/**
	 *
	 */
	public function translations()
	{
		return $this->groups;
	}

}