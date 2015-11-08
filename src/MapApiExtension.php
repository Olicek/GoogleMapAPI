<?php
/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\GoogleAPI;


/**
 * Description of MapApiExtension
 *
 * @author Petr Olišar <petr.olisar@gmail.com>
 */
class MapApiExtension extends \Nette\DI\CompilerExtension
{

	public $defaults = array(
	    'key' => null,
	    'width' => '100%',
	    'height' => '100%',
	    'zoom' => 7,
	    'coordinates' => array(),
	    'type' => 'ROADMAP',
	    'scrollable' => true,
	    'static' => false,
	    'markers' => array(
		'bound' => false,
		'markerClusterer' => false,
		'iconDefaultPath' => null,
		'icon' => null,
		'addMarkers' => array()
	    )
	);
	
	
	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		
		$builder->addDefinition($this->prefix('mapAPI'))
			->setImplement('Oli\GoogleAPI\IMapAPI')
			->setFactory('Oli\GoogleAPI\MapAPI')
			->addSetup('setup', array($config))
			->addSetup('setKey', array($config['key']))
			->addSetup('setCoordinates', array($config['coordinates']))
			->addSetup('setType', array($config['type']))
			->addSetup('isStaticMap', array($config['static']))
			->addSetup('isScrollable', array($config['scrollable']))
			->addSetup('setZoom', array($config['zoom']));
		
		$builder->addDefinition($this->prefix('markers'))
			->setImplement('Oli\GoogleAPI\IMarkers')
			->setFactory('Oli\GoogleAPI\Markers')
			->addSetup('setDefaultIconPath', array($config['markers']['iconDefaultPath']))
			->addSetup('fitBounds', array($config['markers']['bound']))
			->addSetup('isMarkerClusterer', array($config['markers']['markerClusterer']))
			->addSetup('addMarkers', array($config['markers']['addMarkers']));
	}

}
