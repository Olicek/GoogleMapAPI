<?php

namespace Oli\GoogleAPI;


/**
 * Description of TMap
 *
 * @author petr
 */
trait TMap
{
	protected $googleMapAPI;
	protected $googleMapMarkers;


	public function injectGoogleMapAPI(IMapAPI $mapApi)
	{
		$this->googleMapAPI = $mapApi;
	}
	
	
	public function injectGoogleMapMarkers(IMarkers $markers)
	{
		$this->googleMapMarkers = $markers;
	}
	
	
	
	public function createComponentMap()
	{
		$map = $this->googleMapAPI->create();
		$map->addMarkers($this->googleMapMarkers->create());
		return $map;
	}
}
