<?php

namespace Hamsa\HighValueOrder\Observer\Quote;

use Exception;
use Hamsa\HighValueOrder\Helper\Data;
use Hamsa\HighValueOrder\Model\Api\Slack;
use Hamsa\HighValueOrder\Model\Api\SlackMessage;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AfterPlaceOrder
 * @package Hamsa\HighValueOrder\Observer\Quote
 */
class AfterPlaceOrder implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AfterPlaceOrder constructor.
     *
     * @param Data $helperData
     * @param LoggerInterface $logger
     */
    public function __construct(
        Data $helperData,
        LoggerInterface $logger
    ) {
        $this->_helperData = $helperData;
        $this->logger      = $logger;
    }

    /**
     * @param Observer $observer
     *
     * @return AfterPlaceOrder
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getData('order');

        try {
            if ($order
                && $this->_helperData->isEnabled()
                && $order->getGrandTotal() >= $this->_helperData->getTriggerPrice()
            ) {
                $message      = $this->_helperData->getMessage($order);
                $slack        = new Slack($this->_helperData->getWebhookURL());
                $slackMessage = new SlackMessage($slack);
                $slackMessage->setText($message);
                $slackMessage->send();
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $this;
    }
}
