<?php
/**
 * Copyright © 2015 Ipragmatech. All rights reserved.
 */
namespace Ipragmatech\Ipcheckout\Model\ResourceModel;

/**
 * Ipotp resource
 */
class Ipotp extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('ipcheckout_ipotp', 'id');
    }

  
}
