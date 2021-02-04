<?php

namespace Hamsa\HighValueOrder\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\ValidatorException;

/**
 * Class ValidateUrl
 * @package Hamsa\HighValueOrder\Model\Config\Backend
 */
class ValidateUrl extends Value
{
    const WEBHOOK_URL = 'hooks.slack.com';

    /**
     * @return ValidateUrl
     * @throws ValidatorException
     */
    public function beforeSave()
    {
        $url = $this->getValue();

        if (!strpos($url, self::WEBHOOK_URL)) {
            throw new ValidatorException(__('The URL "%1" is not Slack Webhook URL', $url));
        }

        return parent::beforeSave();
    }
}
