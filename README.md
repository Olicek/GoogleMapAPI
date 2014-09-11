GoogleAPI
=========
This component is for Nette framework and make Google map display easier

Requirements
------------
* Nette Framework 2.1 or newer
* Jquery 1.8.x or newer

Installation
-------------

	composer require olicek/google-map-api: dev-master

then you can enable the extension using your neon config

	extensions:
    	map: Oli\GoogleAPI\MapApiExtension
   
Configuration in neon file
--------------------------

You can define your map and all markers in in config neon file.
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
		bound: on								# zoom as mutch as every marker will be displaied
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

Simplest usage
---------------

To your presenter include 

	use \Oli\GoogleAPI\TMap;
	
and to template

	{control map}
	

TODO...
