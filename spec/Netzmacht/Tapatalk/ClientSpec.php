<?php

namespace spec\Netzmacht\Tapatalk;

use Netzmacht\Tapatalk\Api\Config;
use Netzmacht\Tapatalk\Client;
use Netzmacht\Tapatalk\Factory;
use Netzmacht\Tapatalk\Transport;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
	public function let(Transport $transport, Transport\MethodCallResponse $response)
	{
		$transport->call('get_config', array(), false)->willReturn($response);
		$this->beConstructedWith($transport);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\Tapatalk\Client');
    }


	function it_should_return_account()
	{
		$this->account()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Account');
	}

	function it_should_return_posts()
	{
		$this->posts()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Posts');
	}

	function it_should_return_topics()
	{
		$this->topics()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Topics');
	}

	function it_should_return_forums()
	{
		$this->forums()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Forums');
	}

	function it_should_return_users()
	{
		$this->users()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Users');
	}

}