<?php
namespace AppBundle\Model;

class TimeRange
{
	/**
	 * @var \DateTime Linux TimeStamp
	 */
	private $start;

	/**
	 * @var \DateTime Linux TimeStamp
	 */
	private $end;

	/**
	 * TimeRange constructor.
	 * @param $start int Linux TimeStamp
	 * @param $end int Linux TimeStamp
	 */
	public function __construct($start, $end)
	{
		$this->start = $start;
		$this->end = $end;
	}

	public function getStart()
	{
		return $this->start;
	}

	public function getEnd()
	{
		return $this->end;
	}
}