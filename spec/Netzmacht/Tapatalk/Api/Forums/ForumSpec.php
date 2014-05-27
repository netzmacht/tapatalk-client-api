<?php

namespace spec\Netzmacht\Tapatalk\Api\Forums;

use fXmlRpc\Value\Base64;
use Netzmacht\Tapatalk\Api\Forums\Forum;
use Netzmacht\Tapatalk\Transport\fXmlRpc\fXmlRpcSerializer;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ForumSpec extends ObjectBehavior
{
	const LOGO_URL = 'http://example.org/';

	const FORUM_NAME = 'Test';

	const FORUM_ID = '10';

	function let()
	{
		$this->beConstructedWith(self::FORUM_ID, self::FORUM_NAME, self::LOGO_URL, false, false);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\Tapatalk\Api\Forums\Forum');
    }


	function it_should_get_id_as_int_or_string()
	{
		$this->getId()->shouldReturn(static::FORUM_ID);
	}

	function it_should_get_name_as_string()
	{
		$this->getName()->shouldReturn(static::FORUM_NAME);
	}

	function it_should_get_logo_as_string()
	{
		$this->getLogo()->shouldReturn(static::LOGO_URL);
	}

	function it_should_have_not_new_posts()
	{
		$this->shouldNotHaveNewPosts();
	}

	function it_should_not_be_protected()
	{
		$this->shouldNotBeProtected();
	}

	function it_should_have_new_posts()
	{
		$this->beConstructedWith(self::FORUM_ID, self::FORUM_NAME, self::LOGO_URL, false, true);
		$this->shouldHaveNewPosts();
	}

	function it_should_be_protected()
	{
		$this->beConstructedWith(self::FORUM_ID, self::FORUM_NAME, self::LOGO_URL, true, false);
		$this->shouldBeProtected();
	}


	function it_should_be_constructed_through_response()
	{
		$this->custructFromResponse(true, true);

		$this->it_should_get_id_as_int_or_string();
		$this->it_should_get_logo_as_string();
		$this->it_should_get_name_as_string();
		$this->it_should_have_new_posts();
		$this->it_should_be_protected();
	}


	function it_should_beconstructed_through_response_with_no_new_posts()
	{
		$this->custructFromResponse(false, true);
		$this->it_should_have_not_new_posts();
	}

	function it_should_beconstructed_through_response_with_not_protected()
	{
		$this->custructFromResponse(false, false);
		$this->it_should_have_not_new_posts();
	}


	private function custructFromResponse($newPosts, $isProtected)
	{
		$data     = array(
			'forum_id' => static::FORUM_ID,
			'forum_name' => Base64::serialize(static::FORUM_NAME),
			'logo_url' => static::LOGO_URL,
			'new_post' => $newPosts,
			'is_protected' => $isProtected,
		);

		$response = new MethodCallResponse(new fXmlRpcSerializer(), $data);
		$this->beConstructedThrough(array('Netzmacht\Tapatalk\Api\Forums\Forum', 'fromResponse'), array($response));
	}

}
