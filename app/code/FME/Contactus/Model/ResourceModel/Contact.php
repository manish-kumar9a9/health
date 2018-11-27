<?php

namespace FME\Contactus\Model\ResourceModel;

class Contact extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    
    protected function _construct()
    {
        $this->_init('fme_contactus', 'contact_id');
    }
}
