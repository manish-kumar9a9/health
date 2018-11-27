define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
],function(Component,renderList){
    'use strict';
    renderList.push({
        type : 'payu',
        component : 'Woomagestore_Payu/js/view/payment/method-renderer/payu-method'
    });

    return Component.extend({});
})
