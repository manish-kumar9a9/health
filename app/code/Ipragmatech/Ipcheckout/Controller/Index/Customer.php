<?php
/**
 *
 * Copyright © 2015 Ipragmatechcommerce. All rights reserved.
 */
namespace Ipragmatech\Ipcheckout\Controller\Index;


class Customer extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\View\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * \Magento\Customer\Model\Session
     */
     protected $customerSession;

    /**
     * @var Ipragmatech\Ipcheckout\Helper\Data
     */
     protected $helper;


    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
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
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheState = $cacheState;
        $this->cacheFrontendPool = $cacheFrontendPool;
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
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/otp.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info("CUSTOMER OTP from controller.....");

        $response = $this->resultJsonFactory->create();
        $params = $this->getRequest()->getParams();
        $cpMaxId = (isset($params['cpMaxId']) ? $params['cpMaxId'] : '');

		$patients = $this->customerSession->getPatients();
		$mobileNumber = $this->customerSession->getMobileNumber();
		$logger->info('from session:'.json_encode($patients));
		//$logger->info('Mobile Number From Session:'.$mobileNumber.', OTP Code:'.$params['otpCode']);
		$logger->info('Cp MAX ID SELECTED:'.$cpMaxId);

        //check max id
        if(!$cpMaxId){
            $logger->info('Herer ===== 0');
            $temp['status'] = true;
            $temp['msg'] = 'No any patient is selected.';
            $temp['data'] = array();
            $temp['mobile'] = $mobileNumber;
            $this->customerSession->unsCurrentPatients();
            return $response->setData($temp);
        }

        //Chekc mobile in Session
		if (!count($patients) || !isset($patients) || $patients == ""){
			//$resultRedirect->setUrl($this->_storeManager->getStore()->getBaseUrl().'customer/account/create/');
            $temp['status'] = true;
            $temp['msg'] = 'Your are adding new customer';
            $temp['mobile'] = $mobileNumber;
            $temp['data'] = [];
            return $response->setData($temp);

		}else{

            $temp['status'] = true;  //make true before deploy
            $temp['msg'] = 'Thanks for selecting please fill shipping address';
            $temp['mobile'] = $mobileNumber;

            //setlogin customer or set selected patients
            $curpatient = [];
            foreach ($patients as $patient) {
                $logger->info('pateint Id:'.$patient['MaxID'].' , cpMaxID'.$cpMaxId);

                if($patient['MaxID'] == $cpMaxId ){
                    $logger->info('Current patients seted...');
                    $curpatient = $patient;
                    $this->customerSession->setCurrentPatients($curpatient);
                }
            }
            $temp['data'] = $curpatient;
            return $response->setData($temp);
		}

        //make curel request
        //$data = [];
        //$this->fetchPatient($data);

		//return $response->setData($temp);
    }

    /**
     * //Make curl and fetch patient data
     */

    public function setCustomerLogin($data){

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/otp.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info("setCustomerLogin for max lab.....");

        // {
        // 	"MaxID":"",
        // 	"MobileNo": "9818759151",
        // 	"Source": "19153"
        // }

        // set post fields
        $post = array(
            "MaxID" => "",
            "MobileNo" => "9818759151",
            "Source"   => 19153
        );
        $payload = json_encode( $post );
        $logger->info('payload'.$payload);
        //$logger->info('payload');
        try{
            $ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, "https://crmcare.maxhealthcare.in/api/TGetDetails/PatientSearch");
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
            // $err     = curl_errno($ch);
            // $errmsg  = curl_error($ch) ;
            // $header  = curl_getinfo($ch);

            //$logger->info("err: ".$err);
            //$logger->info("Err Msg: ".$errmsg);

            $logger->info('Curl Result for search user by mobile');
            //$logger->info(json_decode($result, true));
            $logger->info('-------------------1----------------------');
            $logger->info($resultData['statusMessage']);
            $logger->info('-------------------2----------------------');
            //$logger->info(gettype($resultData['statusMessage']));
            $statusMessage = json_decode($resultData['statusMessage'], true);
            $patients = $statusMessage['Patients'];
            //$logger->info($statusMessage['Patients']);
            $logger->info($statusMessage['Patients']);
    		// close the request
    		curl_close($ch);

            return $patients;

        }catch(Exception $e){
            $logger->info("Ex4". $e->getMessage());
        }
    }
}
