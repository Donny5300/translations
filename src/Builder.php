<?php namespace Donny5300\Translations;

use Donny5300\Translations\Repositories\GroupRepo;
use Donny5300\Translations\Repositories\NameRepo;


/**
 * Class Builder
 *
 * @property  GroupRepo                               groupRepo
 * @property \Illuminate\Foundation\Application|mixed nameRepo
 * @property  ucFirst
 * @package Donny5300\Translations*
 * @property  delimeter
 */
class Builder
{
	/** @var array */
	protected $groups;

	/** @var null */
	protected $translations = null;

	/** @var array */
	protected $items;

	/** @var bool */
	private $fallbackLocale = false;

	/**
	 * Builder constructor.
	 */
	public function __construct()
	{
		$this->groupRepo = app( GroupRepo::class );
		$this->nameRepo  = app( NameRepo::class );
		$this->config    = config( 'translations' );
		$this->cache     = cache();

		$this->translations = $this->setTranslations();
	}

	/**
	 * Returns a list with groups
	 */
	public function getGroupList()
	{
		$list   = [ ];
		$groups = $this->getGroups();

		$this->createList( $groups, $list );


		return $list;
	}

	/**
	 * Get all translations
	 *
	 * @return array
	 */
	public function getTranslations()
	{
		return $this->translations;
	}

	/**
	 * @return array
	 */
	protected function setTranslations()
	{
		if( !$this->cache->has( $this->getCacheKey() ) || $this->config['debug'] )
		{
			return $this->translations = $this->buildTranslations();
		}

		return $this->cache->get( $this->getCacheKey() );
	}

	/**
	 * Returns a list with all groups and the progress
	 *
	 * @return array
	 */
	public function getTranslationProgress()
	{
		$count  = [ ];
		$groups = $this->groupRepo->all()->load( 'names.values' )->toArray();

		foreach( $groups as $group )
		{
			$count[$group[$this->getGroupKeyType()]] = $this->calculateGroup( $group['names'] );
		}

		return $count;
	}

	/**
	 * Return all names
	 *
	 * @return array
	 */
	protected function getNamesList()
	{
		return $this->nameRepo
			->getAll()
			->toArray();
	}

	/**
	 * Returns the fallback locale from the app.php config or set
	 * through $this->setFallbackLocale
	 *
	 * @return string
	 */
	protected function getFallbackLocale()
	{
		if( $this->fallbackLocale )
		{
			return $this->fallbackLocale;
		}

		return config( 'app.fallback_locale' );
	}

	/**
	 * Set the fallback language
	 *
	 * @param $locale
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setFallbackLocale( $locale )
	{
		if( strlen( $locale ) != 2 )
		{
			throw new \Exception( 'Fallback locale must be 2 characters!' );
		}

		$this->fallbackLocale = $locale;

		return $this;
	}

	/**
	 * Gets the current locale
	 *
	 * @return string
	 */
	protected function getLocale()
	{
		return app()->getLocale();
	}

	/**
	 * Returns the cachekey that is setup in the config
	 *
	 * @return string
	 */
	protected function getCacheKey()
	{
		return $this->config['cache']['key'];
	}

	/**
	 * Return a flattened group with children and the name title
	 *
	 * @param $groups
	 * @param $name
	 *
	 * @return string
	 */
	protected function getTranslationKey( $groups, $name )
	{
		return $groups[$name->group_id] . '.' . $name->title;
	}

	/**
	 * @return mixed
	 */
	protected function getUcFirst()
	{
		if( $this->ucFirst )
		{
			return $this->ucFirst;
		}
		
		return $this->config['list']['uc_first'];
	}

	/**
	 * Returns the delimeter
	 *
	 * @return string
	 */
	private function getDelimeter()
	{
		if( $this->delimeter )
		{
			return $this->delimeter;
		}

		return $this->config['list']['delimeter'];
	}

	/**
	 * Parses the group title
	 *
	 * @param $path
	 * @param $title
	 *
	 * @return string
	 */
	private function getGroupTitle( $path, $title )
	{
		$title = $this->getUcFirst() ? ucfirst( $title ) : $title;

		return ltrim( $path . $this->getDelimeter() . $title, $this->getDelimeter() );
	}

	/**
	 * Returns a list with groups as groupId => groupTitle
	 *
	 * @return mixed
	 */
	private function getGroups()
	{
		return $this->groupRepo->getGroupTree();
	}

	/**
	 * Return the translation
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	private function getTranslationsByLocale( $key )
	{
		$locale = app()->getLocale();
		if( $this->translationKeyExists( $locale, $key ) )
		{
			return $this->translations[$locale][$key];
		}
		elseif( $this->translationKeyExists( $this->getFallbackLocale(), $key ) )
		{
			return $this->translations[$this->getFallbackLocale()][$key];
		}

		return false;
	}

	/**
	 * @return string
	 */
	private function getGroupKeyType()
	{
		return $this->config['database']['id_field'];
	}

	/**
	 * Calculates how many values are not empty
	 *
	 * @param $name
	 *
	 * @return int
	 */
	private function getTitleAmount( $name )
	{
		$total = 0;
		foreach( $name['values'] as $value )
		{
			if( !empty( $value['title'] ) )
			{
				$total++;
			}
		}

		return $total;
	}

	/**
	 * Set the group name to ucfirst or not
	 *
	 * @param bool $ucfirst
	 *
	 * @return $this
	 */
	public function setUcFirst( $ucfirst = true )
	{
		$this->ucFirst = $ucfirst;

		return $this;
	}

	/**
	 * @param $names
	 *
	 * @return array
	 */
	protected function calculateGroup( $names )
	{
		$total = [
			'total'      => 0,
			'translated' => 0
		];
		foreach( $names as $name )
		{
			$total['total'] += count( $name['values'] );
			$total['translated'] += $this->getTitleAmount( $name );
		}

		$total['percentage'] = $this->calculatePercentage( $total['total'], $total['translated'] );

		return $total;

	}

	/**
	 * @param $locale
	 * @param $translationKey
	 *
	 * @return bool
	 */
	protected function translationKeyExists( $locale, $translationKey )
	{
		return array_key_exists( $locale, $this->translations )
		&& array_key_exists( $translationKey, $this->translations[$locale] )
		&& !empty( $this->translations[$locale][$translationKey] );
	}

	/**
	 * Set the delimeter. Will be used for displaying the user interface
	 *
	 * @param $delimeter
	 *
	 * @return $this
	 */
	public function setDelimeter( $delimeter )
	{
		$this->delimeter = $delimeter;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setGroupKeyByUuid()
	{
		$this->keyByUuid = true;

		return $this;
	}

	/**
	 * @return array
	 */
	private function buildTranslations()
	{
		$translations = [ ];
		$groups       = $this->setDelimeter( '.' )
			->setUcFirst( false )
			->getGroupList();

		$names = $this->nameRepo
			->getAll()
			->load( 'values.language' );

		foreach( $names as $name )
		{
			foreach( $name->values as $value )
			{
				$langKey = $value->language->short_two;
				$key     = $this->getTranslationKey( $groups, $name );

				$translations[$langKey][$key] = $value->title;
			}
		}

		$this->writeToCache( $translations );

		return $translations;
	}

	/**
	 * @param        $in
	 * @param        $out
	 * @param string $path
	 */
	protected function createList( $in, &$out, $path = '' )
	{
		foreach( $in as $data )
		{
			$title                                = $this->getGroupTitle( $path, $data['title'] );
			$out[$data[$this->getGroupKeyType()]] = $title;
			if( count( $data['groups'] ) > 0 )
			{
				$this->createList( $data['groups'], $out, $title );
			}
		}
	}

	/**
	 * @param $translations
	 *
	 * @return mixed
	 */
	private function writeToCache( $translations )
	{
		if( !$this->config['debug'] )
		{
			return $this->cache->forever( $this->getCacheKey(), $translations );
		}
	}

	/**
	 * @param $total
	 * @param $translated
	 *
	 * @return int
	 */
	private function calculatePercentage( $total, $translated )
	{
		if( $total !== $translated )
		{
			$perItem = $total / 100;
			$amount  = $total - $translated;

			return intval( 100 - round( $amount / $perItem ) );
		}

		return 100;
	}

	/**
	 * Insert string that must be translated
	 *
	 * @param $key
	 *
	 * @return string
	 */
	public function translate( $key )
	{
		$args = func_get_args();

		if( $translation = $this->getTranslationsByLocale( $key ) )
		{
			foreach( $args as $index => $replace )
			{
				$translation = str_replace( '$' . $index, $replace, $translation );
			}

			return $translation;
		}

		return $this->getLocale() . ':' . $key;
	}
}