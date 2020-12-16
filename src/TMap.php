<?php
/**
 * Copyright (c) 2015 Petr Olišar (http://olisar.eu)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Oli\GoogleAPI;


/**
 * Description of TMap
 *
 * @author Petr Olišar <petr.olisar@gmail.com>
 */
trait TMap
{

	/**
	 * @var IMapAPI
	 */
	protected IMapAPI $googleMapAPI;

	/**
	 * @var IMarkers
	 */
	protected IMarkers $googleMapMarkers;


	/**
	 * @param \Oli\GoogleAPI\IMapAPI $mapApi
	 */
	public function injectGoogleMapApi(IMapAPI $mapApi): void
  {
		$this->googleMapAPI = $mapApi;
	}


	/**
	 * @param \Oli\GoogleAPI\IMarkers $markers
	 */
	public function injectGoogleMapMarkers(IMarkers $markers): void
  {
		$this->googleMapMarkers = $markers;
	}

  /**
   * @return \Oli\GoogleAPI\MapAPI
   */
	public function createComponentMap(): MapAPI
  {
		$map = $this->googleMapAPI->create();
		$map->addMarkers($this->googleMapMarkers->create());
		return $map;
	}

}
