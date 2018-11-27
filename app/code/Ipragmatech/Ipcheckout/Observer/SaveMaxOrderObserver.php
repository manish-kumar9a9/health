<?php
namespace Ipragmatech\Ipcheckout\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class SaveMaxOrderObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectmanager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectmanager)
    {
        $this->_objectManager = $objectmanager;
    }


    public function execute(EventObserver $observer)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/otp.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info("Before order placed");


        $order = $observer->getOrder();
        $quoteRepository = $this->_objectManager->create('Magento\Quote\Model\QuoteRepository');

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $quoteRepository->get($order->getQuoteId());
        $order->setMaxId( $quote->getMaxId() );
        $order->setMaxCityId( $quote->getMaxCityId() );
        $order->setMaxGender( $quote->getMaxGender() );
        $order->setMaxDob( $quote->getMaxDob() );
        $order->setMaxCityName( $quote->getMaxCityName() );
        $order->setMaxSchedule( $quote->getMaxSchedule() );
        $order->setMaxScheduleDate( $quote->getMaxScheduleDate() );

        $logger->info("Before order placed: Max Id:".$quote->getMaxId().", city: ".$quote->getMaxCityId().", gender: ". $quote->getMaxGender().", DOb:".$quote->getMaxDob().', City Name: '.$quote->getMaxCityName().', Schedule: '.$quote->getMaxScheduleDate().' : '.$quote->getMaxSchedule());
        return $this;
    }
}
