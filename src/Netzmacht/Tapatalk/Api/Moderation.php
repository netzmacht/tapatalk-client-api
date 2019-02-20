<?php

/**
 * @package    tapatalk-client-api
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2019 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

declare(strict_types=1);

namespace Netzmacht\Tapatalk\Api;

use Netzmacht\Tapatalk\Api;

final class Moderation extends Api
{
    public function stickTopic(string $topicId): void
    {
        $response = $this->transport->call('m_stick_topic', ['topic_id' => $topicId, 'mode' => 1]);
        $this->assert()->resultSuccess($response);
    }

    public function unstickTopic(string $topicId): void
    {
        $response = $this->transport->call('m_stick_topic', ['topic_id' => $topicId, 'mode' => 2]);
        $this->assert()->resultSuccess($response);
    }

    public function reopenTopic(string $topicId): void
    {
        $response = $this->transport->call('m_close_topic', ['topic_id' => $topicId, 'mode' => 1]);
        $this->assert()->resultSuccess($response);
    }

    public function closeTopic(string $topicId): void
    {
        $response = $this->transport->call('m_close_topic', ['topic_id' => $topicId, 'mode' => 2]);
        $this->assert()->resultSuccess($response);
    }
}
