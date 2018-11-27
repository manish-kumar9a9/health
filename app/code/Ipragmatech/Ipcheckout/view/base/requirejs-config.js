/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
	'config': {
	    'mixins': {
	        'Magento_Checkout/js/view/shipping': {
	            'Ipragmatech_Ipcheckout/js/view/shipping-payment-mixin': true
	        },
	        'Magento_Checkout/js/view/payment': {
	            'Ipragmatech_Ipcheckout/js/view/shipping-payment-mixin': true
	        }
	    }
	}
};
