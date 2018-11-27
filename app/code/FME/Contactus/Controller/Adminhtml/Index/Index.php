<?php

namespace FME\Contactus\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

   
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('FME_Contactus::contactus');
    }

   
    public function execute()
    {
        
       
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('FME_Contactus::Contactus');
        $resultPage->addBreadcrumb(__('Contactus'), __('Contactus'));
        $resultPage->addBreadcrumb(__('Manage Submissions'), __('Manage Submissions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Submissions'));

        return $resultPage;
    }
}
