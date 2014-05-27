<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Posts;


use Netzmacht\Tapatalk\Api\Attachments\Attachment;
use Netzmacht\Tapatalk\Api\Users\User;
use Netzmacht\Tapatalk\Transport\MethodCallResponse;
use Netzmacht\Tapatalk\Util\DateTime;


class PostFull extends Post
{
	/**
	 * @var string
	 */
	private $postTitle;

	/**
	 * @var User
	 */
	private $author;

	/**
	 * @var bool
	 */
	private $renderSmilies;

	/**
	 * @var User[]
	 */
	private $thanks;

	/**
	 * @var User[]
	 */
	private $likes;

	/**
	 * @var Edit[]
	 */
	private $edits;


	/**
	 * @param $id
	 * @param $content
	 * @param $updatedAt
	 * @param $attachments
	 * @param $canEdit
	 * @param $canDelete
	 * @param $author
	 * @param $editors
	 * @param $likes
	 * @param $postTitle
	 * @param $renderSmilies
	 * @param $thanks
	 */
	function __construct($id, $content, $updatedAt, $attachments, $canEdit, $canDelete, $author, $editors, $likes, $postTitle, $renderSmilies, $thanks)
	{
		parent::__construct($id, $content, $updatedAt, $attachments, $canEdit, $canDelete);

		$this->author        = $author;
		$this->edits         = $editors;
		$this->likes         = $likes;
		$this->postTitle     = $postTitle;
		$this->renderSmilies = $renderSmilies;
		$this->thanks        = $thanks;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return static
	 */
	public static function fromResponse(MethodCallResponse $response)
	{
		/** @var PostFull $post */
		$attachments = array();

		foreach($response->get('attachments', false, array()) as $attachment) {
			$attachments[] = new Attachment(
				$attachment->get('url'),
				$attachment->get('content_type', true),
				$attachment->get('thumbnail_url')
			);
		}

		return new static(
			$response->get('post_id'),
			$response->get('post_content', true),
			DateTime::createFromTimestamp($response->get('post_time') ? : $response->get('timestamp')),
			$attachments,
			$response->get('can_edit', false, true),
			$response->get('can_delete', false, true),
			static::createAuthor($response),
			static::createEdits($response),
			static::createLikes($response),
			$response->get('post_title', true),
			$response->get('allow_smilies', false, true),
			static::createThanks($response)
		);
	}


	/**
	 * @param MethodCallResponse $response
	 * @return \Netzmacht\Tapatalk\Api\Users\User
	 */
	private static function createAuthor(MethodCallResponse $response)
	{
		return new User(
			$response->get('post_author_id'),
			$response->get('post_autor_name'),
			$response->get('icon_url')
		);
	}

	/**
	 * @param MethodCallResponse $response
	 * @return Edit[]
	 */
	private static function createEdits(MethodCallResponse $response)
	{
		$edits = array();

		foreach($response->get('edit_info', false, array()) as $edit) {
			$edits[] = Edit::fromResponse($edit);
		}

		return $edits;
	}

	/**
	 * @param MethodCallResponse $response
	 * @return User[]
	 */
	private static function createLikes(MethodCallResponse $response)
	{
		$likes = array();

		foreach($response->get('likes_info', false, array()) as $like) {
			$likes[] = new User($like->get('userid'), $like->get('username', true), null);
		}

		return $likes;
	}


	/**
	 * @param MethodCallResponse $response
	 * @return User[]
	 */
	private static function createThanks(MethodCallResponse $response)
	{
		$thanks = array();

		foreach($response->get('thanks_info', false, array()) as $like) {
			$thanks[] = new User($like->get('userid'), $like->get('username', true), null);
		}

		return $thanks;
	}


	/**
	 * @return User
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * @return Edit[]
	 */
	public function getEdits()
	{
		return $this->edits;
	}

	/**
	 * @return User[]
	 */
	public function getLikes()
	{
		return $this->likes;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->postTitle;
	}

	/**
	 * @return boolean
	 */
	public function getRenderSmilies()
	{
		return $this->renderSmilies;
	}

	/**
	 * @return User[]
	 */
	public function getThanks()
	{
		return $this->thanks;
	}

	/**
	 * @return int
	 */
	public function getThanksNumber()
	{
		return count($this->thanks);
	}

	/**
	 * @return int
	 */
	public function getLikesNumber()
	{
		return count($this->likes);
	}

} 