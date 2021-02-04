<?php

namespace Hamsa\HighValueOrder\Model\Api;

/**
 * Class SlackAttachmentField
 * @package Hamsa\HighValueOrder\Model\Api
 */
class SlackAttachmentField
{
    // Required
    public $title = "";
    public $value = "";

    // Optional
    public $short;

    /**
     * SlackAttachmentField constructor.
     *
     * @param $title
     * @param $value
     * @param null $short
     */
    public function __construct($title, $value, $short = null)
    {
        $this->title = $title;
        $this->value = $value;
        if (isset($short)) {
            $this->short = $short;
        }
    }

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setShort($bool = true): SlackAttachmentField
    {
        $this->short = $bool;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'title' => $this->title,
            'value' => $this->value,
        ];
        if (isset($this->short)) {
            $data['short'] = $this->short;
        }

        return $data;
    }
}
