<?php

namespace Ipragmatech\Ipcheckout\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Model\Order\Payment\Transaction;

class Additionalinfo extends Column
{
    protected $_orderRepository;
    protected $_searchCriteria;
    protected $_transaction;

    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, OrderRepositoryInterface $orderRepository, Transaction $transaction, SearchCriteriaBuilder $criteria, array $components = [], array $data = [])
    {
        $this->_orderRepository = $orderRepository;
        $this->_searchCriteria  = $criteria;
        $this->_transaction = $transaction;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/max.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info("Here your cmment Additionalinfo:");


        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {

                $order  = $this->_orderRepository->get($item["entity_id"]);
                $tid = $order->getPayment()->getLastTransId();
                $collection = $this->_transaction->getCollection();
                $logger->info("ADDITIONAL Order ID: ".$item["entity_id"]);
                $logger->info("ADDITIONAL payment ID: ".$order->getPayment()->getId());
                $logger->info("ADDITIONAL Transaction ID: ".$tid);
                $logger->info("Here your cmment tRANSACTION aDDITIONAL COLLECTION: ".$collection->getSelect());
                $logger->info("Here your cmment tRANSACTION aDDITIONAL COLLECTION: ".$collection->getSelect());
                $mihpayid = $order->getPayment()->getLastTransId();

                $additionalInformation = $order->getPayment()->getAdditionalData();
                $additionalInformation = unserialize($additionalInformation);
                //$logger->info(json_encode($additionalInformation));
                // $this->getData('name') returns the name of the column so in this case it would return export_status
                $item[$this->getData('name')] = $tid;
            }
        }

        return $dataSource;
    }
}

// $transaction = Mage::getModel('sales/order_payment_transaction')->getCollection()
//                                                                          ->addAttributeToFilter('order_id', array('eq' => $payment->getOrder()->getEntityId()))
//                                                                          ->addAttributeToFilter('txn_type', array('eq' => 'capture'));
