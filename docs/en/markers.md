Markers
=======

The easiest way, how to create `Oli\GoogleAPI\Markers` object, is to use a factory

```
public function injectGoogleMapMarkers(\Oli\GoogleAPI\Markers $markers)
{
	$this->googleMapMarkers = $markers;
}

public function createComponentMap()
{
	$markers = $this->googleMapMarkers->create();
	// ...
	return $markers;
}
```

Object `Markers` with all the markers from the config is in variable `$markers` in this moment.

Methods
-------


### addMarkers()

```
$markers->addMarkers($array);
```

You can add multiple markers through this method. This method is internally used to add markers from the config.

### addMarker()

```
/**
 * @param array $animation
 * @param boolean|string $animation 
 * @param NULL|string $animation
 */
$markers->addMarker(array $position, $animation = FALSE, $title = NULL);
```

This method is the only one which is required to create a marker.

Position is an array, where the array is [latitude, longitude]. The animation can be `Markers::DROP`, `Markers::BOUNCE` or default `FALSE`.
Title is like HTML title, which is showed on mouseover. 

### getMarker()

```
$markers->getMarker();
```

Return last inserted marker.

### deleteMarkers()

```
$markers->deleteMarkers();
```

Delete all markers.

### setMessage()

```
$markers->setMessage($message, $autoOpen = FALSE);
```

Set [message](https://developers.google.com/maps/documentation/javascript/examples/infowindow-simple), with is showed by click on marker. If second parameter is `TRUE`, message is shown by default.

> Message can contains HTML tags

### isMarkerClusterer()

```
$markers->isMarkerClusterer($cluster = TRUE);
```

Create [cluster](https://googlemaps.github.io/js-marker-clusterer/docs/examples.html) from close markers.

### getMarkerClusterer()

### fitBounds()

```
$markers->fitBounds($bound = TRUE);
```

Set zoom as close as possible and all markers are shown in the same time.

### getBound()

### setDefaultIconPath()

```
$markers->setDefaultIconPath($defaultPath);
```

Set default path to icons. This path will be used to the every next added icon. It's relative to `www` folder.


### setIcon()

```
$markers->setIcon($icon);
```

If default path is set, it will be used before `$icon` string. It's relative to `www` folder (or default path folder).

### setColor()

```
$markers->setColor($color)
```

A few colors can be set through this simple way. It just colorizes the default marker.

* green
* purple
* yellow
* blue
* orange
* red