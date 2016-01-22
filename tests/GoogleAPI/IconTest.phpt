<?php
/**
 * Created by PhpStorm.
 * User: petr
 * Date: 22.1.16
 * Time: 10:02
 */

namespace Oli\GoogleAPI;


use Oli\GoogleAPI\Marker\Icon;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';


class IconTest extends TestCase
{

	/**
	 * @var Icon
	 */
	private $icon;


	protected function setUp()
	{
		parent::setUp();
		$this->icon = new Icon('http://localhost');
	}


	public function testUrl()
	{
		Assert::same('http://localhost', $this->icon->getUrl());
		$this->icon->setUrl('http://another.url');
		Assert::same('http://another.url', $this->icon->getUrl());
	}


	public function testAnchor()
	{
	    Assert::null($this->icon->getAnchor());
		$this->icon->setAnchor([0, 32]);
		Assert::same([0, 32], $this->icon->getAnchor());
	}


	public function testSize()
	{
	    Assert::null($this->icon->getSize());
		$this->icon->setSize([0, 32]);
		Assert::same([0, 32], $this->icon->getSize());
	}


	public function testOrigin()
	{
	    Assert::null($this->icon->getOrigin());
		$this->icon->setOrigin([0, 32]);
		Assert::same([0, 32], $this->icon->getOrigin());
	}


	public function testArray()
	{
	    Assert::same([
			'url' => 'http://localhost',
			'size' => NULL,
			'origin' => NULL,
			'anchor' => NULL
		], $this->icon->getArray());

		$this->icon->setUrl('http://another.url')
			->setAnchor([0, 10])
			->setOrigin([0, 0])
			->setSize([20, 20]);

		Assert::same([
			'url' => 'http://another.url',
			'size' => [20, 20],
			'origin' => [0, 0],
			'anchor' => [0, 10],
		], $this->icon->getArray());
	}

}

$test = new IconTest();
$test->run();