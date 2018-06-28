<?php
/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\GoogleAPI;

use Nette\SmartObject;
use Nette\Utils\Strings;
use Oli\GoogleAPI\Marker\Icon;


/**
 * Class Markers
 * @package Oli\GoogleAPI
 * @see https://developers.google.com/maps/documentation/javascript/markers#complex_icons
 */
class Markers
{

	use SmartObject;
	const DROP = 'DROP', BOUNCE = 'BOUNCE';

	/**
	 * @var array
	 */
	private $markers = array();

	/**
	 * @var
	 */
	private $iconDefaultPath;

	/**
	 * @var bool
	 */
	private $bound = FALSE;

	/**
	 * @var bool
	 */
	private $markerClusterer = FALSE;

	/**
	 * @var array
	 */
	private $clusterOptions = array();


	/**
	 * @param array $markers
	 */
	public function addMarkers(array $markers)
	{
		if(count($markers))
		{
			foreach($markers as $marker)
			{
				$this->createMarker($marker);
			}
		}
	}


	/**
	 * @param array $position
	 * @param bool $animation
	 * @param null|string $title
	 * @return $this
	 */
	public function addMarker(array $position, $animation = false, $title = null)
	{
		if (!is_string($animation) && !is_bool($animation))
		{
			throw new InvalidArgumentException("Animation must be string or boolean, $animation (" .
					gettype($animation) . ") was given");
		}
		if (!is_string($title) && $title != null)
		{
			throw new InvalidArgumentException("Title must be string or null, $title (".gettype($title).") was given");
		}
		$this->markers[] = array(
			'position' => $position,
			'title' => $title,
			'animation' => $animation,
			'visible' => true
		);
		return $this;
	}


	/**
	 * @return array
	 */
	public function getMarker()
	{
		return end($this->markers);
	}


	/**
	 * @return array
	 */
	public function getMarkers()
	{
		return $this->markers;
	}


	public function deleteMarkers()
	{
		$this->markers = array();
	}


	/**
	 * @param $message
	 * @param bool $autoOpen
	 * @return $this
	 * @throws LogicException
	 */
	public function setMessage($message, $autoOpen = false)
	{
		if (!count($this->markers))
		{
			throw new LogicException("setMessage must be called after addMarker()");
		}
		end($this->markers);         // move the internal pointer to the end of the array
		$key = key($this->markers);
		$this->markers[$key]['message'] = $message;
		$this->markers[$key]['autoOpen'] = $autoOpen;
		return $this;
	}


	/**
	 * @param bool $cluster
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function isMarkerClusterer($cluster = true)
	{
		if (!is_bool($cluster))
		{
			throw new InvalidArgumentException("cluster must be boolean, $cluster (".gettype($cluster).") was given");
		}
		
		$this->markerClusterer = $cluster;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getMarkerClusterer()
	{
		return $this->markerClusterer;
	}


	/**
	 * @param array $options
	 * @return $this
	 */
	public function setClusterOptions($options = array())
	{
		$this->clusterOptions = $options;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getClusterOptions()
	{
		return $this->clusterOptions;
	}


	/**
	 * @param bool $bound Show all of markers
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function fitBounds($bound = true)
	{
		if (!is_bool($bound))
		{
			throw new InvalidArgumentException("fitBounds must be boolean, $bound (".gettype($bound).") was given");
		}

		$this->bound = $bound;
		return $this;
	}
	
	
	/**
	 * @return Boolean
	 */
	public function getBound()
	{
		return $this->bound;
	}


	/**
	 * @param string|Icon $icon
	 * @return $this
	 * @throws LogicException
	 */
	public function setIcon($icon)
	{
		if (!count($this->markers))
		{
			throw new LogicException("setIcon must be called after addMarker()");
		}
		end($this->markers);         // move the internal pointer to the end of the array
		$key = key($this->markers);
		if($icon instanceof Marker\Icon)
		{
			$icon->setUrl(is_null($this->iconDefaultPath) ? $icon->getUrl() : $this->iconDefaultPath . $icon->getUrl());
			$this->markers[$key]['icon'] = $icon->getArray();
			
		} else
		{
			$this->markers[$key]['icon'] = is_null($this->iconDefaultPath) ? $icon : $this->iconDefaultPath . $icon;
		}

		return $this;
	}


	/**
	 * @param string $defaultPath
	 * @return $this
	 */
	public function setDefaultIconPath($defaultPath)
	{
		if(!is_null($defaultPath) &&
			!Strings::endsWith($defaultPath, '/') &&
			!Strings::endsWith($defaultPath, '\\'))
		{
			$defaultPath .= DIRECTORY_SEPARATOR;
		}
		$this->iconDefaultPath = $defaultPath;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getDefaultIconPath()
	{
		return $this->iconDefaultPath;
	}


	/**
	 * @param string $color Color can be 24-bit color or: green, purple, yellow, blue, orange, red
	 * @return $this
	 */
	public function setColor($color)
	{
		$allowed = array('green', 'purple', 'yellow', 'blue', 'orange', 'red');
		if (!in_array($color, $allowed) && !Strings::match($color, '~^0x[a-f0-9]{6}$~i'))
		{
			throw new InvalidArgumentException('Color must be 24-bit color or from the allowed list.');
		}

		if (!count($this->markers))
		{
			throw new InvalidArgumentException("setColor must be called after addMarker()");
		}
		end($this->markers);         // move the internal pointer to the end of the array
		$key = key($this->markers);
		$this->markers[$key]['color'] = $color;
		return $this;
	}


	/**
	 * @param array $marker
	 */
	private function createMarker(array $marker)
	{
		if(!array_key_exists('coordinates', $marker))
		{
			throw new InvalidArgumentException('Coordinates must be set in every marker');
		}

		$this->addMarker(array_values($marker['coordinates']),
				isset($marker['animation']) ? $marker['animation'] : false,
				isset($marker['title']) ? $marker['title'] : null);

		if(array_key_exists('message', $marker))
		{
			if(is_array($marker['message']))
			{
				$message = array_values($marker['message']);
				$this->setMessage($message[0], $message[1]);
			} else
			{
				$this->setMessage($marker['message']);
			}
		}

		if(array_key_exists('icon', $marker))
		{
			if(is_array($marker['icon']))
			{
				$icon = new Marker\Icon($marker['icon']['url']);

				if(array_key_exists('size', $marker['icon']))
				{
					$icon->setSize($marker['icon']['size']);
				}

				if(array_key_exists('anchor', $marker['icon']))
				{
					$icon->setAnchor($marker['icon']['anchor']);
				}

				if(array_key_exists('origin', $marker['icon']))
				{
					$icon->setOrigin($marker['icon']['origin']);
				}
				$this->setIcon($icon);

			} else
			{
				$this->setIcon($marker['icon']);
			}
		}

		if(array_key_exists('color', $marker))
		{
			$this->setColor($marker['color']);
		}
	}
}
