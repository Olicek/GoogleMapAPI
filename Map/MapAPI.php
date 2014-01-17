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
	private $width = '100%';
	/** @var String */
	private $height = '100%';
	/** @var Array */
	private $position;
	/** @var Integer */
	private $zoom;
	/** @var MapAPI */
	private $type = MapAPI::ROADMAP;
	/** @var Boolean */
	private $staticMap = false;
	/** @var String  */
	private $key;
	/** @var Array */
	private $markers;
	/** @var Boolean */
	private $bound = false;
	
	
	/**
	 * 
	 * @param array $position (latitude, longitude) - center of the map
	 */
	public function __construct(array $position, $zoom = 8, $key = null)
	{
		$this->key = $key;
		
		if (!is_array($position))
		{
			throw new \InvalidArgumentException("type must be array, $position (".gettype($position).") was given");
		}
		
		$this->position = $position;
		
		if (!is_int($zoom))
		{
			throw new \InvalidArgumentException("type must be integer, $zoom (".gettype($zoom).") was given");
		}
		
		if ($zoom < 0 || $zoom > 19)
		{
			throw new \LogicException('Zoom must be betwen <0, 19>.');
		}
		$this->zoom = (int) $zoom;
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
	 * @return array Width and Height of the map.
	 */
	public function getProportions()
	{
		return array('width' => $this->width, 'height' => $this->height);
	}
	
	
	/**
	 * @return array Center of the map
	 */
	public function getPosition()
	{
		return $this->position;
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
		$this->template->position = $this->position;
		$this->template->zoom = $this->zoom;
		$this->template->type = $this->type;
		$this->template->key = $this->key;
		$this->template->bound = $this->bound;
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
