<?php
/**
 *
 * Copyright © 2015 Ipragmatechcommerce. All rights reserved.
 */
namespace Ipragmatech\Ipcheckout\Controller\Index;

class Sendotp extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\View\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     *@var Ipragmatech\Ipcheckout\Helper\Data
     */
     protected $helper;

    /*
     * \Magento\Customer\Model\Session
     */
     protected $customerSession;



    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Ipragmatech\Ipcheckout\Helper\Data
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory    $resultJsonFactory,
        \Ipragmatech\Ipcheckout\Helper\Data $helper,
        \Magento\Customer\Model\Session $customerSession

    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        $this->customerSession = $customerSession;
    }

    /**
     * Flush cache storage
     *
     */
    public function execute()
    {
        //temp start
        $result = $this->resultJsonFactory->create();

        //Temp end
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/otp.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        //$this->resultPage = $this->resultPageFactory->create();
		// return $this->resultPage;

        //mobileNumber
        $params = $this->getRequest()->getParams();
        $mobileNumber = $params['mobileNumber'];
        $mobileNumber = '+91'.$mobileNumber;

        try{

            $otp = rand(100000,999999);
            $otpCollection = $this->_objectManager->create('Ipragmatech\Ipcheckout\Model\Ipotp')->getCollection()
                        ->addFieldToFilter('mobileno',array ('like' => '%' . $mobileNumber . '%'));
            //$logger->info('Mobile Query>>'.$otpCollection->getSelect());
            if(count($otpCollection)){
                $logger->info('Going to Update Mobile NO :'.$mobileNumber.', and OTP >>'. $otp);
                foreach ($otpCollection as $item){
                    $modelOtp = $this->_objectManager->create('Ipragmatech\Ipcheckout\Model\Ipotp')->load($item->getId());
                    $modelOtp->setOtpCode($otp);
                    $modelOtp->setStatus(0);
                    $modelOtp->save();
                }
            }
            else{
                $logger->info('Going to save Mobile NO :'.$mobileNumber.', and OTP >>'. $otp);
                $modelOtp = $this->_objectManager->create('Ipragmatech\Ipcheckout\Model\Ipotp');
                $modelOtp->setOtpCode($otp);
                $modelOtp->setMobileno($mobileNumber);
                $modelOtp->setStatus(0);
                $modelOtp->save();
            }
            //send OTP on mobile code
            //$accountSid = $this->_objectManager->create('Ipragmatech\Ipcheckout\Helper\Data')->getConfig('twiliosetting/frontend_display/account_sid');
            $accountSid   = $this->helper->getConfig('twiliosetting/general/account_sid');
            $authToken    = $this->helper->getConfig('twiliosetting/general/auth_token');
            $twilioNumber = $this->helper->getConfig('twiliosetting/general/twilio_number');
            $toNumber = $mobileNumber ; //'+91 88020 45390';
            $message = "Please use the OTP ".$otp." to verify your mobile number"; //"Your One Time Password is ".$otp; // pls ignore manish";

            //Step 3: instantiate a new Twilio Rest Client
            $client = new \Services_Twilio($accountSid,$authToken);

            try{
                //Services'Twilio/Rest
                // $sms = $client->account->messages->sendMessage(
                //         $twilioNumber,
                //         $toNumber,
                //         $message
                // );

                //Bulk SMS gateway
                $smsStatus = $this->sendSMS(substr($toNumber,3), $message);

                //$smsStatus = true;
                $logger->info('smsStatus: '.$smsStatus);
                if($smsStatus){
                    $this->customerSession->setMobileNumber($mobileNumber);
                    $temp['status'] = true;
                    $temp['msg'] = 'OTP has been successfully sent to your number.';
                    return $result->setData($temp);
                }else {
                    $temp['status'] = false;
                    $temp['errorMsg'] = 'We can\'t send the otp, something went wrong.';
                    return $result->setData($temp);
                }
            }
            catch (\Exception $e) {

                $logger->info("Ex2". $e->getMessage());
                //$this->messageManager->addException($e, __('We can\'t send the otp, something went wrong'));
                $temp['status'] = false;
                $temp['errorMsg'] = 'We can\'t send the otp, something went wrong.';
                return $result->setData($temp);
            }
        }catch (\Exception $e) {

            $logger->info("Ex3". $e->getMessage());
            $temp['status'] = false;
            $temp['errorMsg'] = 'We can\'t send the otp, something went wrong.';
            return $result->setData($temp);
            //$this->messageManager->addException($e, __('We can\'t send the otp, something went wrong'));
        }

    }

    /*
     * use bulk sms to send message
     */
    public function sendSMS($mobileNumber, $message){

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/sms_error.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('SMS BULT OTP sending...');

        $textmessage = urlencode($message);
        //$logger->info('Text message:' + $textmessage);

        try{
            $url = "https://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=367046&username=9654768393&password=Rahul@123&To=".$mobileNumber."&Text=".$textmessage."&senderid=MAXLAB";
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
