<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Tapatalk\Api\Config;

/**
 * Class Features
 * @package Netzmacht\Tapatalk\Api\Config
 */
class Features 
{
	const REPORT_POST             = 'report_post';
	const REPORT_PM               = 'report_pm';
	const MARK_ALL_READ           = 'mark_read';
	const MARK_FORUM_READ         = 'mark_forum';
	const SUBSCRIBE_FORUM         = 'subscribe_forum';
	const LIST_LATEST_TOPICS      = 'get_latest_topic';
	const GET_ID_BY_URL           = 'get_id_by_url';
	const DELETE_REASON           = 'delete_reason';
	const MOD_APPROVE_VIEW        = 'm_approve';
	const MOD_DELETE_VIEW         = 'm_delete';
	const MOD_REPORT_VIEW         = 'm_report';
	const ANONYMOUS_LOGIN         = 'anonymous';
	const SEARCH_ID               = 'searchid';
	const DOWNLOAD_AVATAR         = 'avatar';
	const PM_PAGINATION           = 'pm_load';
	const SUBSCRIBE_PAGINATION    = 'subscribe_load';
	const PM                      = 'inbox_stat';
	const MULTI_QUOTE             = 'multi_quote';
	const DEFAULT_SMILIES         = 'default_smilies';
	const UNREAD                  = 'can_unread';
	const ANNOUNCEMENTS           = 'announcement';
	const EMOJI                   = 'emoji_support';
	const PM_CONVERSATION         = 'conversation';
	const GET_TOPIC_STATUS        = 'get_topic_status';
	const GET_PARTICIPATED_FORUM  = 'get_participated_forum';
	const GET_FORUM_STATUS        = 'get_forum_status';
	const GET_SMILIES             = 'get_smilies';
	const GET_ACTIVITY            = 'get_activity';
	const SUBSCRIBE_UNREAD_NUMBER = 'inbox_stat'; // no bug. it's actually the same config name. added for more semantic


	/**
	 * @return array
	 */
	public static function getFeatures()
	{
		$reflector = new \ReflectionClass(get_called_class());
		$constants = $reflector->getConstants();

		return array_values($constants);
	}

} 