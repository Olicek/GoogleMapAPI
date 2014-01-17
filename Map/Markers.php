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
		$this->markers[$key]['icon'] = is_null($this->iconDefaultPath) ?: $this->iconDefaultPath .'/'. $icon;
	}
	
	
	/**
	 * 
	 * @param String $defaultPath
	 * @return \Oli\GoogleAPI\Markers
	 */
	public function setDefaultIconPath($defaultPath)
	{
		$this->iconDefaultPath = $defaultPath;
		return $this;
	}
	
	
	/**
	 * 
	 * @param String $color
	 * @return \Oli\GoogleAPI\Markers
	 */
	public function color($color)
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
