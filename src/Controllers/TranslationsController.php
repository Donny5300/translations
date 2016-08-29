<?php namespace Donny5300\Translations\Controllers;

use App\Http\Controllers\Controller;

/**
 * Class TranslationsController
 *
 * @package Donny5300\Translations\Controllers
 */
class TranslationsController extends Controller
{
	/**
	 * @return array
	 */
	public function translations()
	{
		return translator()->getTranslations();
	}

}