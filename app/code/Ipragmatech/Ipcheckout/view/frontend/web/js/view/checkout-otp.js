define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'jquery',
        'mage/url',
        'mage/mage'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        $,
        url
    ) {
        'use strict';

        // var url = url.build('ipcheckout/index/sendotp');
        // console.log('url')
        // console.log(url)
        /**
        *
        * mystep - is the name of the component's .html template,
        * <Vendor>_<Module>  - is the name of the your module directory.
        *
        */
        return Component.extend({
            defaults: {
                template: 'Ipragmatech_Ipcheckout/otp'
            },

            //add here your logic to display step,
            isVisible   : ko.observable(true),
            sendOtpUrl  : url.build('ipcheckout/index/sendotp'),
            verifyOtpUrl : url.build('ipcheckout/index/verifyotp'),
            customerUrl  : url.build('ipcheckout/index/customer'),
            nxtStatus: false,
            /**
            *
            * @returns {*}
            */
            initialize: function () {
                this._super();
                // register your step
                stepNavigator.registerStep(
                    //step code will be used as step content id in the component template
                    'otpstep',
                    //step alias
                    null,
                    //step title value
                    'Mobile verification',
                    //observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
                    * sort order value
                    * 'sort order value' < 10: step displays before shipping step;
                    * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                    * 'sort order value' > 20 : step displays after payment step
                    */
                    5
                );
                this.hideElements(this);
                return this;
            },

            /**
            * The navigate() method is responsible for navigation between checkout step
            * during checkout. You can add custom logic, for example some conditions
            * for switching to your custom step
            */
            navigate: function () {

            },

            /**
            * @returns void
            */
            hideElements: function (scope) {
                //console.log('Hide element');
                //$('.form-login').removeAttr( "data-role" );
                // $('.items-in-cart').addClass('active');
                // $('.minicart-items').show();
                $('#verifyotp').hide();
                $('#mobileOTPWrapper').hide();
            },

            /**
            * @returns void
            */
            navigateToNextStep: function () {
                var stepStatus = this.setCustomer();
                console.log('stepStatus' + stepStatus);
                //stepNavigator.next();
            },

            /**
             * Obtiene la lista de contactos relacionados a la cuenta
             * @param {Object} scope
             * @returns void
             */
            setCustomer: function(scope){

                var selectedPatient = '';
                if($("input:radio[name='patient']").is(":checked")) {
                    selectedPatient = $('input[name=patient]:checked', '#patent-otp-form').val();
                }else{
                    $('#errMsg label').text('Please select one');
                    $('#errMsg').show();
                    $('#successMsg label').text('');
                    $('#successMsg').hide();
                    return false;
                }

                var url =  this.customerUrl;
                $.ajax({
                    showLoader: true,
                    url: url,
                    type: 'GET',
                    crossDomain: true,
                    dataType: 'json',
                    data: {
                        cpMaxId: selectedPatient
                    }
                })
                .done(function(response){
                    if(response.status){
                        console.log(response);
                        $('#errMsg label').text('');
                        $('#errMsg').hide();
                        $('#successMsg label').text(response.msg);
                        $('#successMsg').show();
                        //scope.contacts = response.data;
                        $('#sendotp').hide();
                        if(response.data.MaxID){
                            console.log('maxid:' + response.data.MaxID);
                            $('[name="custom_attributes[max_id]"]').val( response.data.MaxID);
                        }
                        $('[name="telephone"]').val(response.mobile);
                        //$('.form-login').removeAttr( "data-role" );

                        //$("#id").css("display", "none");
                        stepNavigator.next();
                        return true;
                    }else{
                        $('#errMsg label').text(response.msg);
                        $('#errMsg').show();
                        $('#successMsg label').text('');
                        $('#successMsg').hide();
                        return false;
                    }
                })
                .error(function(err){
                    console.log('errr');
                    console.log(err);
                    $('#errMsg label').text('Something went wrong. Please try later..!');
                    $('#errMsg').show();
                    $('#successMsg label').text('');
                    $('#successMsg').hide();
                    return false;
                });
            },


            /**
            * @returns void
            */
            sendOTP: function (scope) {
                //this.getCustomer();
                this.startTimer();
                $('#changeno').show();
                var mobileno = $('#mobileno').val();
                if(mobileno.length != 10 ){
                    $('#successMsg label').text('');
                    $('#successMsg').hide();
                    $('#errMsg label').text('Mobile number is required and contains only 10 digits');
                    $('#errMsg').show();
                    $('#mobileno').focus();
                    return false;

                }

                //check regular expression for mobile number
                var mobregx = /^[1-9]{1}[0-9]{9}$/;
                if (mobregx.test(mobileno) == false) {
                    console.log('regular expresssioon');
                    $('#successMsg label').text('');
                    $('#successMsg').hide();
                    $('#errMsg label').text('Please enter a valid mobile number');
                    $('#errMsg').show();
                    $('#mobileno').focus();
                    return false;
                }

                var url =  this.sendOtpUrl;
                $.ajax({
                    showLoader: true,
                    url: url,
                    type: 'GET',
                    crossDomain: true,
                    dataType: 'json',
                    data: {
                        mobileNumber: mobileno
                    }
                })
                .done(function(response){
                    if(response.status){
                        if(response.status){
                            //show hide element
                            $('#sendotp').hide();
                            $('#mobileNoWrapper').hide();
                            $('#verifyotp').show();
                            $('#mobileOTPWrapper').show();
                            $('#errMsg label').text('');
                            $('#errMsg').hide();
                            $('#successMsg label').text(response.msg);
                            $('#successMsg').show();

                        }else{
                            $('#errMsg label').text(response.errorMsg);
                            $('#errMsg').show();
                            $('#successMsg label').text('');
                            $('#successMsg').hide();
                        }
                    }
                })
                .error(function(err, code){
                    console.log('errr' + err);
                    console.log('code' + code);
                    $('#errMsg label').text('Something went wrong. Please try later..!');
                    $('#errMsg').show();
                    $('#successMsg label').text('');
                    $('#successMsg').hide();
                });
            },

            /**
            * @returns void
            */
            verifyOTP: function (scope) {
                $('#changeno').show();
                var otpCode = $('#mobileotp').val();
                if(otpCode == '' || otpCode == null || otpCode === ''){
                    $('#errMsg label').text('OTP is required.');
                    $('#errMsg').show();
                    $('#successMsg label').text('');
                    $('#successMsg').hide();
                    return false;
                }

                var url =  this.verifyOtpUrl;
                $.ajax({
                    showLoader: true,
                    url: url,
                    type: 'GET',
                    crossDomain: true,
                    dataType: 'json',
                    data: {
                        otpCode: otpCode
                    }
                })
                .done(function(response){

                    var htmlData = '';
                    if(response.status == true){
                        //hide the element
                        $('#verifyotp').hide();
                        $('#mobileOTPWrapper').hide();
                        $('#errMsg label').text('');
                        $('#errMsg').hide();
                        $('#successMsg label').text(response.msg);
                        $('#successMsg').show();
                        $('#ipnext').show();

                        if(response.data.length <= 0){
                            //No data navigate to next
                            // if(response.data.MaxID){
                            //     console.log('maxid:' + response.data.MaxID);
                            //     $('[name="custom_attributes[max_id]"]').val( response.data.MaxID);
                            // }
                            $('[name="telephone"]').val(response.mobile);
                            $('#ipnext').hide();
                            stepNavigator.next();
                            return true;
                        }else{

                            htmlData += '<p class="note-info">The selected Max ID will be used for reporting.</p><table class="table table-bordered table-hover table-striped max-member"><thead><tr><th class="member-heading member-select">Select</th><th class="member-heading">Member Name</th><th class="member-heading">Max ID</th></tr></thead><tbody>'
                            $.each(response.data, function(index, patient) {
                                htmlData += '<tr><td><input type="radio" id="'+ patient.MaxID +'" name="patient" value="'+patient.MaxID+'"></td><td>'+patient.PatientName+'</td><td>'+patient.MaxID+'</td></tr>';
                                //htmlData += '<li><label  id="' + patient.MaxID + '"><input type="radio" id="'+ patient.MaxID +'" name="patient" value="'+patient.MaxID+'">'+patient.PatientName+' [ Max ID: '+patient.MaxID+' ]'+'</li>';
                            });
                            htmlData += '<td><input type="radio" id="newpatient" name="patient" value="0"></td><td colspan="2">Add New Member</td>';
                            htmlData += '</tbody></table>';
                            //htmlData += '<li><label id="newpatient"><input type="radio" id="newpatient" name="patient" value="0">Add New Customer</li>';
                            $("#ip-user-list div").append(htmlData);
                        }


                    }else{
                        $('#errMsg label').text(response.msg);
                        $('#errMsg').show();
                        $('#successMsg label').text('');
                        $('#successMsg').hide();
                    }
                })
                .error(function(err){
                    console.log('errr');
                    console.log(err);
                    $('#errMsg label').text('Something went wrong. Please try later.');
                    $('#errMsg').show();
                    $('#successMsg label').text('');
                    $('#successMsg').hide();
                });
            },

            startTimer: function( scope ){
                console.log('Timer started...');
                var timeLeft = 30;
                var elem = document.getElementById('timermsg');

                var timerId = setInterval(countdown, 1000);

                function countdown() {
                    if (timeLeft == 0) {
                        clearTimeout(timerId);
                        $('#timermsg').hide();
                        $('#resendOTP').show();
                    } else {
                        $('#timermsg').show();
                        $('#resendOTP').hide();
                        elem.innerHTML = 'Didn\'t recieve the code yet? Resend OTP in (' + timeLeft + ') seconds.';
                        timeLeft--;
                    }
                }
            },

            changeNo: function(){
                
                $('#sendotp').show();
                $('#mobileNoWrapper').show();
                $('#verifyotp').hide();
                $('#mobileOTPWrapper').hide();
                $('#errMsg label').text('');
                $('#errMsg').hide();
                $('#successMsg label').text('');
                $('#successMsg').hide();
                $("#ip-user-list ul").empty();
                $('#ipnext').hide();
                $('#mobileotp').val('');
                $('#changeno').hide();
            }
        });
    }
);
