<?php

namespace Hamsa\HighValueOrder\Model\Api;

use Exception;
use Hamsa\HighValueOrder\Model\Api\SlackMessage;

class Slack
{
    // WebhookUrl e.g. https://hooks.slack.com/services/XXXXXXXXX/XXXXXXXXX/XXXXXXXXXXXXXXXXXXXXXXXX
    public $url;

    // Empty => Default username set in Slack Webhook integration settings
    public $username;

    // Empty => Default channel set in Slack Webhook integration settings
    public $channel;

    // Empty => Default icon set in Slack Webhook integration settings
    public $icon_url;

    // Empty => Default icon set in Slack Webhook integration settings
    public $icon_emoji;

    // Unfurl links: automatically fetch and create attachments for URLs
    // Empty = default (false)
    public $unfurl_links;

    /**
     * Slack constructor.
     *
     * @param $webhookUrl
     */
    public function __construct($webhookUrl)
    {
        $this->url = $webhookUrl;
    }

    /**
     * @param $property
     *
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->$property);
    }

    /**
     * @param SlackMessage $message
     *
     * @return bool
     */
    public function send(SlackMessage $message)
    {
        $data = $message->toArray();

        try {
            $json = json_encode($data);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL            => $this->url,
                CURLOPT_USERAGENT      => 'cURL Request',
                CURLOPT_POST           => 1,
                CURLOPT_POSTFIELDS     => ['payload' => $json],
            ]);
            $result = curl_exec($curl);

            if (!$result) {
                return false;
            }

            curl_close($curl);

            if ($result == 'ok') {
                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $unfurl
     *
     * @return $this
     */
    public function setDefaultUnfurlLinks($unfurl)
    {
        $this->unfurl_links = $unfurl;

        return $this;
    }

    /**
     * @param $channel
     *
     * @return $this
     */
    public function setDefaultChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @param $username
     *
     * @return $this
     */
    public function setDefaultUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param $url
     *
     * @return $this
     */
    public function setDefaultIcon($url): Slack
    {
        $this->icon_url = $url;

        return $this;
    }

    /**
     * @param $emoji
     *
     * @return $this
     */
    public function setDefaultEmoji($emoji)
    {
        $this->icon_emoji = $emoji;

        return $this;
    }
}
