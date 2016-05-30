<?php namespace Donny5300\Translations\Controllers;

use Donny5300\Translations\Models\Group;
use Donny5300\Translations\Models\Language;
use Donny5300\Translations\Models\Name;
use Donny5300\Translations\Models\Value;
use Illuminate\Http\Request;

class MissingTranslationsController extends BaseController
{

	protected $viewPath = 'missing_translations';

	public function __construct( Language $lang, Name $name, Group $group, Value $value )
	{
		parent::__construct();
		$this->lang  = $lang;
		$this->name  = $name;
		$this->group = $group;
		$this->value = $value;

	}

	public function index()
	{
//		session()->forget('missing_translations');
		$translations = array_unique( session()->get( 'missing_translations', [ ] ) );

		return $this->output( 'index', compact( 'translations' ) );
	}

	public function store( Request $request )
	{
		$items = $request->get( 'keys', [ ] );
		foreach( $items as $value )
		{
			$groupId = null;

			$chunks = explode( '.', $value );

			if( ( $amount = count( $chunks ) ) > 1 )
			{
				foreach( $chunks as $chunkIndex => $chunk )
				{

					if( $chunkIndex == ( $amount - 1 ) )
					{
						$name      = $this->name->withTrashed()->firstOrCreate( [ 'group_id' => $groupId, 'title' => $chunk ] );
						$languages = $this->lang->withTrashed()->get();

						foreach( $languages as $key => $language )
						{
							$this->value->withTrashed()->firstOrCreate( [ 'name_id' => $name->id, 'language_id' => $language->id ] );
						}
					}
					else
					{
						$groupId = $this->group->withTrashed()->firstOrCreate( [ 'title' => $chunk, 'group_id' => $groupId ] )->id;
					}
				}
			}
		}

		session()->forget( 'missing_translations' );

		return redirect()->to('/system/translations/groups');
	}

}