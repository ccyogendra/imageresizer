<?php
namespace Mageplugins\ResizeImageGraphQl\Helper;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * XML path constant for Mageplugins Image Resizer configuration.
     */
    const XML_PATH_MAGEPLUGINS_IMAGERESIZER = 'resizeimage/display_setting/mageplugins_general';

    /**
     * Constructor.
     *
     * @param CustomerSession $customerSession
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CustomerSession $customerSession,
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get configuration value.
     *
     * @param string $field
     * @param string|null $storeCode
     * @return mixed
     */
    public function getConfigValue($field, $storeCode = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeCode);
    }

    /**
     * Get general configuration value.
     *
     * @param string $fieldId
     * @param string|null $storeCode
     * @return mixed
     */
    public function getGeneralConfig($fieldId, $storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_MAGEPLUGINS_IMAGERESIZER . '/' . $fieldId, $storeCode);
    }
}
