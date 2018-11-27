<?php
namespace Ipragmatech\Ipcheckout\Block\Adminhtml;
class Ipotp extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_ipotp';/*block grid.php directory*/
        $this->_blockGroup = 'Ipragmatech_Ipcheckout';
        $this->_headerText = __('Ipotp');
        $this->_addButtonLabel = __('Add New Entry'); 
        parent::_construct();
		
    }
}
