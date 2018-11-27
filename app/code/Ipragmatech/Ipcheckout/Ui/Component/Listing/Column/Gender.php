<?php

namespace Ipragmatech\Ipcheckout\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;

class Gender extends Column
{
    protected $_orderRepository;
    protected $_searchCriteria;

    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, OrderRepositoryInterface $orderRepository, SearchCriteriaBuilder $criteria, array $components = [], array $data = [])
    {
        $this->_orderRepository = $orderRepository;
        $this->_searchCriteria  = $criteria;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/max.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        //$logger->info("Here your cmment Gender:");


        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {

                $order  = $this->_orderRepository->get($item["entity_id"]);
                $status = $order->getData("max_gender");
                switch ($status) {
                    case 1:
                        $export_status = "Male";
                        break;
                    case 2;
                        $export_status = "Female";
                        break;
                    default:
                        $export_status = "Others";
                        break;

                }

                // $this->getData('name') returns the name of the column so in this case it would return export_status
                $item[$this->getData('name')] = $export_status;
            }
        }

        return $dataSource;
    }
}
