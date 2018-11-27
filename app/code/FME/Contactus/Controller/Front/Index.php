<?php

namespace FME\Contactus\Controller\Front;

class Index extends \FME\Contactus\Controller\Front
{

    public function execute()
    {
    
        //echo "news module";
        $this->_view->loadLayout();

        //$this->_view->getLayout()->initMessages();

        $this->_view->renderLayout();
    }
}
