Oli/GoogleAPI/MapAPI
===============


Requirements
------------
Oli/GoogleAPI/MapAPI requires PHP 5.3.2 with pdo extension.

* Nette Framework 2.0.x
* Jquery 1.8.x or newer

Installation
------------

- download plugin and extract it to libs directory (or anywhere where loader can see)

Initialize
----------

minimum of what is required to fill

config.neon:
```php
googleMap: Oli\GoogleAPI\MapAPI(@httpRequest, @mapRepository)
```

Presenter:
```php
public function injectGoogleMap(\Oli\GoogleAPI\MapAPI $googleMap)
{
	$this->googleMap = $googleMap;
}

protected function createComponentGoogleMap()
{
	$map = $this->googleMap;
	$map->initialMap(array(latitude, longitude))
	return $map;
}
```