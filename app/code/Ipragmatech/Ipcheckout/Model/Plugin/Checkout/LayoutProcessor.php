<?php

 namespace Ipragmatech\Ipcheckout\Model\Plugin\Checkout;
 class LayoutProcessor
 {
      /**
      * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
      * @param array $jsLayout
      * @return array
      */
      public function afterProcess(
          \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
          array  $jsLayout
      ) {

          $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/otp.log');
          $logger = new \Zend\Log\Logger();
          $logger->addWriter($writer);
          $logger->info("layout Processor");


        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        //$patientData = $customerSession->getPatients();
        $patientData = $customerSession->getCurrentPatients();
        $mobileNo = $customerSession->getMobileNumber();
        //$logger->info("Mobile NO: ".$mobileNo);
        //$logger->info(json_encode($patientData));

        $cityCollection = $om->get('Ipragmatech\Ipcheckout\Model\City')->getCollection();
        $cityCollection->addFieldToSelect('city_name');
        $cityCollection->addFieldToSelect('city_code');
        //$logger->info('City Select Query'. $cityCollection->getSelect());

        $cityOption = [];
        if(count($cityCollection)){
            foreach ($cityCollection as $city) {
                $temp = [
                    'value' => $city['city_name'],
                    'label' => $city['city_code']
                ];
                $cityOption[] = $temp;
            }

        }

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_gender'] = [
         'component' => 'Magento_Ui/js/form/element/select',
         'config'	=> [
         	'customScope' => 'shippingAddress.custom_attributes',
         	'template' => 'ui/form/field',
         	'elementTmpl' => 'ui/form/element/select',
         	'options' => [
                 ['value' => '1', 'label' => 'Male'],
                 ['value' => '2', 'label' => 'Female'],
                 ['value' => '3', 'label' => 'Others']
             ],
         	'id' => 'maxgender'
         ],
         'validation' => [
            'required-entry' => true
         ],
         'options' => [
             ['value' => '1', 'label' => 'Male'],
             ['value' => '2', 'label' => 'Female'],
             ['value' => '3', 'label' => 'Others']
         ],
         'label' => __('Gender'),
         'required' => true,
         'dataScope' => 'shippingAddress.custom_attributes.max_gender',
         'provider' => 'checkoutProvider',
         'visible' => true,
         'sortOrder' => 54,
         'id' => 'maxgender'
         ];

         $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_dob'] = [
         'component' => 'Magento_Ui/js/form/element/abstract',
         'config'	=> [
         	'customScope' => 'shippingAddress.custom_attributes',
         	'template' => 'ui/form/field',
         	'elementTmpl' => 'ui/form/element/date',
         	'id' => 'maxdob',
            'options' => [
                'changeYear'=> true,
                'changeMonth'=> true,
                'yearRange' => '1950:2050',
            ],
         ],
         'validation' => [
            'required-entry' => true
         ],
         'label' => __('Date of Birth'),
         'required' => true,
         'dataScope' => 'shippingAddress.custom_attributes.max_dob',
         'provider' => 'checkoutProvider',
         'visible' => true,
         'sortOrder' => 55,
         'id' => 'maxdob'
         ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_schedule_date'] = [
        'component' => 'Magento_Ui/js/form/element/abstract',
        'config'	=> [
            'customScope' => 'shippingAddress.custom_attributes',
            'template' => 'ui/form/field',
            'elementTmpl' => 'ui/form/element/date',
            'id' => 'max_schedule_date',
            'options' => [
                'minDate' => date('m/d/Y')
            ],
        ],
        'validation' => [
            'required-entry' => true
        ],
        'label' => __('Schedule Date'),
        'required' => true,
        'dataScope' => 'shippingAddress.custom_attributes.max_schedule_date',
        'provider' => 'checkoutProvider',
        'visible' => true,
        'sortOrder' => 97,
        'id' => 'max_schedule_date'
        ];

        // $now = date("H"); 
        // $logger->info('City Select Query'. $cityCollection->getSelect());

        $scheduleOptions = [
            ['value'=> '', 'label' => '--select--'],
            ['value'=> '12:00 AM-1:00 AM', 'label' => '12:00 AM-1:00 AM'],
            ['value'=> '1:00 AM-2:00 AM', 'label' => '1:00 AM-2:00 AM'],
            ['value'=> '2:00 AM-3:00 AM', 'label' => '2:00 AM-3:00 AM'],
            ['value'=> '3:00 AM-4:00 AM', 'label' => '3:00 AM-4:00 AM'],
            ['value'=> '4:00 AM-5:00 AM', 'label' => '4:00 AM-5:00 AM'],
            ['value'=> '5:00 AM-6:00 AM', 'label' => '5:00 AM-6:00 AM'],
            ['value'=> '6:00 AM-7:00 AM', 'label' => '6:00 AM-7:00 AM'],
            ['value'=> '7:00 AM-8:00 AM', 'label' => '7:00 AM-8:00 AM'],
            ['value'=> '8:00 AM-9:00 AM', 'label' => '8:00 AM-9:00 AM'],
            ['value'=> '9:00 AM-10:00 AM', 'label' => '9:00 AM-10:00 AM'],
            ['value'=> '10:00 AM-11:00 AM', 'label' => '10:00 AM-11:00 AM'],
            ['value'=> '11:00 AM-12:00 PM', 'label' => '11:00 AM-12:00 PM'],
            ['value'=> '12:00 PM-1:00 PM', 'label' => '12:00 PM-1:00 PM'],
            ['value'=> '1:00 PM-2:00 PM', 'label' => '1:00 PM-2:00 PM'],
            ['value'=> '2:00 PM-3:00 PM', 'label' => '2:00 PM-3:00 PM'],
            ['value'=> '3:00 PM-4:00 PM', 'label' => '3:00 PM-4:00 PM'],
            ['value'=> '4:00 PM-5:00 PM', 'label' => '4:00 PM-5:00 PM'],
            ['value'=> '5:00 PM-6:00 PM', 'label' => '5:00 PM-6:00 PM'],
            ['value'=> '6:00 PM-7:00 PM', 'label' => '6:00 PM-7:00 PM'],
            ['value'=> '7:00 PM-8:00 PM', 'label' => '7:00 PM-8:00 PM'],
            ['value'=> '8:00 PM-9:00 PM', 'label' => '8:00 PM-9:00 PM'],
            ['value'=> '9:00 PM-10:00 PM', 'label' => '9:00 PM-10:00 PM'],
            ['value'=> '10:00 PM-11:00 PM', 'label' => '10:00 PM-11:00 PM'],
            ['value'=> '11:00 PM-12:00 AM', 'label' => '11:00 PM-12:00 AM']
        ];

         $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_schedule'] = [
         'component' => 'Magento_Ui/js/form/element/select',
         'config'	=> [
         	'customScope' => 'shippingAddress.custom_attributes',
         	'template' => 'ui/form/field',
         	'elementTmpl' => 'ui/form/element/select',
         	'id' => 'max_schedule',
            'options' => $scheduleOptions
         ],
         'validation' => [
            'required-entry' => true
         ],
         'options' => $scheduleOptions,
         'label' => __('Schedule Time'),
         'required' => true,
         'dataScope' => 'shippingAddress.custom_attributes.max_schedule',
         'provider' => 'checkoutProvider',
         'visible' => true,
         'sortOrder' => 98,
         'id' => 'max_schedule'
         ];

        

        //Reset
        unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['city']);

         $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_city_id'] = [
         'component' => 'Magento_Ui/js/form/element/select',
         'config'	=> [
         	'customScope' => 'shippingAddress.custom_attributes',
         	'template' => 'ui/form/field',
         	'elementTmpl' => 'ui/form/element/select',
         	'options' => [
                 ['value' => '11', 'label' => 'Delhi'],
                 ['value' => '12', 'label' => 'Noida'],
                 ['value' => '13', 'label' => 'Gurugram']
             ],
         	'id' => 'maxcity'
         ],
         'validation' => [
            'required-entry' => true
         ],
         'options' => [
             ['value' => '11', 'label' => 'Delhi'],
             ['value' => '12', 'label' => 'Noida'],
             ['value' => '13', 'label' => 'Gurugram']
         ],
         'label' => __('City'),
         'required' => true,
         'dataScope' => 'shippingAddress.custom_attributes.max_city_id',
         'provider' => 'checkoutProvider',
         'visible' => false,
         'sortOrder' => 59,
         'id' => 'maxcity'
         ];

         //reset city
         $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = [
         'component' => 'Magento_Ui/js/form/element/select',
         'config'	=> [
         	'customScope' => 'shippingAddress',
         	'template' => 'ui/form/field',
         	'elementTmpl' => 'ui/form/element/select',
         	'options' => $cityOption,
         	'id' => 'maxcity'
         ],
         'validation' => [
            'required-entry' => true
         ],
         'options' => $cityOption,
         'label' => __('City'),
         'required' => true,
         'dataScope' => 'shippingAddress.city',
         'provider' => 'checkoutProvider',
         'visible' => true,
         'sortOrder' => 100,
         'id' => 'maxcity'
         ];

         

         $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_id'] = [
         'component' => 'Magento_Ui/js/form/element/abstract',
         'config'	=> [
         	'customScope' => 'shippingAddress.custom_attributes',
         	'template' => 'ui/form/field',
         // 	'elementTmpl' => 'ui/form/element/date',
         	'id' => 'max-id',
             'options' => [],
         ],
         'validation' => [
            'required-entry' => true
         ],
         'label' => __('Max ID'),
         'required' => false,
         'dataScope' => 'shippingAddress.custom_attributes.max_id',
         'provider' => 'checkoutProvider',
         'visible' => false,
         'sortOrder' => 57,
         'id' => 'max-id'
         ];


         $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_city_name'] = [
         'component' => 'Magento_Ui/js/form/element/abstract',
         'config'	=> [
         	'customScope' => 'shippingAddress.custom_attributes',
         	'template' => 'ui/form/field',
         // 	'elementTmpl' => 'ui/form/element/date',
         	'id' => 'max-city-name',
             'options' => [],
         ],
         'validation' => [
            'required-entry' => true
         ],
         'label' => __('Max City'),
         'required' => false,
         'dataScope' => 'shippingAddress.custom_attributes.max_city_name',
         'provider' => 'checkoutProvider',
         'visible' => false,
         'sortOrder' => 52,
         'id' => 'max-city-name'
         ];

         $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['country_id']['visible'] = false;

         //Unset fields
         unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
         ['shippingAddress']['children']['shipping-address-fieldset']['children']['company']); //%path_to_target_node% is the path to the component's node in checkout_index_index.xml
        //  unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        //  ['shippingAddress']['children']['shipping-address-fieldset']['children']['city']); //%path_to_target_node% is the path to the component's node in checkout_index_index.xml

        // if($mobileNo){
        //     $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        //     ['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone']['value'] = $mobileNo;
        // }
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone']['disabled'] = 'disabled';
        
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone']['sortOrder'] = 99;

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['region']['sortOrder'] = 110;
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']['sortOrder'] = 111;

        // if($patientData.MaxID){
        //     $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        //     ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_id']['value'] = $patientData.MaxID;
        // }
        // if($patientData.DateOfBirth){
        //     $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        //     ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_dob']['value'] = $patientData.DateOfBirth;
        // }
        // if($patientData.EmailID){
        //     $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        //     ['shippingAddress']['children']['shipping-address-fieldset']['children']['email']['value'] = $patientData.EmailID;
        // }
        // if($patientData.GenderID){
        //     $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        //     ['shippingAddress']['children']['shipping-address-fieldset']['children']['max_gender']['value'] = $patientData.GenderID;
        // }

        return $jsLayout;

      }
  }
