<?php
/**
 * Copyright © 2015 Ipragmatech. All rights reserved.
 */
namespace Ipragmatech\Contactme\Model\ResourceModel;

/**
 * Contactme resource
 */
class Contactme extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('contactme_contactme', 'id');
    }

  
}
