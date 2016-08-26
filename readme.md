# How to install

Add to the composer: `composer require donny5300/translations`

Publish config: `artisan vendor:publish --tag="translations-config"`

Add to `app.providers` : `Donny5300\Translations\ServiceProvider::class`

#### Config ####
If you would like to use the system interface, set `translations.system` to `true`. At every 'module' heading there is a route path available

#### Languages ####
Route: `translations.languages.*`

Actions: `create, edit, store, destroy, update`

URL: `/system/translations/languages`


#### Groups ####
Route: `translations.languages.*`

Actions: `create, edit, store, destroy, update`

URL: `/system/translations/languages`



#### Names ####
Route: `translations.languages.*`

Actions: `create, edit, store, destroy, update`

URL: `/system/translations/languages`

### Methods ###

##### Setters #####
Below there is a list with available methods. All methods are returning `$this`. Using the setters can be done through `app('translations')`
```
$this->setFallbackLocale('en');
$this->setUcFirst( true )
$this->setDelimeter(' > ')
```

##### Getters #####
Below is a list of getters. These Getters can be could through `app('translations')`

```
$this->getGroupList();
```
Returns a array with key,value.
```
$this->getTranslations();
```
Returns a list with all translations. In example `'form.label.welcome_back' => 'Title'`
```
$this->getTranslationProgress();
```
Returns a multidimensional array. Parent is the group ID. The child contains 3 keys: total
`( total translations available = names x amount of languages )`, translated: `( the amount of translations that are done )` and percentage `( amount in percentage that is done )`
```
$this->translate('key', 'arg1', 'arg2', 'arg3');
```
Lets take a example with greeting a user and wish him a good evening. First we create 2 groups: `page_headings` and `user` that has the parent `page_headings`.

After creating the groups, we add a new translation ` name ` called 'greet_user'. Now we need to give up the translation for it.
Arguments are passed in as 2nd option till infinite. The arguments must be placed with a `$` sign. As example: `Hello, welcome back $1! We wish you a good $2!`

Now when we use one of the translation functions, we can pass in a translation string with some arguments.

###### Twig templates ######
```
 {{ 'page_headings.user.greet_user` | t('Donny5300', 'evening') }}
```

###### PHP Function ######
If the function is created by this package:
```
<?php translate('page_headings.user.greet_user', 'Donny5300', 'evening');
```

###### Through the singleton instance ######
```
<?php app('translations')->translate('page_headings.user.greet_user', 'Donny5300', 'evening');
```

###### Output ######
The output of the translation function will be `Hello, welcome back Donny5300! We wish you a good evening!`

## Using custom views and controllers ##
To use own templates for the translation interface, each controller can be extended. Also you need to at a property called '$packageView' Calling the parent will only return a array of data wich contains the config and the controller data.
A quick example how to use your own templates:

```
<?php namespace App\Http\Controllers;

class LanguageController extends Donny5300\Translations\Controllers\LanguagesController
{
	protected $packageView = false;

	public function index()
	{
		$data = parent::index();

		return view('path.to.your.view', compact('data'));
	}

}
```

By extending the view, the necessary repositories are loaded. Those repositories only contain functions that has something to do with Eloquent.