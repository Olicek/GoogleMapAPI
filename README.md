Google Map API [![Build Status](https://travis-ci.org/Olicek/GoogleMapAPI.svg?branch=master)](https://travis-ci.org/Olicek/GoogleMapAPI)
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
    	
The last step is to link `client-side/googleMapAPI.js` to your page.

[Documentation](https://github.com/Olicek/GoogleMapAPI/blob/master/docs/en)
