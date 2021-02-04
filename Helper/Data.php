<?php

namespace Hamsa\HighValueOrder\Helper;

use Magento\Email\Model\ResourceModel\Template;
use Magento\Email\Model\TemplateFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package Hamsa\HighValueOrder\Helper
 */
class Data extends AbstractHelper
{
    const GENERAL_PATH = 'high_value_order/general/';

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var TemplateFactory
     */
    protected $templateFactory;

    /**
     * @var Template
     */
    protected $templateResourceModel;

    /**
     * Data constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param TemplateFactory $templateFactory
     * @param Template $templateResourceModel
     * @param Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        TemplateFactory $templateFactory,
        Template $templateResourceModel,
        Context $context
    ) {
        $this->_storeManager         = $storeManager;
        $this->templateFactory       = $templateFactory;
        $this->templateResourceModel = $templateResourceModel;

        parent::__construct($context);
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::GENERAL_PATH . 'enable',
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    /**
     * @return float
     * @throws NoSuchEntityException
     */
    public function getTriggerPrice()
    {
        return (float) $this->scopeConfig->getValue(
            self::GENERAL_PATH . 'trigger_price',
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getWebhookURL()
    {
        return $this->scopeConfig->getValue(
            self::GENERAL_PATH . 'webhook_url',
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getMessageTemplateId()
    {
        return $this->scopeConfig->getValue(
            self::GENERAL_PATH . 'message_template',
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    /**
     * @return \Magento\Email\Model\Template
     * @throws NoSuchEntityException
     */
    public function getTemplate()
    {
        $templateId = $this->getMessageTemplateId();
        $template   = $this->templateFactory->create();
        if (is_numeric($templateId)) {
            $this->templateResourceModel->load($template, $templateId);
        } else {
            $template->setForcedArea($templateId);
            $template->loadDefault($templateId);
        }

        return $template;
    }

    /**
     * @param $order
     *
     * @return string
     * @throws NoSuchEntityException|MailException
     */
    public function getMessage($order)
    {
        $template = $this->getTemplate();
        $template->setVars(['order' => $order]);

        return strip_tags($template->processTemplate());
    }
}
