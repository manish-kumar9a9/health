<?php
namespace Ipragmatech\Ipcheckout\Block\Adminhtml\Ipotp\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_ipotp_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Ipotp Information'));
    }
}