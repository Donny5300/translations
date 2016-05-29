<?php namespace Donny5300\Translations;

use Donny5300\Translations\Models\Group;
use Donny5300\Translations\Models\Language;

/**
 * Class TranslationBuilder
 *
 * @package Donny5300\Translations
 */
class TranslationBuilder
{
	/**
	 * @var array
	 */
	public $groups = [ ];
	/**
	 * @var array
	 */
	private $dottedGroups = [ ];
	/**
	 * @var string
	 */
	private $cacheKey = 'translation_groups';
	/**
	 * @var \Illuminate\Foundation\Application|mixed
	 */
	private $groupsModel;
	/**
	 * @var array
	 */
	protected $config = [ 'depth' => 3 ];
	/**
	 * @var string
	 */
	public $delimeter = '.';
	/**
	 * @var bool
	 */
	private $ucfirst = false;

	/**
	 * @var bool
	 */
	private $language = false;

	/**
	 * TranslationBuilder constructor.
	 */
	public function __construct()
	{
		$this->cache         = app( 'cache' );
		$this->groupsModel   = app( Group::class );
		$this->languageModel = app( Language::class );
	}


	/**
	 * @param        $groups
	 * @param null   $id
	 * @param string $key
	 * @return $this
	 */
	public function setDotGroups( $groups, $id = null, $key = '' )
	{
		$this->dottedGroups[$id] = rtrim( $key, $this->getDelimeter() );

		foreach( $groups as $group )
		{
			if( array_key_exists( 'groups', $group ) )
			{
				$this->setDotGroups( $group['groups'], $group['id'], $key .= $this->getUcfirstGroup( $group['title'] ) . $this->getDelimeter() );
			}

		}

		return $this;
	}

	/**
	 *
	 */
	private function setGroups()
	{
		if( !$this->cache->has( $this->cacheKey ) )
		{
			$this->groups = $this->groupsModel
				->with( $this->getDepth() )
				->whereNull( 'group_id' )
				->get( [ 'id', 'title', 'group_id' ] )
				->toArray();

			return;
		}

		$this->groups = $this->cache->get( $this->cacheKey );

	}

	/**
	 * @return string
	 */
	public function getDepth()
	{
		$key = '';
		for( $depth = 0; $depth <= $this->config['depth']; $depth++ )
		{
			$key .= 'groups.';

		}

		return rtrim( $key, '.' );
	}

	/**
	 *
	 */
	public function setCache()
	{
		$this->cache->forever( $this->cacheKey, $this->groups );
	}

	/**
	 * @param        $in
	 * @param        $out
	 * @param string $path
	 */
	public function createList( $in, &$out, $path = '' )
	{
		foreach( $in as $data )
		{
			$title            = ltrim( $path . $this->getDelimeter() . $data['title'], $this->getDelimeter() );
			$out[$data['id']] = $title;
			if( count( $data['groups'] ) > 0 )
			{
				$this->createList( $data['groups'], $out, $title );
			}
		}
	}

	/**
	 * @return $this
	 */
	public function build()
	{

		$this->setGroups();

		$this->createList( $this->groups, $this->dottedGroups );

		$this->setCache();

		return $this;
	}

	/**
	 * @return array
	 */
	public function render()
	{
		return $this->dottedGroups;
	}

	/**
	 * @return array
	 */
	public function renderTranslations()
	{

		if( $this->cache->has( 'translations' ) )
		{
			return $this->cache->get( 'translations' );
		}

		$items        = $this->loadTranslations();
		$translations = [ ];

		foreach( $items as $language )
		{
			if( !array_key_exists( strtolower( $language->title_short_two ), $translations ) )
			{
				$translations[strtolower( $language->title_short_two )] = [ ];
			}

			foreach( $language->values as $value )
			{
				$translation                                                          = $this->getFullTranslation( $value );
				$translations[strtolower( $language->title_short_two )][$translation] = $value->title;

			}
		}

		$this->cache->forever( 'translations', $translations );


		return $translations;
	}

	public function getTranslations()
	{
		$translations = $this->renderTranslations();

		if( $this->language && array_key_exists( $this->language, $translations ) )
		{
			return $translations[$this->language];
		}

		return $translations;
	}

	/**
	 * @param string $delimeter
	 * @return $this
	 */
	public function setDelimeter( $delimeter = ' > ' )
	{
		$this->delimeter = $delimeter;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDelimeter()
	{
		return $this->delimeter;
	}

	/**
	 * @return array
	 */
	public function getGroups()
	{
		return $this->dottedGroups;
	}

	/**
	 * @return $this
	 */
	public function setUcfirst()
	{
		$this->ucfirst = true;

		return $this;
	}

	/**
	 * @param $group
	 * @return string
	 */
	private function getUcfirstGroup( $group )
	{
		if( $this->ucfirst )
		{
			return ucfirst( $group );
		}

		return $group;
	}

	/**
	 * @return $this
	 */
	public function loadTranslations()
	{
		return $this->languageModel->with( 'values.name' )->get();
	}

	/**
	 * @param $translation
	 * @return string
	 */
	private function getFullTranslation( $translation )
	{
		$translationName = $translation->name->title;
		$groupId         = $translation->name->group_id;


		return $this->dottedGroups[$groupId] . $this->getDelimeter() . $translationName;
	}

	/**
	 * @param $language
	 * @return $this
	 */
	public function setLanguage( $language )
	{
		$this->language = strtolower( $language );

		return $this;
	}
}

