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
	protected $googleMapAPI;

	/**
	 * @var IMarkers
	 */
	protected $googleMapMarkers;


	/**
	 * @param \Oli\GoogleAPI\IMapAPI $mapApi
	 */
	public function injectGoogleMapApi(IMapAPI $mapApi)
	{
		$this->googleMapAPI = $mapApi;
	}
	
	
	/**
	 * @param \Oli\GoogleAPI\IMarkers $markers
	 */
	public function injectGoogleMapMarkers(IMarkers $markers)
	{
		$this->googleMapMarkers = $markers;
	}
	
	
	/**
	 * @return MapAPI
	 */
	public function createComponentMap()
	{
		$map = $this->googleMapAPI->create();
		$map->addMarkers($this->googleMapMarkers->create());
		return $map;
	}
	
}
