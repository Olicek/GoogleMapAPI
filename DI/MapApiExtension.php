<?php

namespace Oli\GoogleAPI;

/**
 * Description of MapApiExtension
 *
 * @author petr
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
	    'static' => false,
	    'bound' => false,
	    'markerClusterer' => false,
	    'markers' => array(
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
			->addSetup('fitBounds', [$config['bound']])
			->addSetup('isMarkerClusterer', [$config['markerClusterer']])
			->addSetup('isStaticMap', [$config['static']])
			->addSetup('setZoom', [$config['zoom']]);
		
		$markers = $builder->addDefinition($this->prefix('markers'))
			->setImplement('Oli\GoogleAPI\IMarkers')
			->setFactory('Oli\GoogleAPI\Markers')
			->addSetup('setDefaultIconPath', [$config['markers']['iconDefaultPath']])
			->addSetup('addMarkers', [$config['markers']['addMarkers']]);
	}
}
