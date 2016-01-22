<?php
/**
 * Created by PhpStorm.
 * User: petr
 * Date: 8.11.15
 * Time: 23:06
 */

namespace Oli\GoogleAPI;


use Oli\GoogleAPI\Marker\Icon;
use Tester\TestCase;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class MarkersTest extends TestCase
{

	/**
	 * @var Markers
	 */
	private $markers;


	protected function setUp()
	{
		parent::setUp();
		$this->markers = new Markers();
	}


	public function testIsMarkerClusterer()
	{
		Assert::false($this->markers->getMarkerClusterer());

		$this->markers->isMarkerClusterer();
		Assert::true($this->markers->getMarkerClusterer());

		$this->markers->isMarkerClusterer(FALSE);
		Assert::false($this->markers->getMarkerClusterer());

		$this->markers->isMarkerClusterer(TRUE);
		Assert::true($this->markers->getMarkerClusterer());

		Assert::exception(function () {
			$this->markers->isMarkerClusterer('foo');
		}, InvalidArgumentException::class, 'cluster must be boolean, foo (string) was given');
	}


	public function testDefaultIconPath()
	{
		$this->markers->setDefaultIconPath('foo/bar');
		Assert::same('foo/bar/', $this->markers->getDefaultIconPath());

		$this->markers->setDefaultIconPath('foo/bar/');
		Assert::same('foo/bar/', $this->markers->getDefaultIconPath());
	}


	public function testFitBounds()
	{
		Assert::false($this->markers->getBound());

		$this->markers->fitBounds();
		Assert::true($this->markers->getBound());

		$this->markers->fitBounds(FALSE);
		Assert::false($this->markers->getBound());

		$this->markers->fitBounds(TRUE);
		Assert::true($this->markers->getBound());

		Assert::exception(function () {
			$this->markers->fitBounds('foo');
		}, InvalidArgumentException::class, 'fitBounds must be boolean, foo (string) was given');
	}


	public function testAddMarker()
	{
		$this->markers->addMarker([11, 12]);
		Assert::equal([
			'position' => [11, 12],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE
		], $this->markers->getMarker());

		$this->markers->addMarker([21, 22], Markers::BOUNCE);
		Assert::equal([
				'position' => [21, 22],
				'title' => NULL,
				'animation' => 'BOUNCE',
				'visible' => TRUE
		], $this->markers->getMarker());

		$this->markers->addMarker([31, 32], Markers::DROP);
		Assert::equal([
				'position' => [31, 32],
				'title' => NULL,
				'animation' => 'DROP',
				'visible' => TRUE
		], $this->markers->getMarker());

		$this->markers->addMarker([41, 42], FALSE, 'foo');
		Assert::equal([
				'position' => [41, 42],
				'title' => 'foo',
				'animation' => FALSE,
				'visible' => TRUE
		], $this->markers->getMarker());

		Assert::exception(function () {
			$this->markers->addMarker([], 123);
		}, InvalidArgumentException::class);

		Assert::exception(function () {
			$this->markers->addMarker([], FALSE, 123);
		}, InvalidArgumentException::class);

		Assert::equal([
			[
				'position' => [11, 12],
				'title' => NULL,
				'animation' => FALSE,
				'visible' => TRUE,
			],
			[
				'position' => [21, 22],
				'title' => NULL,
				'animation' => 'BOUNCE',
				'visible' => TRUE,
			],
			[
				'position' => [31, 32],
				'title' => NULL,
				'animation' => 'DROP',
				'visible' => TRUE,
			],
			[
				'position' => [41, 42],
				'title' => 'foo',
				'animation' => FALSE,
				'visible' => TRUE,
			],
		], $this->markers->getMarkers());

		$this->markers->deleteMarkers();
		Assert::same([], $this->markers->getMarkers());
		Assert::false($this->markers->getMarker());
	}


	public function testSetMessage()
	{
		Assert::exception(function () {
			$this->markers->setMessage('foo');
		}, LogicException::class, 'setMessage must be called after addMarker()');

		Assert::false($this->markers->getMarker());

		$this->markers->addMarker([11, 12]);
		$this->markers->setMessage('foo');
		Assert::same([
			'position' => [11, 12],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE,
			'message' => 'foo',
			'autoOpen' => FALSE,
		], $this->markers->getMarker());

		$this->markers->addMarker([21, 22], Markers::BOUNCE)
			->setMessage('bar', TRUE);
		Assert::same([
			'position' => [21, 22],
			'title' => NULL,
			'animation' => 'BOUNCE',
			'visible' => TRUE,
			'message' => 'bar',
			'autoOpen' => TRUE
		], $this->markers->getMarker());

		$this->markers->addMarker([31, 32], Markers::DROP)
			->setMessage('foo-bar', FALSE);
		Assert::same([
			'position' => [31, 32],
			'title' => NULL,
			'animation' => 'DROP',
			'visible' => TRUE,
			'message' => 'foo-bar',
			'autoOpen' => FALSE
		], $this->markers->getMarker());
	}


	public function testSetClusterOptions()
	{
		$marker = $this->markers->addMarker([11, 12]);
		Assert::same([], $this->markers->getClusterOptions());

		$marker->setClusterOptions([1, 2, 'foo']);
		Assert::same([1, 2, 'foo'], $this->markers->getClusterOptions());
	}


	public function testSetColor()
	{
		Assert::exception(function () {
			$this->markers->setColor('foo');
		}, InvalidArgumentException::class, 'Color must be 24-bit color or from the allowed list.');

		Assert::exception(function () {
			$this->markers->setColor('green');
		}, InvalidArgumentException::class, 'setColor must be called after addMarker()');

		$this->markers->addMarker([11, 12])
			->setColor('purple');
		Assert::same([
			'position' => [11, 12],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE,
			'color' => 'purple',
		], $this->markers->getMarker());
	}


	public function testSetIcon()
	{
		Assert::exception(function () {
			$this->markers->setIcon('foo');
		}, LogicException::class, 'setIcon must be called after addMarker()');

		$this->markers->addMarker([11, 12])
			->setIcon('icon.png');
		Assert::same([
			'position' => [11, 12],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE,
			'icon' => 'icon.png',
		], $this->markers->getMarker());

		$this->markers->setDefaultIconPath('default/path');

		$this->markers->addMarker([21, 22])
			->setIcon('icon.png');
		Assert::same([
			'position' => [21, 22],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE,
			'icon' => 'default/path/icon.png',
		], $this->markers->getMarker());

		$icon = new Icon('icon.png');
		$this->markers->addMarker([31, 32])
			->setIcon($icon);
		Assert::same([
			'position' => [31, 32],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE,
			'icon' => [
				'url' => 'default/path/icon.png',
				'size' => NULL,
				'origin' => NULL,
				'anchor' => NULL
			],
		], $this->markers->getMarker());

		$this->markers->setDefaultIconPath(NULL);

		$icon = (new Icon('icon.png'))->setSize([20, 20]);
		$this->markers->addMarker([31, 32])
			->setIcon($icon);
		Assert::same([
			'position' => [31, 32],
			'title' => NULL,
			'animation' => FALSE,
			'visible' => TRUE,
			'icon' => [
				'url' => 'icon.png',
				'size' => [20, 20],
				'origin' => NULL,
				'anchor' => NULL
			],
		], $this->markers->getMarker());
	}

}

$test = new MarkersTest();
$test->run();