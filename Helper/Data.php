<?php
namespace Mageplugins\ResizeImageGraphQl\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    public const XML_PATH_MAGEPLUGINS_IMAGERESIZER = 'resizeimage/display_setting/mageplugins_general';

    public function __construct(
        protected CustomerSession $customerSession,
        protected StoreManagerInterface $storeManager,
        Context $context
    ) {
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeCode = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeCode);
    }

    public function getGeneralConfig($fieldId, $storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_MAGEPLUGINS_IMAGERESIZER.'/'.$fieldId, $storeCode);
    }
}
