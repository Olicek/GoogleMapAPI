<?php

/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\GoogleAPI\Marker;


/**
 * Description of Icon
 *
 * @author petr
 */
class Icon extends \Nette\Object
{
	
	/**
	 *
	 * @var string
	 */
	private $url;
	
	/**
	 *
	 * @var array
	 */
	private $anchor;
	
	/**
	 *
	 * @var array
	 */
	private $size;
	
	/**
	 *
	 * @var array
	 */
	private $origin;
	
	
	public function __construct($url)
	{
		$this->url = $url;
	}
	
	
	public function getUrl()
	{
		return $this->url;
	}


	public function getAnchor()
	{
		return $this->anchor;
	}


	public function getSize()
	{
		return $this->size;
	}


	public function getOrigin()
	{
		return $this->origin;
	}


	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}


	public function setAnchor(array $anchor)
	{
		$this->anchor = $anchor;
		return $this;
	}


	public function setSize(array $size)
	{
		$this->size = $size;
		return $this;
	}


	public function setOrigin(array $origin)
	{
		$this->origin = $origin;
		return $this;
	}
	
	
	public function getArray()
	{
		return [
			'url' => $this->getUrl(),
			'size' => $this->getSize(),
			'origin' => $this->getOrigin(),
			'anchor' => $this->getAnchor()
		];
	}
	
}
