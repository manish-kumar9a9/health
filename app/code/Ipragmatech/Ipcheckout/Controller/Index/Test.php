<?php
/**
 *
 * Copyright © 2015 Ipragmatechcommerce. All rights reserved.
 */
namespace Ipragmatech\Ipcheckout\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\View\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     *@var Ipragmatech\Ipcheckout\Helper\Data
     */
     protected $helper;

    /*
     * \Magento\Customer\Model\Session
     */
     protected $customerSession;



    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Ipragmatech\Ipcheckout\Helper\Data
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory    $resultJsonFactory,
        \Ipragmatech\Ipcheckout\Helper\Data $helper,
        \Magento\Customer\Model\Session $customerSession

    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        $this->customerSession = $customerSession;
    }

    /**
     * Flush cache storage
     *
     */
    public function execute()
    {

        // //Temp end
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/otp.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('Test Controllers......!!');
        //
        // $om = \Magento\Framework\App\ObjectManager::getInstance();
        // $generateBillInstance = $om->get('Ipragmatech\Ipcheckout\Observer\Generatebill');
        // $observerIns = $om->get('Magento\Framework\Event\Observer');
        //
        // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // $orderFactory = $objectManager->get('\Magento\Sales\Model\OrderFactory');
        // $order_information = $orderFactory->create()->load(21);
        // $shippingAddress = $order_information->getShippingAddress();
        // $logger->info(json_encode($order_information->getData()));
        // $logger->info('******************************************');
        // $logger->info('payment' . json_encode($order_information->getPayment()->getData()));
        // $logger->info('payment2: ' . json_encode($order_information->getPayment()->getLastTransId()));
        //
        //
        // $items = $order_information->getAllItems();
        // foreach($items as $item) {
        //     $productIds[]= $item->getProductId();
        //     $logger->info('Product:--------------------------------- ' .json_encode($item->getData()));
        // }
        //
        // $timezoneInterface = $objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        // $bdate = $timezoneInterface->date($order_information->getMaxDob())->format('m/d/Y');
        // $logger->info('DOB- ' .$bdate);

        //\Magento\Framework\Event\Observer $observer
        //$Generatebill = $generateBillInstance->execute($observerIns);

        $this->resultPage = $this->resultPageFactory->create();
		return $this->resultPage;

    }
}
