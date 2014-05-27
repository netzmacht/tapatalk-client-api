<?php

namespace spec\Netzmacht\Tapatalk\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prediction\CallbackPrediction;
use Prophecy\Prophecy\MethodProphecy;

class DateTimeSpec extends ObjectBehavior
{
	function it_is_initializable()
	{
		$this->shouldHaveType('Netzmacht\Tapatalk\Util\DateTime');
	}


	function it_should_return_instance_of_php_datetime()
	{
		$this->createFromTimestamp()->shouldHaveType('\DateTime');
	}


	function it_should_use_passed_timestamp()
	{
		$tstamp = time() - 64000;

		$this->createFromTimestamp($tstamp)->shouldHaveType('\DateTime');
		$this->createFromTimestamp($tstamp)->shouldEqualTimestamp($tstamp);
	}


	function it_should_use_now_when_no_timetamp_passed()
	{
		$now = time();
		// use tolerance to avoid timestamp switching between those 2 operations
		$this->createFromTimestamp()->shouldEqualTimestamp($now, 1);
	}


	public function getMatchers()
	{
		return array(
			'equalTimestamp' => function ($subject, $key, $tolerance = 0) {
					if($tolerance) {
						$ts = $subject->getTimestamp();

						return ((($ts + $tolerance) > $key) && (($ts - $tolerance) < $key));
					}

					return ($subject->getTimestamp() === $key);
				}
		);
	}
}
