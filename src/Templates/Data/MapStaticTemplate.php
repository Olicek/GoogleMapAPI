<?php declare(strict_types = 1);

namespace Oli\GoogleAPI\Templates\Data;

use Nette\Bridges\ApplicationLatte\Template;

/**
 * Class MapDefaultTemplate
 * Copyright (c) 2017 Sportisimo s.r.o.
 */
class MapStaticTemplate extends Template
{

  /**
   * @var double|string
   */
  public $height;

  /**
   * @var double|string
   */
  public $width;

  /**
   * @var int
   */
  public int $zoom;

  /**
   * @var array
   */
  public array $position;

  /**
   * @var array
   */
  public array $markers = [];

  /**
   * @var bool|string|callable
   */
  public $clickable = false;

  /**
   * @var array
   */
  public array $map = [];

}
