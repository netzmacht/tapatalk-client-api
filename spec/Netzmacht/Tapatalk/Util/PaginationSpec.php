<?php

namespace spec\Netzmacht\Tapatalk\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaginationSpec extends ObjectBehavior
{
	function it_is_initializable()
	{
		$this->shouldHaveType('Netzmacht\Tapatalk\Util\Pagination');
	}

	function it_should_return_first_page_if_limit_is_bigger_than_offset()
	{
		$this->getPage(100, 10)->shouldReturn(1);
	}

	function it_should_return_second_page_if_limit_equals_offset()
	{
		$this->getPage(10, 10)->shouldReturn(2);
	}

	function it_should_return_quotient_of_page_limit_plus_1()
	{
		$this->getPage(10, 40)->shouldReturn((int)40 / 10 + 1);
	}


	function it_should_calculate_offset_from_given_page_and_limit()
	{
		$this->getOffset(10, -1)->shouldReturn(0);
		$this->getOffset(10, 1)->shouldReturn(0);
		$this->getOffset(10)->shouldReturn(0);
		$this->getOffset(10, 2)->shouldReturn(10);
	}
}
