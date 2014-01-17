GoogleAPI
=========

Requirements
------------
Oli/GoogleAPI/MapAPI requires PHP 5.3.2 with pdo extension.

* Nette Framework 2.0.x or newer
* Jquery 1.8.x or newer

Installation
------------

- download plugin and extract it to libs directory (or anywhere where loader can see)

Simplest example
----------------

```php
protected function createComponentGoogleMap()
{
	$map = new MapAPI(array(50.250718,14.583435), 4, 'yourKey');
	return $map;
}
```

Map options
-----------

```php
protected function createComponentGoogleMap()
{
	$map->setProportions('800px', '480px');   // Default is 100% x 100%. If proportion is default, map must be 
	                                          // wrapped to some concrete proportions otherwise it will not be displayed.
	$map->isStaticMap();                      // Map will be displayed as img. 
	                                          // To the image can be inserted colored markers.
	$map->addMarkers($markers);               // It transmits the map markers.
	return $map;
}
```

Markers options
-----------

```php
$markers = new Markers();
$markers->addMarker(array(50.250718,14.583435), false, null);   // Put marker to the coordinate
                                                                // Optional animation, can be DROP, BOUNCE, false
                                                                // Optional title can be string|null
$markers->setMessage('<h1>Hello world</h1>', false);            // Message can contains HTML tags
                                                                // Optional auto open: auto open the message
                                                                // Static map can not display messages
$markers->color('red');                                         // Can set color of markers to 10 diferent colors
$markers->setDefaultIconPath('/img/');                          // Path which will be prepend icon path
$markers->setIcon('someIcon.png');                              // Icon from www folder.
                                                                // If default path was not set, 
                                                                // setIcon would be '/img/someIcon.png'
```

Complex example
-----------

```php
protected function createComponentGoogleMap()
{
	$map = new MapAPI(array(50.250718,14.583435), 4, 'yourKey');
	$map->setProportions('940px', '400px');
	$markers = new Markers();
	$markers->setDefaultIconPath('/img/');
	foreach ($markersFromDb as $marker)
  {
    $markers->addMarker(array($marker->lat, $marker->lng), Marker::DROP)
		  ->setMessage(
		    '<h1>'.$marker->title.'</h1><br />'.$marker->description
		  )->setIcon($marker->icon.'.png');
  }
	$map->addMarkers($markers);
	return $map;
}
```
