# Google Map API

[![Latest stable](https://img.shields.io/packagist/v/olicek/google-map-api.svg)](https://packagist.org/packages/olicek/google-map-api) [![Packagist](https://img.shields.io/packagist/dt/olicek/google-map-api.svg)](https://packagist.org/packages/olicek/google-map-api/stats) [![Build Status](https://travis-ci.org/Olicek/GoogleMapAPI.svg?branch=master)](https://travis-ci.org/Olicek/GoogleMapAPI) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Olicek/GoogleMapAPI/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Olicek/GoogleMapAPI/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Olicek/GoogleMapAPI/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Olicek/GoogleMapAPI/?branch=master)


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

[Documentation](https://github.com/Olicek/GoogleMapAPI/blob/master/docs/en)
