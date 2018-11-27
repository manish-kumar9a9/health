<?php

namespace Woomagestore\Payu\Controller\Standard;

class Cancel extends \Woomagestore\Payu\Controller\PayuAbstract {

    public function execute() {
        $this->getOrder()->cancel()->save();
        
        $this->messageManager->addErrorMessage(__('Your order has been can cancelled'));
        $this->getResponse()->setRedirect(
                $this->getCheckoutHelper()->getUrl('checkout')
        );
    }

}
