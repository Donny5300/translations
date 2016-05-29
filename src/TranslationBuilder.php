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

//			$this->setCache();

//			dd('File: ' . __FILE__, 'Line: '. __LINE__ , $this->groups);

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
	 * @return $this
	 */
	public function build()
	{
		$this->setGroups();
		$this->setDotGroups( $this->groups );
		$this->setCache();

		return $this;
	}

	/**
	 * @return array
	 */
	public function render()
	{
		return array_filter( $this->dottedGroups );
	}

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

