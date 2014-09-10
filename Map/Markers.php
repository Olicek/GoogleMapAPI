<?php

namespace Oli\GoogleAPI;

/**
 * Description of Markers
 *
 * @author Petr Olišar <petr.olisar@gmail.com>
 * @author Karel Koliš <karel@kolis.eu>
 */
class Markers extends \Nette\Object
{
	const DROP = 'DROP', BOUNCE = 'BOUNCE';
	
	/** @var Array */
	private $markers = array();
	/** @var String */
	private $iconDefaultPath;
	
	
	public function addMarkers(array $markers)
	{
		if(count($markers))
		{
			foreach($markers as $marker)
			{
				if(!array_key_exists('coordinates', $marker))
				{
					throw new \Nette\InvalidArgumentException('Coordinates must be set in every marker');
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
					$this->setIcon($marker['icon']);
				}
				
				if(array_key_exists('color', $marker))
				{
					$this->setColor($marker['color']);
				}
			}
		}
	}
	
	
	/**
	* @param array $position
	* @param boolean $animation
	* @param String $title
	* @return Markers
	*/
	public function addMarker(array $position, $animation = false, $title = null)
	{
		if (!is_string($animation) && !is_bool($animation))
		{
			throw new \InvalidArgumentException("Animation must be string or boolean, $animation (".gettype($animation).") was given");
		}
		if (!is_string($title) && $title != null)
		{
			throw new \InvalidArgumentException("Title must be string or null, $title (".gettype($title).") was given");
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
	 * @return Array
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
	 * @param String $message
	 * @param Boolean $autoOpen
	 * @return Markers
	 */
	public function setMessage($message, $autoOpen = false)
	{
		end($this->markers);         // move the internal pointer to the end of the array
		$key = key($this->markers);
		$this->markers[$key]['message'] = $message;
		$this->markers[$key]['autoOpen'] = $autoOpen;
		return $this;
	}
	
	
	/**
	 * 
	 * @param String $icon
	 */
	public function setIcon($icon)
	{
		end($this->markers);         // move the internal pointer to the end of the array
		$key = key($this->markers);
		$this->markers[$key]['icon'] = is_null($this->iconDefaultPath) ? $icon : $this->iconDefaultPath . $icon;
	}
	
	
	/**
	 * 
	 * @param String $defaultPath
	 * @return \Oli\GoogleAPI\Markers
	 */
	public function setDefaultIconPath($defaultPath)
	{
		if(!is_null($defaultPath) && 
			!\Nette\Utils\Strings::endsWith($defaultPath, '/') && 
			!\Nette\Utils\Strings::endsWith($defaultPath, '\\'))
		{
			$defaultPath .= DIRECTORY_SEPARATOR;
		}
		$this->iconDefaultPath = $defaultPath;
		return $this;
	}
	
	
	/**
	 * 
	 * @param String $color Color can be 24-bit color or: black, brown, green, purple, yellow, blue, gray, orange, red, white
	 * @return \Oli\GoogleAPI\Markers
	 */
	public function setColor($color)
	{
		$allowed = array('black', 'brown', 'green', 'purple', 'yellow', 'blue', 'gray', 'orange', 'red', 'white');
		if (!in_array($color, $allowed))
		{
			if (!\Nette\Utils\Strings::match($color, '~^0x[a-f0-9]{6}$~i'))
			{
				throw new \Nette\InvalidArgumentException('Color must be 24-bit color or from the allowed list.');
			}
		}
		end($this->markers);         // move the internal pointer to the end of the array
		$key = key($this->markers);
		$this->markers[$key]['color'] = $color;
		return $this;
	}
}
