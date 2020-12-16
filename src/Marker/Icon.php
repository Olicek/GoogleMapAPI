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
class Icon
{

	/**
	 * @var string
	 */
	private string $url;

	/**
	 * @var array|null
	 */
	private ?array $anchor = null;

	/**
	 * @var array|null
	 */
	private ?array $size = null;

	/**
	 * @var array|null
	 */
	private ?array $origin = null;


	/**
	 * Icon constructor.
	 * @param string $url
	 */
	public function __construct(string $url)
	{
		$this->url = $url;
	}

  /**
   * @return string
   */
	public function getUrl(): string
  {
		return $this->url;
	}


	/**
	 * @return array|null
	 */
	public function getAnchor(): ?array
  {
		return $this->anchor;
	}


	/**
	 * @return array|null
	 */
	public function getSize(): ?array
  {
		return $this->size;
	}


	/**
	 * @return array|null
	 */
	public function getOrigin(): ?array
  {
		return $this->origin;
	}


	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl(string $url): Icon
  {
		$this->url = $url;
		return $this;
	}


	/**
	 * @param array $anchor
	 * @return $this
	 */
	public function setAnchor(array $anchor): Icon
  {
		$this->anchor = $anchor;
		return $this;
	}


	/**
	 * @param array $size
	 * @return $this
	 */
	public function setSize(array $size): Icon
  {
		$this->size = $size;
		return $this;
	}


	/**
	 * @param array $origin
	 * @return $this
	 */
	public function setOrigin(array $origin): Icon
  {
		$this->origin = $origin;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getArray(): array
  {
		return [
			'url' => $this->getUrl(),
			'size' => $this->getSize(),
			'origin' => $this->getOrigin(),
			'anchor' => $this->getAnchor()
		];
	}

}
