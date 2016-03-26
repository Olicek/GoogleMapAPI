Google Map API
=========
This component is stated for Nette framework and it simlifies working with a Google map.

Requirements
============
* Nette Framework 2.1+

Installation
============

	composer require olicek/google-map-api:dev-master

and now the component can be registered in extensions in a neon config

```
extensions:
    map: Oli\GoogleAPI\MapApiExtension
```
    	
The last step is to link 2 files to your page.

```
client-side/googleMapAPI.js
client-side/googleMapApiLoader.js
```
   
Configuration in a neon file
==========================

You can define your map and all markers in the config neon file. All configurations in neon are optional.
```yml
map:
	key: your_key								# your personal key to google map
	zoom: 2										
	width: 300px								# map will 300px width
	height: 150px								# map will 150px height
	coordinates:								# you can name keies as you whis or use [49, 15]
		lat: 49.8037633
		lng: 15.4749126
	type: SATELLITE								# type of map
	markers:									# this section will be configured amrkers
		bound: on								# zoom as mutch as possible, but every marker will be displaied
		markerClusterer: on						# neer markers group to one cluster
		addMarkers:								# definitions of markers
			- 									# first marker has no name
				coordinates: 					# the same as map coordinates
					sirka: 47
					vyska: 15
				icon: images/point.png			# it will display png image from www/images/point.png
				message: 
					text: Opened message		# text of message
					autoOpen: on				# if it is on, this message will be displaied after map loaded
			1:									# second marker has name 1
				coordinates: [46, 13]
				animation: DROP					# this marker will has drop animation
				title: Hello world
				icon: images/point2.png
			Prague:								# last marker has name Prague
				coordinates: 
					lat: 48
					lng: 15
				animation: DROP
				message: 
					Closed message
					autoOpen: off				# message has autoOpen default off
```

Default values can be seen in [MapApiExtension](https://github.com/Olicek/GoogleMapAPI/blob/master/src/DI/MapApiExtension.php#L19-L31)

The simplest example of application
==============

Insert to your presenter (if you have PHP 5.4+)

``` php	
class MapPresenter extends Nette\Application\UI\Presenter
{
	use \Oli\GoogleAPI\TMap;
	
	// ...
}
```	
and to the template

	{control map}
	

Define map in a component
=======================
``` php
private $map;
private $markers;

public function __construct(\Oli\GoogleAPI\IMapAPI $mapApi, \Oli\GoogleAPI\IMarkers $markers)
{
	$this->map = $mapApi;
	$this->markers = $markers;
}

public function createComponentMap()
{
	$map = $this->map->create();
	$markers = $this->markers->create();
	
	// ...
	
	$map->addMarkers($markers);
	return $map;
}
```
And in the template

`{control map}`

Markers options
---------------
``` php
$markers = $this->markers->create();
	
$markers->addMarker(array(50.250718,14.583435), false, null);

$markers->setMessage('<h1>Hello world</h1>', false);
$markers->deleteMarkers();
$markers->isMarkerClusterer();	// neer markers group to one cluster
$markers->fitBounds();	// zoom as mutch as possible, but every marker will be displaied
$markers->setColor('red');	// Can set color of markers to 10 diferent colors
$markers->setDefaultIconPath('img/');	// Path which will be prepend icon path

/**
 * Icon from www folder.
 * If default path was not set, setIcon would be '/www/someIcon.png'
 */
$markers->setIcon('someIcon.png');
```
Complex example
---------------

config.neon

	map:
		key: my_key
		width: 750px
		height: 450px
	markers:
		iconDefaultPath: images/mapPoints

Presenter
``` php
protected function createComponentGoogleMap()
{
	$map = $this->map->create();
	
	$map->setCoordinates(array(50.250718,14.583435))
		->setZoom(4)
		->setType(MapAPI::TERRAIN);
		
	$markers = $this->markers->create();
	$markers->fitBounds();
	
	if(count($markersFromDb) > 30)
	{
		$markers->isMarkerClusterer();
	}
	
	foreach ($markersFromDb as $marker)
	{
		$addedMarker = $markers->addMarker(array($marker->lat, $marker->lng), Markers::DROP)
			->setMessage(
				'<h1>'.$marker->title.'</h1><br />'.$marker->description
			)->setIcon($marker->icon);
		
		// $marker->waypoint can be start, end, waypoints
		$map->setWaypoint($marker->waypoint, $addedMarker->getMarker());
	}
	
	$map->setDirection([
	    'avoidHighways' => true,
	    'avoidTolls' => true,
	]);

	$map->addMarkers($markers);
	return $map;
}
```
### Template

```
{block content}
	{control map}
{/block}
```
