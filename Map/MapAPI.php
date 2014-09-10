<?php

namespace Oli\GoogleAPI;
use Nette\Application\UI\Control;
use Nette\Application\Responses\JsonResponse;

/**
 * Description of GoogleAPI
 *
 * @author Petr Olišar <petr.olisar@gmail.com>
 * @author Karel Koliš <karel@kolis.eu>
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
	/** @var MapAPI */
	private $type;
	/** @var Boolean */
	private $staticMap = false;
	/** @var String  */
	private $key;
	/** @var Array */
	private $markers = array();
	/** @var Boolean */
	private $bound;
	private $markerClusterer;
	
	
	public function setup($config)
	{
		$this->width = $config['width'];
		$this->height = $config['height'];
	}
	
	
	/**
	 * 
	 * @param array $coordinates (latitude, longitude) - center of the map
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
	
	
	public function setKey($key)
	{
		$this->key = $key;
		return $this;
	}
	
	
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
	
	
	public function setType($type)
	{
		if($type !== self::HYBRID || $type !== self::ROADMAP || $type !== self::SATELLITE || $type !== self::TERRAIN)
		{
			
		}
		$this->type = $type;
		return $this;
	}
	
	
	public function isMarkerClusterer($cluster)
	{
		if (!is_bool($cluster))
		{
			throw new \InvalidArgumentException("staticMap must be boolean, $cluster (".gettype($cluster).") was given");
		}
		
		$this->markerClusterer = $cluster;
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
	 * @return Integet Zoom
	 */
	public function getZoom()
	{
		return $this->zoom;
	}
	
	
	/**
	 * @return MapAPI Which map type will be show
	 */
	public function getType()
	{
		return $this->type;
	}
	
	
	/**
	 * @param Boolean $staticMap
	 * @return \Oli\GoogleAPI\MapAPI
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
	
	
	/**
	 * @param \Oli\GoogleAPI\Markers $markers
	 */
	public function addMarkers(Markers $markers)
	{
		$this->markers = $markers->getMarkers();
	}
	
	
	/**
	 * @param Boolean $bound Show all of markers
	 * @return \Oli\GoogleAPI\MapAPI
	 */
	public function fitBounds($bound = true)
	{
		$this->bound = $bound;
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
		$this->template->height = $this->height;
		$this->template->width = $this->width;
		$this->template->position = $this->coordinates;
		$this->template->zoom = $this->zoom;
		$this->template->type = $this->type;
		$this->template->key = $this->key;
		$this->template->bound = $this->bound;
		$this->template->cluster = $this->markerClusterer;
		if ($this->staticMap)
		{
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
	 */
	public function handleMarkers()
	{
		$this->getPresenter()->sendResponse(new JsonResponse($this->markers));
	}
}
