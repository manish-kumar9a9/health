<?php
namespace Ipragmatech\Ipcheckout\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddHtmlToOrderShippingViewObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function execute(EventObserver $observer)
    {
        if($observer->getElementName() == 'order_shipping_view') {
            $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
            $order = $orderShippingViewBlock->getOrder();
            // $localeDate = $this->objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
            // if($order->getDeliveryDate() != '0000-00-00 00:00:00') {
            //     $formattedDate = $localeDate->formatDateTime(
            //         $order->getDeliveryDate(),
            //         \IntlDateFormatter::MEDIUM,
            //         \IntlDateFormatter::MEDIUM,
            //         null,
            //         $localeDate->getConfigTimezone(
            //             \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            //             $order->getStore()->getCode()
            //         )
            //     );
            // } else {
            //     $formattedDate = __('N/A');
            // }


            $maxDataBlock = $this->objectManager->create('Magento\Framework\View\Element\Template');
            $maxDataBlock->setMaxId($order->getMaxId());
            $maxDataBlock->setMaxCityId($order->getMaxCityId());
            $maxDataBlock->setMaxCityName($order->getMaxCityName());
            $maxDataBlock->setMaxDob($order->getMaxDob());
            $maxDataBlock->setMaxGender($order->getMaxGender());
            $maxDataBlock->setMaxSchedule($order->getMaxSchedule());
            $maxDataBlock->setMaxScheduleDate($order->getMaxScheduleDate());
            $maxDataBlock->setTemplate('Ipragmatech_Ipcheckout::order_info_shipping_info.phtml');
            $html = $observer->getTransport()->getOutput() . $maxDataBlock->toHtml();
            $observer->getTransport()->setOutput($html);
        }
    }
}
