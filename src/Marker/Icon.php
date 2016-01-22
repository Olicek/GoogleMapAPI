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
	 * @var string
	 */
	private $url;

	/**
	 * @var array|null
	 */
	private $anchor;

	/**
	 * @var array|null
	 */
	private $size;

	/**
	 * @var array|null
	 */
	private $origin;


	/**
	 * Icon constructor.
	 * @param string $url
	 */
	public function __construct($url)
	{
		$this->url = $url;
	}


	/**
	 * @return null
	 */
	public function getUrl()
	{
		return $this->url;
	}


	/**
	 * @return array|null
	 */
	public function getAnchor()
	{
		return $this->anchor;
	}


	/**
	 * @return array|null
	 */
	public function getSize()
	{
		return $this->size;
	}


	/**
	 * @return array|null
	 */
	public function getOrigin()
	{
		return $this->origin;
	}


	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}


	/**
	 * @param array $anchor
	 * @return $this
	 */
	public function setAnchor(array $anchor)
	{
		$this->anchor = $anchor;
		return $this;
	}


	/**
	 * @param array $size
	 * @return $this
	 */
	public function setSize(array $size)
	{
		$this->size = $size;
		return $this;
	}


	/**
	 * @param array $origin
	 * @return $this
	 */
	public function setOrigin(array $origin)
	{
		$this->origin = $origin;
		return $this;
	}


	/**
	 * @return array
	 */
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
