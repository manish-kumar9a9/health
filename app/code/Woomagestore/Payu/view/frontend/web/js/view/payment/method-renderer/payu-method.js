define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Woomagestore_Payu/js/action/set-payment-method',
    ],
    function(Component,setPaymentMethod){
    'use strict';

    return Component.extend({
        defaults:{
            'template':'Woomagestore_Payu/payment/payu'
        },
        redirectAfterPlaceOrder: false,
        
        afterPlaceOrder: function () {
            setPaymentMethod();    
        }

    });
});
