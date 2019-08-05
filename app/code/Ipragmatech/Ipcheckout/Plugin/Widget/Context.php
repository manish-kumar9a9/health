<?php
namespace Ipragmatech\Ipcheckout\Plugin\Widget;


class Context
{
    public function afterGetButtonList(
        \Magento\Backend\Block\Widget\Context $subject,
        $buttonList
    )
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\Action\Context')->getRequest();
        if($request->getFullActionName() == 'sales_order_view'){
            $buttonList->add(
                'custom_button',
                [
                    'label' => __('Custom Button'),
                    'onclick' => 'setLocation(\'' . $this->getCustomUrl() . '\')',
                    'class' => 'ship'
                ]
            );
        }

        return $buttonList;
    }

    public function getCustomUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $urlManager = $objectManager->get('Magento\Framework\Url');
        return $urlManager->getUrl('sales/*/custom');
    }
}