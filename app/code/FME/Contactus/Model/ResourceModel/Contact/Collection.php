<?php

namespace FME\Contactus\Model\ResourceModel\Contact;

use \FME\Contactus\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'contact_id';

    protected $_previewFlag;

    protected function _construct()
    {
        $this->_init('FME\Contactus\Model\Contact', 'FME\Contactus\Model\ResourceModel\Contact');

        $this->_map['fields']['contact_id'] ='main_table.contact_id';
    }
}
