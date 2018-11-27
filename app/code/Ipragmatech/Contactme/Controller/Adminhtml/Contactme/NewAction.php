<?php
namespace Ipragmatech\Contactme\Controller\Adminhtml\Contactme;
use Magento\Backend\App\Action;
class NewAction extends \Magento\Backend\App\Action
{
     public function execute()
    {
		$this->_forward('edit');
    }
}
