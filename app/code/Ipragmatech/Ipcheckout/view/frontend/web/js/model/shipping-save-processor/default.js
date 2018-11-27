/*global define,alert*/
define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'mage/storage',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/select-billing-address'
    ],
    function (
        $,
        ko,
        quote,
        resourceUrlManager,
        storage,
        paymentService,
        methodConverter,
        errorProcessor,
        fullScreenLoader,
        selectBillingAddressAction
    ) {
        'use strict';

        return {
            saveShippingInformation: function () {
                var payload;

                if (!quote.billingAddress()) {
                    selectBillingAddressAction(quote.shippingAddress());
                }

                //for city name:

                var cityName= $('select[name="city"] option:selected').text();
                //console.log('City name from js: selected' + cityName);

                payload = {
                    addressInformation: {
                        shipping_address: quote.shippingAddress(),
                        billing_address: quote.billingAddress(),
                        shipping_method_code: quote.shippingMethod().method_code,
                        shipping_carrier_code: quote.shippingMethod().carrier_code,
                        extension_attributes:{

                            max_id: $('[name="custom_attributes[max_id]"]').val(),
                            max_city_name: cityName, //$('[name="custom_attributes[max_id]"]').val(),
                            //max_city_id: $('[name="custom_attributes[max_city_id]"]').val(), //$('[name="max_city_id"]').val(),
                            max_city_id: $('[name="city"]').val(), //$('[name="max_city_id"]').val(),
                            max_gender: $('[name="custom_attributes[max_gender]"]').val(), //$('[name="max_gender"]').val(),
                            max_dob: $('[name="custom_attributes[max_dob]"]').val(), //$('[name="max_dob"]').val()
                            max_schedule: $('[name="custom_attributes[max_schedule]"]').val(), //$('[name="max_schedule"]').val()
                            max_schedule_date: $('[name="custom_attributes[max_schedule_date]"]').val() //$('[name="max_schedule"]').val()
                        }
                    }
                };
                payload.addressInformation.shipping_address.telephone = $('[name="telephone"]').val();
                payload.addressInformation.billing_address.telephone = $('[name="telephone"]').val();
                // console.log('payload from js');
                // console.log(payload);
                fullScreenLoader.startLoader();

                return storage.post(
                    resourceUrlManager.getUrlForSetShippingInformation(quote),
                    JSON.stringify(payload)
                ).done(
                    function (response) {
                        quote.setTotals(response.totals);
                        paymentService.setPaymentMethods(methodConverter(response.payment_methods));
                        fullScreenLoader.stopLoader();
                    }
                ).fail(
                    function (response) {
                        errorProcessor.process(response);
                        fullScreenLoader.stopLoader();
                    }
                );
            }
        };
    }
);
