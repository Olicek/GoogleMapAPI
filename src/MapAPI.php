<?php
/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\GoogleAPI;

use Nette\Application\UI\Control;
use Nette\Application\Responses\JsonResponse;
use Nette\Utils\Html;
use Nette\Utils\Json;
use Oli\GoogleAPI\Exceptions\InvalidArgumentException;
use Oli\GoogleAPI\Exceptions\LogicException;

/**
 * Description of GoogleAPI
 * @author Petr Olišar <petr.olisar@gmail.com>
 * @property \Oli\GoogleAPI\Templates\Data\MapStaticTemplate $template
 */
class MapAPI extends Control
{

  public const ROADMAP = 'ROADMAP', SATELLITE = 'SATELLITE', HYBRID = 'HYBRID', TERRAIN = 'TERRAIN', BICYCLING = 'BICYCLING', DRIVING = 'DRIVING', TRANSIT = 'TRANSIT', WALKING = 'WALKING';

  /**
   * @var double|string
   */
  private $width;

  /**
   * @var double|string
   */
  private $height;

  /**
   * @var array
   */
  private array $coordinates;

  /**
   * @var int
   */
  private int $zoom;

  /**
   * @var string
   */
  private string $type;

  /**
   * @var bool
   */
  private bool $staticMap = false;

  /**
   * @var bool|string|callable
   */
  private $clickable = false;

  /**
   * @var string
   */
  private string $key;

  /**
   * @var array
   */
  private array $markers = [];

  /**
   * @var bool
   */
  private bool $bound;

  /**
   * @var bool
   */
  private bool $markerClusterer;

  /**
   * @var array
   */
  private array $clusterOptions;

  /**
   * @var bool
   */
  private bool $scrollable = false;

  /**
   * @var array|null
   */
  private ?array $waypoints = null;

  /**
   * @var array
   */
  private array $direction = ['travelmode' => 'DRIVING'];

  /**
   * @param array $config
   * @internal
   */
  public function setup(array $config): void
  {
    $this->width = $config['width'];
    $this->height = $config['height'];
  }

  /**
   * @param array $coordinates (latitude, longitude) - center of the map
   * @return $this
   */
  public function setCoordinates(array $coordinates): MapAPI
  {
    if(!count($coordinates))
    {
      $this->coordinates = [null, null];
    }
    else
    {
      $this->coordinates = array_values($coordinates);
    }

    return $this;
  }

  /**
   * @param double|string $width
   * @param double|string $height
   * @return $this
   */
  public function setProportions($width, $height): MapAPI
  {
    $this->width = $width;
    $this->height = $height;

    return $this;
  }

  /**
   * @param string $key
   * @return $this
   */
  public function setKey(string $key): MapAPI
  {
    $this->key = $key;

    return $this;
  }

  /**
   * @param int $zoom <0, 19>
   * @return $this
   * @throws \Oli\GoogleAPI\Exceptions\LogicException
   */
  public function setZoom(int $zoom): MapAPI
  {
    if($zoom < 0 || $zoom > 19)
    {
      throw new LogicException('Zoom must be betwen <0, 19>.');
    }

    $this->zoom = $zoom;

    return $this;
  }

  /**
   * @param string $type
   * @return $this
   * @throws InvalidArgumentException
   */
  public function setType(string $type): MapAPI
  {
    if($type !== self::HYBRID && $type !== self::ROADMAP && $type !== self::SATELLITE && $type !== self::TERRAIN)
    {
      throw new InvalidArgumentException;
    }
    $this->type = $type;

    return $this;
  }

  /**
   * @param string $key
   * @param array $waypoint
   * @return $this
   * @throws InvalidArgumentException
   */
  public function setWaypoint(string $key, array $waypoint): MapAPI
  {
    if(!in_array($key, ['start', 'end', 'waypoint']))
    {
      throw new InvalidArgumentException('First argument must be "start|end|waypoint", ' . $key . ' was given');
    }

    if($key === 'waypoint')
    {
      $this->waypoints['waypoints'][] = $waypoint;

    }
    else
    {
      $this->waypoints[$key] = $waypoint;
    }

    return $this;
  }

  /**
   * @param array $direction
   * @return $this
   * @throws InvalidArgumentException
   */
  public function setDirection(array $direction): MapAPI
  {
    $this->direction = $direction;
    if(!array_key_exists('travelmode', $this->direction))
    {
      $this->direction['travelmode'] = 'DRIVING';
    }
    else if(!in_array(
      $direction['travelmode'], [
      self::BICYCLING,
      self::DRIVING,
      self::WALKING,
      self::TRANSIT,
    ], true
    ))
    {
      throw new InvalidArgumentException;
    }

    return $this;
  }

  /**
   * @return array  Width and Height of the map.
   */
  public function getProportions(): array
  {
    return ['width' => $this->width, 'height' => $this->height];
  }

  /**
   * @return array  Center of the map
   */
  public function getCoordinates(): array
  {
    return $this->coordinates;
  }

  /**
   * @return int
   */
  public function getZoom(): int
  {
    return $this->zoom;
  }

  /**
   * @return string  Which map type will be show
   */
  public function getType(): string
  {
    return $this->type;
  }

  /**
   * @return array
   */
  public function getWaypoints(): array
  {
    return $this->waypoints;
  }

  /**
   * @return array
   */
  public function getDirection(): array
  {
    return $this->direction;
  }

  /**
   * @return string
   */
  public function getKey(): string
  {
    return $this->key;
  }

  /**
   * @param bool $staticMap
   * @return $this
   * @throws InvalidArgumentException
   */
  public function isStaticMap($staticMap = true): MapAPI
  {
    if(!is_bool($staticMap))
    {
      throw new InvalidArgumentException("staticMap must be boolean, $staticMap (" . gettype($staticMap) . ") was given");
    }

    $this->staticMap = $staticMap;

    return $this;
  }

  /**
   * @return bool
   */
  public function getIsStaticMap(): bool
  {
    return $this->staticMap;
  }

  /**
   * @param bool|callable $clickable
   * @return $this
   * @throws InvalidArgumentException
   */
  public function isClickable($clickable = true): MapAPI
  {
    if(!$this->staticMap)
    {
      throw new InvalidArgumentException("the 'clickable' option only applies to static map");
    }

    if(!is_bool($clickable) && !is_callable($clickable))
    {
      throw new InvalidArgumentException(
        "clickable must be boolean or callable, $clickable (" . gettype($clickable) . ") was given"
      );
    }

    if(is_callable($clickable))
    {
      $this->clickable = $clickable;

    }
    else if($clickable !== false)
    {
      $this->clickable = '<a href="https://maps.google.com/maps/place/' . $this->coordinates[0] . ',' . $this->coordinates[1] . '/">';
    }

    return $this;
  }

  /**
   * @return bool|callable|string
   */
  public function getIsClicable()
  {
    return $this->clickable;
  }

  /**
   * @param bool $scrollable
   * @return $this
   * @throws InvalidArgumentException
   */
  public function isScrollable($scrollable = true): MapAPI
  {
    if(!is_bool($scrollable))
    {
      throw new InvalidArgumentException("staticMap must be boolean, $scrollable (" . gettype($scrollable) . ") was given");
    }

    $this->scrollable = $scrollable;

    return $this;
  }

  /**
   * @return bool
   */
  public function getIsScrollable(): bool
  {
    return $this->scrollable;
  }

  /**
   * @param Markers $markers
   * @return $this
   */
  public function addMarkers(Markers $markers): MapAPI
  {
    $this->markers = $markers->getMarkers();
    $this->bound = $markers->getBound();
    $this->markerClusterer = $markers->getMarkerClusterer();
    $this->clusterOptions = $markers->getClusterOptions();

    return $this;
  }

  /**
   * Alias to handleMarkers()
   */
  public function invalidateMarkers(): void
  {
    $this->handleMarkers();
  }

  /**
   * @throws \Nette\Utils\JsonException
   */
  public function render(): void
  {
    if($this->staticMap)
    {
      if(is_callable($this->clickable))
      {
        $this->clickable = call_user_func(
          $this->clickable, 'https://maps.google.com/maps/place/' . $this->coordinates[0] . ',' . $this->coordinates[1] . '/', $this->getCoordinates()
        );
      }

      $this->template->height = $this->height;
      $this->template->width = $this->width;
      $this->template->zoom = $this->zoom;
      $this->template->position = $this->coordinates;
      $this->template->markers = $this->markers;
      $this->template->clickable = $this->clickable instanceof Html ? $this->clickable->startTag() : $this->clickable;
      $this->template->setFile(__DIR__ . '/Templates/static.latte');
    }
    else
    {
      $map = [
        'position' => $this->coordinates,
        'height' => $this->height,
        'width' => $this->width,
        'zoom' => $this->zoom,
        'type' => $this->type,
        'scrollable' => $this->scrollable,
        'key' => $this->key,
        'bound' => $this->bound,
        'cluster' => $this->markerClusterer,
        'clusterOptions' => $this->clusterOptions,
        'waypoint' => !is_null($this->waypoints) ? array_merge($this->waypoints, $this->direction) : null,
      ];
      $this->template->map = Json::encode($map);
      $this->template->setFile(__DIR__ . '/template.latte');
    }
    $this->template->render();
  }

  /**
   * Send markers to template as JSON
   * @internal
   */
  public function handleMarkers(): void
  {
    $this->getPresenter()
      ->sendResponse(new JsonResponse($this->markers));
  }
}
