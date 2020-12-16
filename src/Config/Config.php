<?php declare(strict_types = 1);

namespace Oli\GoogleAPI\Config;

/**
 * Class Config
 * @package Oli\GoogleAPI\Config
 */
class Config
{

  public ?string $key = null;

  public string $width = '100%';

  public string $height = '100%';

  public int $zoom = 7;

  public array $coordinates = [];

  public string $type = 'ROADMAP';

  public bool $scrollable = true;

  public bool $static = false;

  public array $markers = [
    'bound' => false,
    'markerClusterer' => false,
    'iconDefaultPath' => null,
    'icon' => null,
    'addMarkers' => [],
  ];

}
