<?php

namespace Ipragmatech\Ipcheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Generatebill implements ObserverInterface {


    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Order Model
     *
     * @var \Magento\Sales\Model\Order $order
     */
    protected $order;

    /** * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderModel;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;

    /**
     * @var \Magento\Checkout\Model\Session $checkoutSession
     */
    protected $checkoutSession;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectmanager
     * @param \Magento\Sales\Model\OrderFactory $orderModel
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Sales\Model\Order $order,
        \Magento\Sales\Model\OrderFactory $orderModel,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        \Magento\Checkout\Model\Session $checkoutSession
    ){
        $this->_objectManager = $objectmanager;
        $this->order = $order;
        $this->orderModel = $orderModel;
        $this->orderSender = $orderSender;
        $this->checkoutSession = $checkoutSession;
    }


    public function execute(\Magento\Framework\Event\Observer $observer) {


        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/max.log');
    		$logger = new \Zend\Log\Logger();
    		$logger->addWriter($writer);
          $logger->info('checkout Success Observer');

    		$event = $observer->getEvent();
    		$order = $observer->getEvent()->getOrder();
        $orderids = $observer->getEvent()->getOrderIds();
        $logger->info('Order Ids: '.json_encode($orderids));

        //stop sending email if payment not made
        if(count($orderids))
        {
            $this->checkoutSession->setForceOrderMailSentOnSuccess(true);
            $order = $this->orderModel->create()->load($orderids[0]);
            $this->orderSender->send($order, true);
        }

        foreach($orderids as $orderid){

            $smsParams = [];
            $logger->info('Current Order: '.$orderid);
            $order = $this->order->load($orderid);

            //$logger->info(json_encode($event->toArray()));
            $logger->info('Order Data: '.json_encode($order->getData()));
            $items =$order->getAllItems();
            $scheduledDate = $order->getMaxScheduleDate();
            $incrementId = $order->getIncrementId();

            //service Data
            $serviceData = [];
            foreach($items as $item) {
                $logger->info('Serive for SKU: '.$item->getSku());
                $service = array(
                    "ServiceID" => 41,
                    "ServiceItemID"=> $item->getSku(),
                    "SpecimenID"=> 7,
                    "Amount"=>  round($item->getRowTotalInclTax()),
                    "OrderQty"=> round($item->getQtyOrdered()),
                    "DiscountAmount"=> 0,
                    "DiscountID"=> 0,
                );
                $serviceData[] = $service;
            }
            $logger->info('Serive: '.json_encode($serviceData));

            $shippingAddress = $order->getShippingAddress();
            $payment = $order->getPayment();
            // $logger->info('Shipping Address:');
            // $logger->info(json_encode($shippingAddress->getData()));
            // $logger->info('payment');
             $logger->info('Payment Data: '.json_encode($payment->getData()));
             $logger->info('Payment Method: '.$payment->getMethod());

            $paymentMethod = $payment->getMethod();
            $timezoneInterface = $this->_objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
            $bdate = $timezoneInterface->date($order->getMaxDob())->format('m/d/Y');

            $paymentData = array(
                // "Amount"=>$order->getGrandtotal(),
                "Amount"=>round($payment->getAmountOrdered()),
                "PaymentMode"=>"OP",
                "CNumber"=> substr($shippingAddress->getTelephone(),3),
                "ApprovalNo"=>$payment->getLastTransId()
                //"BankName"=>"TEST",
                // "BranchName"=>null,
                // "City"=>null,
                //"Name"=>"haraha n"
            );


            $customerData = array(
                'email' => $shippingAddress->getEmail(),
                'fname' => $shippingAddress->getFirstname(),
                'lanme' => $shippingAddress->getLastname(),
                'street' => $shippingAddress->getStreet(),
                'city' => $shippingAddress->getCity(),
                'gender' => $order->getMaxGender(),
                'dob' => $bdate,
                'mobile' => substr($shippingAddress->getTelephone(),3),
                'postalcode' => $shippingAddress->getPostcode(),
            );

            $smsParams['mobile'] =  substr($shippingAddress->getTelephone(),3);
            $smsParams['fname'] =  $shippingAddress->getFirstname();
            $smsParams['lanme'] =  $shippingAddress->getLastname();
            $smsParams['orderID'] =  $orderid;
            $smsParams['scheduleDate'] =  $scheduledDate;
            $smsParams['incrementId'] =  $incrementId;
            //$logger->info(json_encode($customerData));


            $curentMaxId = $order->getMaxId();
            $logger->info('Current Max ID:'. $curentMaxId);
            $logger->info('Last transaction ID:'.$payment->getLastTransId());
            $logger->info('Transaction ID:'.$payment->getTransactionId());
            //if( $paymentMethod == 'payu' && $payment->getLastTransId() ){
            if( true ){

                $logger->info( 'Payment method is Pay where tansaction id:'.$payment->getLastTransId().' And Max id from order:'.$curentMaxId );
                if(!$curentMaxId){
                    $response = $this->register( $customerData );
                    $registerData = json_decode($response, true);
                    $logger->info('Register Data');
                    $logger->info(json_encode($registerData));

                    if( $registerData['Status'] == 'Success'){
                        $curentMaxId = $registerData['MaxID'];
                        $comment = 'Max Registration: Max ID created after new registration:'.$registerData['MaxID'].'Message:'.$registerData['Message'];
                        $order->setMaxId($registerData['MaxID'])->save();
                        $order->setMaxHosName('Max Panchsheel Saket')->save();

                        $order->addStatusHistoryComment($comment)
                                ->setIsVisibleOnFront(false)
                                ->setIsCustomerNotified(false)
                                ->save();

                    }else{
                        $comment = 'Max Registration: '.$registerData['Message'];
                        $order->setMaxHosName('Max Panchsheel Saket')->save();

                        $order->addStatusHistoryComment($comment)
                                ->setIsVisibleOnFront(false)
                                ->setIsCustomerNotified(false)
                                ->save();
                    }
                }
                //generate Bill
                //if( $curentMaxId ){
                if( true ){
                    $commanData = array(
                        'UserID' => 19153,
                        'HospitalID' => 9,
                        'MaxID' => $curentMaxId,
                        'CompanyID' => 0,
                        'EMailID' => $shippingAddress->getEmail(),
                        'DepositAmount' => 0,
                        'RefDoctorID' => 2015,
                        'Source' => 19153,
                        'IsPreBill' => 1
                    );
                    $billResponse = $this->saveBill($commanData, $serviceData, $paymentData);
                    $billData = json_decode($billResponse, true);
                    $logger->info('Bill Data');
                    $logger->info(json_encode($billData));
                    if( $billData['Status'] == 'Success'){
                        $comment = 'Max Bill Generation: BillNo-'.$billData['BillNo'].', BillId-'.$billData['BillId'].', Message-'.$billData['Message'];


                        $order->setMaxHosName('Max Panchsheel Saket')
                            ->setMaxBillId($billData['BillId'])
                            ->setMaxBillNo($billData['BillNo'])
                            ->save();


                        $order->addStatusHistoryComment($comment)
                                ->setIsVisibleOnFront(false)
                                ->setIsCustomerNotified(false)
                                ->save();

                    }else{
                        $comment = 'Max Registration: '.$billData['Message'];
                        $order->setMaxHosName('Max Panchsheel Saket')->save();
                        $order->addStatusHistoryComment($comment)
                                ->setIsVisibleOnFront(false)
                                ->setIsCustomerNotified(false)
                                ->save();
                    }
                }
            }
            //send sms to customer
            $this->sendOrderSMS($smsParams);
        }
        //$order_information = $orderFactory->create()->load($orderId);


    }

    /**
     * // Register customer
     */
    public function register($customerData){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/max.log');
  		$logger = new \Zend\Log\Logger();
  		$logger->addWriter($writer);
        $logger->info('Initiating CURL to register user');


        $customerPayload = [
            "FirstName"=>$customerData['fname'],
        	"LastName"=> $customerData['lanme'],
        	"Address"=> $customerData['street'],
        	"MobileNo"=> $customerData['mobile'],
        	"Gender"=> $customerData['gender'],
        	"Email"=> $customerData['email'],
        	"DateOfBirth"=> $customerData['dob'],
        	"CityID"=>$customerData['city'],
        	"ZipCode"=> $customerData['postalcode'],
        	"LocationID"=> "9",
        	"UserID"=>19153,
        	"Source"=>19153
        ];

        $payload = json_encode($customerPayload);

        $logger->info('payload Befoer Curl Customer Register >> '.$payload);
        try{
            $ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, "https://crmcare.maxhealthcare.in/api/TGetDetails/PatientReg");
    		curl_setopt($ch, CURLOPT_HEADER, false);
    		//curl_setopt($ch, CURLOPT_HEADER, array('Content-Type'=>'application/json'));
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
    		curl_setopt($ch, CURLOPT_POST, true);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
            );


    		$result = curl_exec($ch);
            $resultData = json_decode($result, true);

            //Debug Error Message
            $err     = curl_errno($ch);
            $errmsg  = curl_error($ch) ;
            $header  = curl_getinfo($ch);

            $logger->info("Curl Err: ".$err);
            $logger->info("Curl Err Msg: ".$errmsg);

            $logger->info('Curl Result for Register:');
            $logger->info('-------------------1----------------------');
            $logger->info($resultData);
            $logger->info('-------------------2----------------------');
            //$logger->info(gettype($resultData['statusMessage']));
            //$response = json_decode($resultData, true);
            $logger->info('Final Response after User storage:');

    		// close the request
    		curl_close($ch);

            return $resultData['statusMessage'];

        }catch(Exception $e){
            $logger->info(" Curl Register Excption:". $e->getMessage());
        }

    }

    /**
     * // Generate Bill
     */
    public function saveBill($commanData, $serviceData, $paymentData){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/max.log');
  		$logger = new \Zend\Log\Logger();
  		$logger->addWriter($writer);
        $logger->info('Initiating CURL to Bill Generation');


        $data = array(
            'Common' => $commanData,
            'ServiceDetails' => array('Service' => $serviceData ),
            'PaymentMode' => array($paymentData),
        );

        $payload = json_encode($data);

        $logger->info('payload Befoer Curl Bill Generation : '.$payload);
        try{
            $ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, "https://crmcare.maxhealthcare.in/api/TGetDetails/SaveBillOP");
    		curl_setopt($ch, CURLOPT_HEADER, false);
    		//curl_setopt($ch, CURLOPT_HEADER, array('Content-Type'=>'application/json'));
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
    		curl_setopt($ch, CURLOPT_POST, true);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
            );


    		$result = curl_exec($ch);
            $resultData = json_decode($result, true);

            //Debug Error Message
            $err     = curl_errno($ch);
            $errmsg  = curl_error($ch) ;
            $header  = curl_getinfo($ch);

            $logger->info("Curl Err: ".$err);
            $logger->info("Curl Err Msg: ".$errmsg);

            $logger->info('Curl Result for Bill Generation:');
            $logger->info('-------------------1----------------------');
            $logger->info($resultData);
            $logger->info('-------------------2----------------------');
            //$logger->info(gettype($resultData['statusMessage']));
            //$response = json_decode($resultData, true);
            $logger->info('Final Response after Bill Generation:');

    		// close the request
    		curl_close($ch);

            return $resultData['statusMessage'];

        }catch(Exception $e){
            $logger->info(" Curl Bill Generation Excption:". $e->getMessage());
        }
    }

    public function sendOrderSMS( $smsParams ){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/sms.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('ORDER SMS BULT OTP sending...');
        $logger->info(json_encode($smsParams));

        // $smsParams['mobile'] =  substr($shippingAddress->getTelephone(),3);
        // $smsParams['fname'] =  $shippingAddress->getFirstname();
        // $smsParams['lanme'] =  $shippingAddress->getLastname();
        // $smsParams['orderID'] =  $orderid;
        // $smsParams['scheduleDate'] =  $scheduledDate;

        $message = 'Confirmation: Hi '.$smsParams['fname'].' '.$smsParams['lanme'].', your appointment '.$smsParams['incrementId'].' with Max Lab has been booked. We will service you on '.$smsParams['scheduleDate'].'. View order: https://www.maxlab.co.in. For further queries or cancellation, call 8744888888';
        $textmessage = urlencode($message);
        //$logger->info('Text message:' + $textmessage);

        try{
            $url = "https://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=367046&username=9654768393&password=Rahul@123&To=".$smsParams['mobile'] ."&Text=".$textmessage."&senderid=MAXLAB";
            $logger->info('Send OTP URL: '.$url);

            // create a new cURL resource
            $ch = curl_init();

            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // grab URL and pass it to the browser
            $result = curl_exec($ch);

            //Debug Error Message
            $err     = curl_errno($ch);
            $errmsg  = curl_error($ch) ;
            // $header  = curl_getinfo($ch);

            $logger->info("err: ".$err);
            $logger->info("Err Msg: ".$errmsg);

            $xml = simplexml_load_string($result);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);

            //$logger->info("SMS OTP Response".$result);
            $logger->info("SMS OTP Response array".json_encode($array));
            // close cURL resource, and free up system resources
            curl_close($ch);

            return true;

        }catch(Exception $e){
            return false;
            $logger->info("Exception while sending OTP via SMS: ".$e);
        }
    }

}
