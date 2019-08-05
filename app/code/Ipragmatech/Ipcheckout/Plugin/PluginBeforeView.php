<?php
/**
 * Created by PhpStorm.
 * User: manish
 * Date: 05/08/19
 * Time: 9:32 PM
 */


namespace Ipragmatech\Ipcheckout\Plugin;

class PluginBeforeView
{
    public function beforeGetOrderId(\Magento\Sales\Block\Adminhtml\Order\View $subject){
        $subject->addButton(
            'reset33',
            ['label' => __('Button 2'), 'onclick' => 'setLocation(window.location.href)', 'class' => 'reset'],
            -1
        );
        return null;
    }
}