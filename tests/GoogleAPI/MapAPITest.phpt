<?php
/**
 * Created by PhpStorm.
 * User: petr
 * Date: 8.11.15
 * Time: 23:06
 */

namespace Oli\GoogleAPI;


use Nette\Utils\Html;
use Tester\TestCase;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class MapAPITest extends TestCase
{

	/**
	 * @var MapAPI
	 */
	private $map;


	protected function setUp()
	{
		parent::setUp();
		$this->map = new MapAPI();
	}


	public function testSetup()
	{
		$this->map->setup(['width' => 123, 'height' => 456]);
//		Assert::contains('width', $this->map->getProportions());
		Assert::type('array', $this->map->getProportions());
		Assert::same(123, $this->map->getProportions()['width']);
		Assert::same(456, $this->map->getProportions()['height']);
	}


	public function testCoordinates()
	{
		$this->map->setCoordinates([123, 789]);
		Assert::same([123, 789], $this->map->getCoordinates());

		$this->map->setCoordinates(['width' => 753, 'height' => 159]);
		Assert::same([753, 159], $this->map->getCoordinates());

		$this->map->setCoordinates([]);
		Assert::same([NULL, NULL], $this->map->getCoordinates());

	}


	public function testProportions()
	{
		$this->map->setProportions(150, 300);
		Assert::same(['width' => 150, 'height' => 300], $this->map->getProportions());

		$this->map->setProportions('150px', '300px');
		Assert::same(['width' => '150px', 'height' => '300px'], $this->map->getProportions());
	}


	public function testKey()
	{
		$this->map->setKey('my-secret-key');
		Assert::same('my-secret-key', $this->map->getKey());
		Assert::type('string', $this->map->getKey());
	}


	public function testZoom()
	{
		$this->map->setZoom(5);
		Assert::type('integer', $this->map->getZoom());
		Assert::same(5, $this->map->getZoom());

		Assert::exception(function () {
			$this->map->setZoom(-1);
		}, LogicException::class);

		Assert::exception(function () {
			$this->map->setZoom(20);
		}, LogicException::class);

		Assert::exception(function () {
			$this->map->setZoom('foo');
		}, InvalidArgumentException::class, 'type must be integer, foo (string) was given');
	}


	public function testType()
	{
		$this->map->setType(MapAPI::HYBRID);
		Assert::same('HYBRID', $this->map->getType());

		$this->map->setType(MapAPI::ROADMAP);
		Assert::same('ROADMAP', $this->map->getType());

		$this->map->setType(MapAPI::SATELLITE);
		Assert::same('SATELLITE', $this->map->getType());

		$this->map->setType(MapAPI::TERRAIN);
		Assert::same('TERRAIN', $this->map->getType());

		Assert::exception(function () {
			$this->map->setType('foo');
		}, InvalidArgumentException::class);
	}


	public function testStaticMap()
	{
		Assert::false($this->map->getIsStaticMap());
		$this->map->isStaticMap();
		Assert::true($this->map->getIsStaticMap());

		$this->map->isStaticMap(TRUE);
		Assert::true($this->map->getIsStaticMap());

		$this->map->isStaticMap(FALSE);
		Assert::false($this->map->getIsStaticMap());

		Assert::exception(function () {
			$this->map->isStaticMap('foo');
		}, InvalidArgumentException::class, 'staticMap must be boolean, foo (string) was given');
	}


	public function testClicable()
	{
		Assert::exception(function () {
			$this->map->isStaticMap(FALSE)->isClickable();
		}, InvalidArgumentException::class, "the 'clickable' option only applies to static map");

		$this->map->setCoordinates(array(50.250718,14.583435))->isStaticMap();
		Assert::false($this->map->getIsClicable());


		$this->map->isClickable(FALSE);
		Assert::false($this->map->getIsClicable());

		$this->map->isClickable();
		Assert::same('<a href="https://maps.google.com/maps/place/50.250718,14.583435/">',
			$this->map->getIsClicable());

		$this->map->isClickable(TRUE);
		Assert::same('<a href="https://maps.google.com/maps/place/50.250718,14.583435/">',
			$this->map->getIsClicable());


		$function = function ($url) {
			return Html::el('a', ['class' => 'btn btn-default'])->href($url);
		};
		$this->map->isClickable($function);
		Assert::same($function, $this->map->getIsClicable());

		Assert::exception(function () {
			$this->map->isClickable('foo');
		}, InvalidArgumentException::class, 'clickable must be boolean or callable, foo (string) was given');
	}


	public function testScrollable()
	{
		Assert::false($this->map->getIsScrollable());

		$this->map->isScrollable();
		Assert::true($this->map->getIsScrollable());

		$this->map->isScrollable(TRUE);
		Assert::true($this->map->getIsScrollable());

		$this->map->isScrollable(FALSE);
		Assert::false($this->map->getIsScrollable());

		Assert::exception(function () {
			$this->map->isScrollable('foo');
		}, InvalidArgumentException::class, 'staticMap must be boolean, foo (string) was given');
	}


	public function testDirection()
	{
	    Assert::same(['travelmode' => 'DRIVING'], $this->map->getDirection());

		$this->map->setDirection([
			'travelmode' => MapAPI::BICYCLING,
			'avoidHighways' => TRUE,
			'avoidTolls' => TRUE
		]);
		Assert::same([
			'travelmode' => 'BICYCLING',
			'avoidHighways' => TRUE,
			'avoidTolls' => TRUE
		], $this->map->getDirection());

		$this->map->setDirection([
			'avoidHighways' => TRUE,
			'avoidTolls' => TRUE
		]);
		Assert::same([
			'avoidHighways' => TRUE,
			'avoidTolls' => TRUE,
			'travelmode' => 'DRIVING'
		], $this->map->getDirection());

		Assert::exception(function () {
			$this->map->setDirection([
				'travelmode' => 'FOO',
				'avoidHighways' => TRUE,
				'avoidTolls' => TRUE
			]);
		}, InvalidArgumentException::class);
	}


	public function testWaypoints()
	{
	    $this->map->setWaypoint('start', [
			'position' => [11, 12],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE
		]);
		Assert::same(['start' => [
			'position' => [11, 12],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE
		]], $this->map->getWaypoints());

		$this->map->setWaypoint('waypoint', [
			'position' => [11, 12],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => FALSE
		]);
		Assert::same([
			'start' => [
				'position' => [11, 12],
				'title' => NULL,
				'animation' => FALSE,
				'visible' => TRUE
			],
			'waypoints' => [
				[
					'position' => [11, 12],
					'title' => NULL,
					'animation' => FALSE,
					'visible' => FALSE
				]
			],
		], $this->map->getWaypoints());

		$this->map->setWaypoint('waypoint', [
			'position' => [10, 10],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => FALSE
		]);
		Assert::same([
			'start' => [
				'position' => [11, 12],
				'title' => NULL,
				'animation' => FALSE,
				'visible' => TRUE
			],
			'waypoints' => [
				[
					'position' => [11, 12],
					'title' => NULL,
					'animation' => FALSE,
					'visible' => FALSE
				],
				[
					'position' => [10, 10],
					'title' => NULL,
					'animation' => FALSE,
					'visible' => FALSE
				]
			],
		], $this->map->getWaypoints());

		$this->map->setWaypoint('end', [
			'position' => [20, 20],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => FALSE
		]);
		Assert::same([
			'start' => [
				'position' => [11, 12],
				'title' => NULL,
				'animation' => FALSE,
				'visible' => TRUE
			],
			'waypoints' => [
				[
					'position' => [11, 12],
					'title' => NULL,
					'animation' => FALSE,
					'visible' => FALSE
				],
				[
					'position' => [10, 10],
					'title' => NULL,
					'animation' => FALSE,
					'visible' => FALSE
				]
			],
			'end' => [
				'position' => [20, 20],
				'title' => NULL,
				'animation' => FALSE,
				'visible' => FALSE
			]
		], $this->map->getWaypoints());

		Assert::exception(function () {
			$this->map->setWaypoint('foo', ['position' => [20, 20]]);
		}, InvalidArgumentException::class, 'First argument must be "start|end|waypoint", foo was given');
	}

}

$test = new MapAPITest();
$test->run();