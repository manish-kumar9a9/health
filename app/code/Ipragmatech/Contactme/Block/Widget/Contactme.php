<?php
/**
 * MaxLab Infotech
 * MaxLab Contact Form Widget Extension
 *
 * @category   MaxLab
 * @package    MaxLab_Contactwidget
 * @copyright  Copyright © 2006-2016 MaxLab (https://www.MaxLabinfotech.com)
 * @license    https://www.MaxLabinfotech.com/magento-extension-license/
 */
namespace Ipragmatech\Contactme\Block\Widget;

class Contactme
extends \Magento\Framework\View\Element\Template
implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->setTemplate('widget/contactme_widget.phtml');
    }

    /**
     * Get form action url
     */
    public function getFormActionUrl() {
        return $this->getUrl('contactme/index/save');
    }

    public function getCityName()
    {
        if (!$this->hasData('city_name')) {
            return 'New Delhi';
        }
        return $this->getData('city_name');
    }

    /**
     * Get config value
     */
    public function getConfigValue($value = '') {
        return $this->_scopeConfig
                ->getValue(
                        $value,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                        );
    }

    public function utmCampaign(){
      if (!$this->hasData('utm_campaign')) {
          return 'daasdasdas';
      }
      return $this->getData('utm_campaign');
    }

}
