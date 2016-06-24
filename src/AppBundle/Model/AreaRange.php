<?php
/**
 * Created by PhpStorm.
 * User: ridho
 * Date: 23/06/2016
 * Time: 02:44
 */

namespace AppBundle\Model;


class AreaRange
{
	/**
	 * @var float
	 */
	private $topLeftX;

	/**
	 * @var float
	 */
	private $topLeftY;

	/**
	 * @var int in meters
	 */
	private $radius;

	/**
	 * @param $topLeftX float
	 * @param $topLeftY float
	 * @param $radius int meter
	 */
	public function __construct($topLeftX, $topLeftY, $radius)
	{
		$this->topLeftX = $topLeftX;
		$this->topLeftY = $topLeftY;
		$this->radius = $radius;
	}

	public function getTopLeftX()
	{
		return $this->topLeftX;
	}

	public function getTopLeftY()
	{
		return $this->topLeftY;
	}

	public function getRadius()
	{
		return $this->radius;
	}

}