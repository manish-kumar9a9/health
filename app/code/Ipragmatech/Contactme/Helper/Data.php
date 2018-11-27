<?php
/**
 * Copyright © 2015 Ipragmatech . All rights reserved.
 */
namespace Ipragmatech\Contactme\Helper;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
  /**
   * @var \Magento\Store\Model\StoreManagerInterface
   */
  protected $_storeManager;

	/**
     * @param \Magento\Framework\App\Helper\Context $context
     *@param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
	public function __construct(
    \Magento\Framework\App\Helper\Context $context,
    \Magento\Store\Model\StoreManagerInterface $storeManager
	) {
    $this->_storeManager = $storeManager;
		parent::__construct($context);
	}

  /**
   * Get config value
   */
  public function getConfigValue($value = '') {
      return $this->scopeConfig
              ->getValue(
                      $value,
                      \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                      );
  }

  /**
   * Get base url
   */
  public function getBaseUrl() {
      return $this->_storeManager
              ->getStore()
              ->getBaseUrl(
                      \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                      );
  }

  /**
   * Get current url
   */
  public function getCurrentUrls() {
      return $this->_urlBuilder->getCurrentUrl();
  }
}
