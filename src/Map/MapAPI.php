<?php
/**
 * Copyright (c) 2014 Petr Olišar (http://olisar.eu)
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
	const ROADMAP = 'ROADMAP', SATELLITE = 'SATELLITE', HYBRID = 'HYBRID', TERRAIN = 'TERRAIN';
	
	/** @var String */
	private $width;
	/** @var String */
	private $height;
	/** @var Array */
	private $coordinates;
	/** @var Integer */
	private $zoom;
	/** @var String */
	private $type;
	/** @var Boolean */
	private $staticMap = false;
	/** @var String  */
	private $key;
	/** @var Array */
	private $markers = array();
	/** @var boolean */
	private $bound;
	/** @var boolean */
	private $markerClusterer;
	/** @var boolean */		
	private $scrollable;
	
	
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
	 * @throws \InvalidArgumentException
	 */
	public function setCoordinates(array $coordinates)
	{
		if (!is_array($coordinates))
		{
			throw new \InvalidArgumentException("type must be array, $coordinates (".gettype($coordinates).") was given");
		}
		
		if(!count($coordinates))
		{
			$this->coordinates = array(null, null);
		} else
		{
			$this->coordinates = array_values($coordinates);
		}
		
		return $this;
	}
	
	
	/**
	 * @param double $width
	 * @param double $height
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
		if($type !== self::HYBRID || $type !== self::ROADMAP || $type !== self::SATELLITE || $type !== self::TERRAIN)
		{
			
		}
		$this->type = $type;
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
	
	
	/**
	 * 
	 * @param Boolean $staticMap
	 * @return \Oli\GoogleAPI\MapAPI
	 * @throws \InvalidArgumentException
	 */
	public function isStaticMap($staticMap = true)
	{
		if (!is_bool($staticMap))
		{
			throw new \InvalidArgumentException("staticMap must be boolean, $staticMap (".gettype($staticMap).") was given");
		}
		
		$this->staticMap = $staticMap;
		return $this;
	}
	
	
	public function isScrollable($scrollable = true)
	{
		if (!is_bool($scrollable))
		{
			throw new \InvalidArgumentException("staticMap must be boolean, $scrollable (".gettype($scrollable).") was given");
		}
		
		$this->scrollable = $scrollable;
		return $this;
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
		$this->renderJS();
		$this->renderHTML();
	}
	
	
	public function renderJS()
	{
		$this->template->height = $this->height;
		$this->template->width = $this->width;
		$this->template->position = $this->coordinates;
		$this->template->zoom = $this->zoom;
		$this->template->type = $this->type;
		$this->template->key = $this->key;
		$this->template->scrollable = $this->scrollable;
		$this->template->bound = $this->bound;
		$this->template->cluster = $this->markerClusterer;
		$this->template->setFile(dirname(__FILE__) . '/js.latte');
		$this->template->render();
	}
	
	
	public function renderHTML()
	{
		if ($this->staticMap)
		{
			$this->template->height = $this->height;
			$this->template->width = $this->width;
			$this->template->zoom = $this->zoom;
			$this->template->position = $this->coordinates;
			$this->template->markers = $this->markers;
			$this->template->setFile(dirname(__FILE__) . '/static.latte');
		} else
		{
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
