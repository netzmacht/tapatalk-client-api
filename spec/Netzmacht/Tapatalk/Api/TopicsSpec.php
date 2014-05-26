<?php

namespace spec\Netzmacht\Tapatalk\Api;

use Guzzle\Http\Message\Response;
use Netzmacht\Tapatalk\Api\Config;
use Netzmacht\Tapatalk\Api\Topics\TopicResult;
use Netzmacht\Tapatalk\Transport\fXmlRpc\fxmlRpcTransportFactory;
use Netzmacht\Tapatalk\Transport;
use Netzmacht\Tapatalk\Transport\Serializer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;

class TopicsSpec extends ObjectBehavior
{

	function let(Transport $transport, Transport\MethodCallResponse $response, Config $config, Transport\MethodCall $request)
	{
		$request->set(Argument::any(), Argument::any())->willReturn($request);
		$transport->createMethodCall('get_config')->willReturn($response);
		$this->beConstructedWith($transport, $config);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\Tapatalk\Api\Topics');
    }


	function it_should_get_topics(Transport $transport, Transport\MethodCallResponse $response, Transport\MethodCall $request, TopicResult $result)
	{
		$request->call()->willReturn($response);
		$response->has('result')->shouldBeCalled();

		$response->get(Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();
		$response->get('topics')->willReturn(array());
		$response->get('prefixes')->willReturn(array());

		$transport->createMethodCall('get_topic')->willReturn($request);
		$this->getTopics(1)->shouldReturnAnInstanceOf('Netzmacht\Tapatalk\Api\Topics\TopicResult');
	}
}
