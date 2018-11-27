<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\Quickrfq\Block\Adminhtml\Quickrfq\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('quickrfq_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('RFQ Information'));
    }
}
