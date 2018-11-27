<?php
namespace Ipragmatech\Contactme\Block\Adminhtml;
class Contactme extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_contactme';/*block grid.php directory*/
        $this->_blockGroup = 'Ipragmatech_Contactme';
        $this->_headerText = __('Contactme');
        $this->_addButtonLabel = __('Add New Entry'); 
        parent::_construct();
		
    }
}
