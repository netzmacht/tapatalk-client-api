<?php

namespace spec\Netzmacht\Tapatalk\Transport;

use fXmlRpc\Value\Base64;
use Netzmacht\Tapatalk\Transport\fXmlRpc\fXmlRpcSerializer;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MethodCallResponseSpec extends ObjectBehavior
{
	const NAME    = 'test';
	const BOOLEAN = true;

	private $data;

	function let()
	{
		$this->data = array(
			'name'  => Base64::serialize(static::NAME),
			'test'  => static::BOOLEAN,
			'null'  => null,
			'false' => false
		);

		$this->beConstructedWith(new fXmlRpcSerializer(), $this->data);
	}

	function it_is_initializable()
	{
		$this->shouldHaveType('Netzmacht\Tapatalk\Transport\MethodCallResponse');
	}

	function it_should_convert_base64_values()
	{
		$this->get('name', true)->shouldReturn(static::NAME);
	}

	function it_should_return_default_value()
	{
		$this->get('not_set', false, 'default')->shouldReturn('default');
	}

	function it_should_return_value()
	{
		$this->get('test')->shouldReturn(static::BOOLEAN);
	}

	function it_should_return_data_iterator()
	{
		$this->getIterator()->shouldHaveType('\ArrayIterator');
	}

	function it_should_contain_data_in_iterator()
	{
		$this->getIterator()->getArrayCopy()->shouldEqual($this->data);
	}

	function it_should_have_name()
	{
		$this->has('name')->shouldReturn(true);
	}

	function it_should_not_have_default()
	{
		$this->has('default')->shouldReturn(false);
	}

	function it_should_have_false_value()
	{
		$this->has('false')->shouldReturn(true);
	}

	function it_should_have_null_value()
	{
		$this->has('null')->shouldReturn(true);
	}

	function it_should_get_data_serialized()
	{
		$this->getData()->shouldReturn($this->data);
	}

	function it_should_get_data_deserialized()
	{
		$data         = $this->data;
		$data['name'] = static::NAME;

		$this->getData(true)->shouldReturn($data);
	}

	function it_should_not_get_data_deserialized()
	{
		$data         = $this->data;
		$data['name'] = static::NAME;

		$this->getData(false)->shouldNotReturn($data);
	}

}
