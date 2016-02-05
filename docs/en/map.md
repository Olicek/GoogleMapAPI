Map
=======

The easiest way how to create `Oli\GoogleAPI\MapAPI` object is to use a factory

```
public function injectGoogleMapAPI(IMapAPI $mapApi)
{
	$this->googleMapAPI = $mapApi;
}

public function createComponentMap()
{
    $map = $this->googleMapAPI->create();
	return $map;
}
```

Object `MapAPI` with the settings from a config is available in variable `$map` in this moment.

Methods
-------


### setCoordinates(array $coordinates)

In coordinates, you can set a latitude and a longitude. If the coordinates are empty, it will set NULL. It's the same as [0, 0].

### setProportions($width, $height)

This method sets a width and a height of the map. It's optional, but if proportions are not set, the map will not be displaied unless you set it in css.

### setKey($key)


### setZoom($zoom)

Zoom is integer and it must be <0, 19>. Default value is 7.

### setType($type)

It can be only one of [4 basic](https://developers.google.com/maps/documentation/javascript/maptypes#BasicMapTypes) map types.

### isStaticMap($staticMap = TRUE)

This method creates map as a picture. When the map is static, only `height`, `width`, `zoom`, `coordinates` and markers has an effect. 

### isScrollable($scrollable = TRUE)

If scrollable is `TRUE`, when mouse is over the map and a user is scrolling, it has no effect.

### isClickable($clickable = TRUE)

Clickable can be boolean or callable. If clickable is boolean it generates link like this:
`<a href="https://maps.google.com/maps/place/50.250718,14.583435/">`. If clickable is callable
it must return \Nette\Utils\Html. Callable accept 2 parameters. `$url` and `$coordinates`.

```
isClickable(function ($url, $coordinates) {
	return Nette\Utils\Html::el('a', ['class' => 'foo'])->href($url);
})
```

### addMarkers(Markers $markers)

This method must be called, if you want to put the markers to the map. It just transmit markers from `$markers` to the map.

### setWaypoint($key, $waypoint)

Key defines, if the marker will be first, last or betwen. Values are `start`, `end` and `waypoints`. `$waypoint` is array. The marker can be set as waypoint, for example like that: 

```
$start = $markers->addMarker([46, 13], Markers::BOUNCE, 'Start')->getMarker();
$end = $markers->addMarker([46, 12], FALSE, 'Finish')->getMarker();
$map->setWaypoint('start', $start)->setWaypoint('end', $end);
```

### setDirection(array $direction)

Direction sets a few additional parameters for drawing a way. Everything what is [here](https://developers.google.com/maps/documentation/javascript/3.exp/reference#DirectionsRequest) can be set. If nothing is set, it will use just `travelmode = DRIVING`.

### invalidateMarkers()

It is alias to `handleMarkers()`

Markers are loaded lazy via ajax, after eveything else is done. If you need to redraw the markers, you can call this method and markers will be replaced.
