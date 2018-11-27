<?php
namespace Ipragmatech\Contactme\Block\Adminhtml\Contactme\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_contactme_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Contactme Information'));
    }
}