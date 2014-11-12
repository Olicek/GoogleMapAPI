<?php
/**
 * Copyright (c) 2014 Petr Olišar (http://olisar.eu)
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
			->addSetup('setup', [$config])
			->addSetup('setKey', [$config['key']])
			->addSetup('setCoordinates', [$config['coordinates']])
			->addSetup('setType', [$config['type']])
			->addSetup('isStaticMap', [$config['static']])
			->addSetup('isScrollable', [$config['scrollable']])
			->addSetup('setZoom', [$config['zoom']]);
		
		$builder->addDefinition($this->prefix('markers'))
			->setImplement('Oli\GoogleAPI\IMarkers')
			->setFactory('Oli\GoogleAPI\Markers')
			->addSetup('setDefaultIconPath', [$config['markers']['iconDefaultPath']])
			->addSetup('fitBounds', [$config['markers']['bound']])
			->addSetup('isMarkerClusterer', [$config['markers']['markerClusterer']])
			->addSetup('addMarkers', [$config['markers']['addMarkers']]);
	}
}
