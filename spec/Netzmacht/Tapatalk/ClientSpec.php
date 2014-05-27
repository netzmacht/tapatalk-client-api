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
	public function let(Transport $transport)
	{
		$config = array(
			'api_level' => 4,
			'is_open'   => true
		);

		$response = new Transport\MethodCallResponse(new Transport\fXmlRpc\fXmlRpcSerializer(), $config);

		$transport->call('get_config', array(), false)->willReturn($response);
		$this->beConstructedWith($transport);
	}

	function it_is_initializable()
	{
		$this->shouldHaveType('Netzmacht\Tapatalk\Client');
	}


	function it_should_throw_exception_if_api_version_is_not_at_least_4(Transport $transport)
	{
		$config = array(
			'api_level' => 3
		);

		$response = new Transport\MethodCallResponse(new Transport\fXmlRpc\fXmlRpcSerializer(), $config);

		$transport->call('get_config', array(), false)->willReturn($response);
		$this->beConstructedWith($transport);
		$this->shouldThrow('Netzmacht\Tapatalk\Api\Exception\ApiVersionNotSupportedException');
	}


	function it_should_throw_exception_if_server_is_not_open(Transport $transport)
	{
		$config = array(
			'api_level' => 4,
			'is_open'   => false
		);

		$response = new Transport\MethodCallResponse(new Transport\fXmlRpc\fXmlRpcSerializer(), $config);

		$transport->call('get_config', array(), false)->willReturn($response);
		$this->beConstructedWith($transport);
		$this->shouldThrow('Netzmacht\Tapatalk\Api\Exception\ApiServiceNotAvailableException');
	}


	function it_should_return_account()
	{
		$this->account()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Account');
	}

	function it_should_throw_error_for_not_implemented_methods()
	{
		$this->shouldThrow('\Exception')->during('attachments');
		$this->shouldThrow('\Exception')->during('messages');
		$this->shouldThrow('\Exception')->during('conversations');
		$this->shouldThrow('\Exception')->during('moderation');
	}

	function it_should_return_board()
	{
		$this->board()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Board');
	}

	function it_should_return_config()
	{
		$this->config()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Config');
	}

	function it_should_return_forums()
	{
		$this->forums()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Forums');
	}


	function it_should_return_posts()
	{
		$this->posts()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Posts');
	}

	function it_should_return_topics()
	{
		$this->topics()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Topics');
	}


	function it_should_return_users()
	{
		$this->users()->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Users');
	}

}
