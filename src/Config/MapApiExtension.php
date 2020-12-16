<?php
/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\GoogleAPI\Config;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Oli\GoogleAPI\MapAPI;
use Oli\GoogleAPI\Markers;

/**
 * Description of MapApiExtension
 * @author Petr Olišar <petr.olisar@gmail.com>
 * @property \Oli\GoogleAPI\Config\Config $config
 */
class MapApiExtension extends CompilerExtension
{

  public array $defaults = [
    'key' => null,
    'width' => '100%',
    'height' => '100%',
    'zoom' => 7,
    'coordinates' => [],
    'type' => 'ROADMAP',
    'scrollable' => true,
    'static' => false,
    'markers' => [
      'bound' => false,
      'markerClusterer' => false,
      'iconDefaultPath' => null,
      'icon' => null,
      'addMarkers' => [],
    ],
  ];

  public function getConfigSchema(): Schema
  {
    return Expect::from(new Config());
  }

  public function loadConfiguration(): void
  {
    $this->setConfig($this->defaults);
    $builder = $this->getContainerBuilder();

    $builder->addDefinition($this->prefix('mapAPI'))
      ->setFactory(MapAPI::class)
      ->addSetup('setup', [$this->config])
      ->addSetup('setKey', [$this->config->key])
      ->addSetup('setCoordinates', [$this->config->coordinates])
      ->addSetup('setType', [$this->config->type])
      ->addSetup('isStaticMap', [$this->config->static])
      ->addSetup('isScrollable', [$this->config->scrollable])
      ->addSetup('setZoom', [$this->config->zoom]);

    $builder->addDefinition($this->prefix('markers'))
      ->setFactory(Markers::class)
      ->addSetup('setDefaultIconPath', [$this->config->markers['iconDefaultPath']])
      ->addSetup('fitBounds', [$this->config->markers['bound']])
      ->addSetup('isMarkerClusterer', [$this->config->markers['markerClusterer']])
      ->addSetup('addMarkers', [$this->config->markers['addMarkers']]);
  }

}
