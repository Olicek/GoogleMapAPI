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


/**
 * Description of GoogleAPI
 *
 * @author Petr Olišar <petr.olisar@gmail.com>
 */
class MapAPI extends Control
{

	const ROADMAP = 'ROADMAP', SATELLITE = 'SATELLITE', HYBRID = 'HYBRID', TERRAIN = 'TERRAIN',
		BICYCLING = 'BICYCLING', DRIVING = 'DRIVING', TRANSIT = 'TRANSIT', WALKING = 'WALKING';
	
	/** @var double|string */
	private $width;

	/** @var double|string */
	private $height;

	/** @var array */
	private $coordinates;

	/** @var Integer */
	private $zoom;

	/** @var String */
	private $type;

	/** @var Boolean */
	private $staticMap = FALSE;

	/** @var Boolean */
	private $clickable = FALSE;

	/** @var String  */
	private $key;

	/** @var array */
	private $markers = array();

	/** @var boolean */
	private $bound;

	/** @var boolean */
	private $markerClusterer;


	/** @var array */
	private $clusterOptions;

	/** @var boolean */
	private $scrollable = FALSE;

	/**
	 *
	 * @var array
	 */
	private $waypoints;

	/**
	 *
	 * @var array
	 */
	private $direction = ['travelmode' => 'DRIVING'];
	
	
	/**
	 * @internal
	 * @param array $config
	 */
	public function setup(array $config)
	{
		$this->width = $config['width'];
		$this->height = $config['height'];
	}

	
	/**
	 *
	 * @param array $coordinates (latitude, longitude) - center of the map
	 * @return \Oli\GoogleAPI\MapAPI
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
	 * @return MapAPI
	 */
	public function setProportions($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
		return $this;
	}
	
	
	/**
	 *
	 * @param string $key
	 * @return \Oli\GoogleAPI\MapAPI
	 */
	public function setKey($key)
	{
		$this->key = $key;
		return $this;
	}
	
	
	/**
	 *
	 * @param int $zoom <0, 19>
	 * @return \Oli\GoogleAPI\MapAPI
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 */
	public function setZoom($zoom)
	{
		if (!is_int($zoom))
		{
			throw new \InvalidArgumentException("type must be integer, $zoom (".gettype($zoom).") was given");
		}
		
		if ($zoom < 0 || $zoom > 19)
		{
			throw new \LogicException('Zoom must be betwen <0, 19>.');
		}
		
		$this->zoom = (int) $zoom;
		return $this;
	}
	
	
	/**
	 *
	 * @param String $type
	 * @return \Oli\GoogleAPI\MapAPI
	 */
	public function setType($type)
	{
		if($type !== self::HYBRID && $type !== self::ROADMAP && $type !== self::SATELLITE &&
				$type !== self::TERRAIN)
		{
			throw new \InvalidArgumentException;
		}
		$this->type = $type;
		return $this;
	}
	
	
	public function setWaypoint($key, $waypoint)
	{
		if($key === 'waypoints')
		{
			$this->waypoints['waypoints'][] = $waypoint;
			
		} else
		{
			$this->waypoints[$key] = $waypoint;
		}
		return $this;
	}
	
	
	public function setDirection(array $direction)
	{
		$this->direction = $direction;
		if(!array_key_exists('travelmode', $this->direction))
		{
			$this->direction['travelmode'] = 'DRIVING';
		}
		return $this;
	}
	
	
	/**
	 * @return array Width and Height of the map.
	 */
	public function getProportions()
	{
		return array('width' => $this->width, 'height' => $this->height);
	}
	
	
	/**
	 * @return array Center of the map
	 */
	public function getCoordinates()
	{
		return $this->coordinates;
	}
	
	
	/**
	 * @return integer Zoom
	 */
	public function getZoom()
	{
		return $this->zoom;
	}
	
	
	/**
	 * @return String Which map type will be show
	 */
	public function getType()
	{
		return $this->type;
	}


	public function getKey()
	{
		return $this->key;
	}
	
	
	/**
	 *
	 * @param Boolean $staticMap
	 * @return \Oli\GoogleAPI\MapAPI
	 * @throws \InvalidArgumentException
	 */
	public function isStaticMap($staticMap = TRUE)
	{
		if (!is_bool($staticMap))
		{
			throw new \InvalidArgumentException("staticMap must be boolean, $staticMap (".gettype($staticMap).") was given");
		}
		
		$this->staticMap = $staticMap;
		return $this;
	}


	public function getIsStaticMap()
	{
		return $this->staticMap;
	}


	/**
	 *
	 * @param Boolean $clickable
	 * @return \Oli\GoogleAPI\MapAPI
	 * @throws \InvalidArgumentException
	 */
	public function isClickable($clickable = TRUE)
	{
		if (!$this->staticMap)
		{
			throw new \InvalidArgumentException("the 'clickable' option only applies to static maps");
		}

		if (!is_bool($clickable))
		{
			throw new \InvalidArgumentException("clickable must be boolean, $clickable (".gettype($clickable).") was given");
		}

		$this->clickable = $clickable;
		return $this;
	}


	public function getIsClicable()
	{
		return $this->clickable;
	}

	
	public function isScrollable($scrollable = TRUE)
	{
		if (!is_bool($scrollable))
		{
			throw new \InvalidArgumentException("staticMap must be boolean, $scrollable (".gettype($scrollable).") was given");
		}
		
		$this->scrollable = $scrollable;
		return $this;
	}


	public function getIsScrollable()
	{
		return $this->scrollable;
	}


	/**
	 *
	 * @param \Oli\GoogleAPI\Markers $markers
	 * @return \Oli\GoogleAPI\MapAPI
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
	* @see Nette\Application\Control#render()
	*/
	public function render()
	{
		if ($this->staticMap)
		{
			$this->template->height = $this->height;
			$this->template->width = $this->width;
			$this->template->zoom = $this->zoom;
			$this->template->position = $this->coordinates;
			$this->template->markers = $this->markers;
			$this->template->clickable = $this->clickable;
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
			$this->template->map = \Nette\Utils\Json::encode($map);
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
