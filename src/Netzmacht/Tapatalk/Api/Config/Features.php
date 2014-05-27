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
	const REPORT_POST             = 'report_post';              // report_post
	const REPORT_PM               = 'report_pm';                // report_om
	const MARK_ALL_READ           = 'mark_read';                // mark_all_as_read
	const MARK_FORUM_READ         = 'mark_forum';               // mark_all_as_read
	const SUB_FORUM_SUBSCRIPTION  = 'subscribe_forum';          // get_subscribed_forum
	const GET_LATEST_TOPICS       = 'get_latest_topic';         // get_latest_topic
	const GET_ID_BY_URL           = 'get_id_by_url';            // get_id_by_url
	const DELETE_REASON           = 'delete_reason';            // m_delete_post, m_delete_topic
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
	const SUBSCRIBE_UNREAD_NUMBER = 'inbox_stat';               // no bug. it's actually the same config name. added for more semantic
	const ADVANCED_ONLINE_USERS   = 'advanced_online_users';
	const MARK_PM_UNREAD          = 'mark_pm_unread';
	const ADVANCED_SEARCH         = 'advanced_search';
	const MASS_SUBSCRIBE          = 'mass_subscribe';           // support id 'ALL' in unsubscribe_topic / unsubscribe_forum
	const USER_ID                 = 'user_id';                  // get_participated_topic / get_user_info / get_user_topic / get_user_reply_post support
	const ADVANCED_DELETE         = 'advanced_delete';
	const MARK_TOPIC_READ         = 'mark_topic_read';
	const FIRST_UNREAD            = 'first_unread';
	const ALERT                   = 'alert';
	const DIRECT_UNSUBSCRIBE      = 'direct_unsubscribe';
	const PREFIX_EDIT             = 'prefix_edit';              // m_rename_topic
	const BAN_DELETE_TYPE         = 'ban_delete_type';
	const ADVANCED_EDIT           = 'advanced_edit';
	const SEARCH_USER             = 'search_user';              // search_user
	const USER_RECOMMENDED        = 'user_recommended';         // get_recommended_user
	const ACCOUNT_FEATURES        = 'inappreg';                 // orget_password , update_password , update_email , register
	const SIGN_IN_WITH_TAPATALK   = 'sign_in';                  // function sign_in, prefetch_account, login status return was supported or not
	const IGNORE_USER             = 'ignore_user';              // ignore user
	const MOD_ADVANCED_MERGE      = 'advanced_merge';           // m_merge_topic
	const MOD_ADVANCED_MOVE       = 'advanced_move';            // m_move_topic
	const MOD_BAN_EXPIRES         = 'ban_expires';              // m_ban_user


	/**
	 * @var array
	 */
	private static $default = array(
		Features::MARK_ALL_READ          => true,
		Features::SUB_FORUM_SUBSCRIPTION => true,
		Features::UNREAD                 => true,
		Features::ANNOUNCEMENTS          => true,
		Features::FIRST_UNREAD           => true,
	);


	/**
	 * @return array
	 */
	public static function getFeatures()
	{
		$reflector = new \ReflectionClass(get_called_class());
		$constants = $reflector->getConstants();

		return array_values($constants);
	}


	/**
	 * @param $feature
	 * @return bool
	 */
	public static function getDefaultValue($feature)
	{
		if(array_key_exists($feature, static::$default)) {
			return static::$default[$feature];
		}

		return false;
	}

} 