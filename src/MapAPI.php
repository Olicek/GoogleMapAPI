<?php
/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\GoogleAPI;

use Nette\Application\UI\Control;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\ITemplate;
use Nette\Utils\Html;
use Nette\Utils\Json;


/**
 * Description of GoogleAPI
 *
 * @author Petr Olišar <petr.olisar@gmail.com>
 * @property-read ITemplate $template
 */
class MapAPI extends Control
{

	const ROADMAP = 'ROADMAP', SATELLITE = 'SATELLITE', HYBRID = 'HYBRID', TERRAIN = 'TERRAIN',
		BICYCLING = 'BICYCLING', DRIVING = 'DRIVING', TRANSIT = 'TRANSIT', WALKING = 'WALKING';

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
	private $coordinates;

	/**
	 * @var int
	 */
	private $zoom;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var bool
	 */
	private $staticMap = FALSE;

	/**
	 * @var bool|string|callable
	 */
	private $clickable = FALSE;

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var array
	 */
	private $markers = array();

	/**
	 * @var bool
	 */
	private $bound;

	/**
	 * @var bool
	 */
	private $markerClusterer;

	/**
	 * @var array
	 */
	private $clusterOptions;

	/**
	 * @var bool
	 */
	private $scrollable = FALSE;

	/**
	 * @var array
	 */
	private $waypoints;

	/**
	 * @var array
	 */
	private $direction = ['travelmode' => 'DRIVING'];


	public function __construct()
	{

	}


	/**
	 * @param array $config
	 * @internal
	 */
	public function setup(array $config)
	{
		$this->width = $config['width'];
		$this->height = $config['height'];
	}


	/**
	 * @param array $coordinates	(latitude, longitude) - center of the map
	 * @return $this
	 */
	public function setCoordinates(array $coordinates)
	{
		if(!count($coordinates))
		{
			$this->coordinates = array(NULL, NULL);
		} else
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
	public function setProportions($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
		return $this;
	}


	/**
	 * @param string $key
	 * @return $this
	 */
	public function setKey($key)
	{
		$this->key = $key;
		return $this;
	}


	/**
	 * @param int $zoom		<0, 19>
	 * @return $this
	 * @throws InvalidArgumentException
	 * @throws LogicException
	 */
	public function setZoom($zoom)
	{
		if (!is_int($zoom))
		{
			throw new InvalidArgumentException("type must be integer, $zoom (".gettype($zoom).") was given");
		}

		if ($zoom < 0 || $zoom > 19)
		{
			throw new LogicException('Zoom must be betwen <0, 19>.');
		}

		$this->zoom = (int) $zoom;
		return $this;
	}


	/**
	 * @param string $type
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setType($type)
	{
		if($type !== self::HYBRID && $type !== self::ROADMAP && $type !== self::SATELLITE &&
				$type !== self::TERRAIN)
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
	public function setWaypoint($key, array $waypoint)
	{
		if (!in_array($key, ['start', 'end', 'waypoint']))
		{
			throw new InvalidArgumentException('First argument must be "start|end|waypoint", ' . $key . ' was given');
		}

		if($key === 'waypoint')
		{
			$this->waypoints['waypoints'][] = $waypoint;

		} else
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
	public function setDirection(array $direction)
	{
		$this->direction = $direction;
		if(!array_key_exists('travelmode', $this->direction))
		{
			$this->direction['travelmode'] = 'DRIVING';
		} else if (!in_array($direction['travelmode'], [
			self::BICYCLING, self::DRIVING, self::WALKING, self::TRANSIT
		]))
		{
			throw new InvalidArgumentException;
		}
		return $this;
	}


	/**
	 * @return array	Width and Height of the map.
	 */
	public function getProportions()
	{
		return array('width' => $this->width, 'height' => $this->height);
	}


	/**
	 * @return array	Center of the map
	 */
	public function getCoordinates()
	{
		return $this->coordinates;
	}


	/**
	 * @return int
	 */
	public function getZoom()
	{
		return $this->zoom;
	}


	/**
	 * @return string	Which map type will be show
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @return array
	 */
	public function getWaypoints()
	{
		return $this->waypoints;
	}


	/**
	 * @return array
	 */
	public function getDirection()
	{
	    return $this->direction;
	}


	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}


	/**
	 * @param bool $staticMap
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function isStaticMap($staticMap = TRUE)
	{
		if (!is_bool($staticMap))
		{
			throw new InvalidArgumentException("staticMap must be boolean, $staticMap (".gettype($staticMap).") was given");
		}

		$this->staticMap = $staticMap;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getIsStaticMap()
	{
		return $this->staticMap;
	}


	/**
	 * @param bool|callable $clickable
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function isClickable($clickable = TRUE)
	{
		if (!$this->staticMap)
		{
			throw new InvalidArgumentException("the 'clickable' option only applies to static map");
		}

		if (!is_bool($clickable) && !is_callable($clickable))
		{
			throw new InvalidArgumentException(
				"clickable must be boolean or callable, $clickable (".gettype($clickable).") was given"
			);
		}

		if (is_callable($clickable)) {
			$this->clickable = $clickable;

		} else if ($clickable !== FALSE)
		{
			$this->clickable = '<a href="https://maps.google.com/maps/place/' .
				$this->coordinates[0] . ',' . $this->coordinates[1] . '/">';
		}

		return $this;
	}


	/**
	 * @return bool
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
	public function isScrollable($scrollable = TRUE)
	{
		if (!is_bool($scrollable))
		{
			throw new InvalidArgumentException("staticMap must be boolean, $scrollable (".gettype($scrollable).") was given");
		}
		
		$this->scrollable = $scrollable;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getIsScrollable()
	{
		return $this->scrollable;
	}


	/**
	 * @param Markers $markers
	 * @return $this
	 */
	public function addMarkers(Markers $markers)
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
	public function invalidateMarkers()
	{
		$this->handleMarkers();
	}


	/**
	 * @throws \Nette\Utils\JsonException
	 */
	public function render()
	{
		if ($this->staticMap)
		{
			if (is_callable($this->clickable))
			{
				$this->clickable = call_user_func_array($this->clickable, [
					'https://maps.google.com/maps/place/' . $this->coordinates[0] . ',' .
					$this->coordinates[1] . '/',
					$this->getCoordinates()
				]);
			}

			$this->template->height = $this->height;
			$this->template->width = $this->width;
			$this->template->zoom = $this->zoom;
			$this->template->position = $this->coordinates;
			$this->template->markers = $this->markers;
			$this->template->clickable = $this->clickable instanceof Html ? $this->clickable->startTag() :
				$this->clickable;
			$this->template->setFile(dirname(__FILE__) . '/static.latte');
		} else
		{
			$map = array(
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
			    'waypoint' => !is_null($this->waypoints) ? array_merge($this->waypoints, $this->direction) : NULL
			);
			$this->template->map = Json::encode($map);
			$this->template->setFile(dirname(__FILE__) . '/template.latte');
		}
		$this->template->render();
	}


	/**
	 * Send markers to template as JSON
	 * @internal
	 */
	public function handleMarkers()
	{
		$this->getPresenter()->sendResponse(new JsonResponse($this->markers));
	}
}
