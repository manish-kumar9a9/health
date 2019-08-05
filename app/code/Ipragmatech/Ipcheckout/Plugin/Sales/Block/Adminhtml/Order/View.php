<?php
namespace Ipragmatech\Ipcheckout\Plugin\Sales\Block\Adminhtml\Order;


use Magento\Sales\Block\Adminhtml\Order\View as OrderView;
use Magento\Framework\UrlInterface;
use Magento\Framework\AuthorizationInterface;

class View
{
    /** @var \Magento\Framework\UrlInterface */
    protected $_urlBuilder;

    /** @var \Magento\Framework\AuthorizationInterface */
    protected $_authorization;

    public function __construct(
        UrlInterface $url,
        AuthorizationInterface $authorization
    ) {
        $this->_urlBuilder = $url;
        $this->_authorization = $authorization;
    }

    public function beforeSetLayout(OrderView $view) {
        $url = $this->_urlBuilder->getUrl('ipcheckout/refund/index', ['id' => $view->getOrderId()]);

        $view->addButton(
            'my_new_button',
            [
                'label' => __('Refund'),
                'onclick' => 'setLocation(\'' . $url . '\')',
                'class' => 'my-button'
            ]
        );
    }

    public function getCustomUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $urlManager = $objectManager->get('Magento\Framework\Url');
        return $urlManager->getUrl('sales/*/custom');
    }
}