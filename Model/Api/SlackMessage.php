<?php

namespace Hamsa\HighValueOrder\Model\Api;

use Hamsa\HighValueOrder\Model\Api\SlackAttachment;
use Hamsa\HighValueOrder\Model\Api\Slack;

class SlackMessage
{
    private $slack;

    // Message to post
    public $text = "";

    // Empty => Default username set in Slack instance
    public $username;

    // Empty => Default channel set in Slack instance
    public $channel;

    // Empty => Default icon set in Slack instance
    public $icon_url;

    // Empty => Default icon set in Slack instance
    public $icon_emoji;

    public $unfurl_links;

    // Array of SlackAttachment instances
    public $attachments;

    /**
     * SlackMessage constructor.
     *
     * @param \Hamsa\HighValueOrder\Model\Api\Slack $slack
     */
    public function __construct(Slack $slack)
    {
        $this->slack = $slack;
    }

    /**
     * @param $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param $channel
     *
     * @return $this
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @param $emoji
     *
     * @return $this
     */
    public function setEmoji($emoji)
    {
        $this->icon_emoji = $emoji;

        return $this;
    }

    public function setIcon($url)
    {
        $this->icon_url = $url;

        return $this;
    }

    /**
     * @param $bool
     *
     * @return $this
     */
    public function setUnfurlLinks($bool)
    {
        $this->unfurl_links = $bool;

        return $this;
    }

    /**
     * @param \Hamsa\HighValueOrder\Model\Api\SlackAttachment $attachment
     *
     * @return $this
     */
    public function addAttachment(SlackAttachment $attachment): SlackMessage
    {
        if (!isset($this->attachments)) {
            $this->attachments = [$attachment];

            return $this;
        }

        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @return string[]
     */
    public function toArray()
    {
        // Loading defaults
        if (isset($this->slack->username)) {
            $username = $this->slack->username;
        }

        if (isset($this->slack->channel)) {
            $channel = $this->slack->channel;
        }

        if (isset($this->slack->icon_url)) {
            $icon_url = $this->slack->icon_url;
        }

        if (isset($this->slack->icon_emoji)) {
            $icon_emoji = $this->slack->icon_emoji;
        }

        if (isset($this->slack->unfurl_links)) {
            $unfurl_links = $this->slack->unfurl_links;
        }

        // Overwrite/create defaults
        if (isset($this->username)) {
            $username = $this->username;
        }

        if (isset($this->channel)) {
            $channel = $this->channel;
        }

        if (isset($this->icon_url)) {
            $icon_url = $this->icon_url;
        }

        if (isset($this->icon_emoji)) {
            $icon_emoji = $this->icon_emoji;
        }

        if (isset($this->unfurl_links)) {
            $unfurl_links = $this->unfurl_links;
        }

        $data = [
            'text' => $this->text,
        ];
        if (isset($username)) {
            $data['username'] = $username;
        }

        if (isset($channel)) {
            $data['channel'] = $channel;
        }

        if (isset($icon_url)) {
            $data['icon_url'] = $icon_url;
        } else {
            if (isset($icon_emoji)) {
                $data['icon_emoji'] = $icon_emoji;
            }
        }

        if (isset($unfurl_links)) {
            $data['unfurl_links'] = $unfurl_links;
        }

        if (isset($this->attachments)) {
            $attachments = [];
            foreach ($this->attachments as $attachment) {
                $attachments[] = $attachment->toArray();
            }
            $data['attachments'] = $attachments;
        }

        return $data;
    }

    /*
     * Send this message to Slack
     */
    public function send()
    {
        return $this->slack->send($this);
    }
}
