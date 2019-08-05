<?php
/**
 * Solwin Infotech
 * Solwin Contact Form Widget Extension
 *
 * @category   Solwin
 * @package    Solwin_Contactwidget
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/
 */
namespace Ipragmatech\Contactme\Controller\Index;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Area;
use Ipragmatech\Contactme\Model\Contactme;
class Save extends \Magento\Framework\App\Action\Action
{

    const EMAIL_TEMPLATE = 'contactme_section/emailsend/emailtemplate';
    const EMAIL_SENDER = 'contactme_section/emailsend/emailsenderto';
    const XML_PATH_EMAIL_RECIPIENT = 'contactme_section/emailsend/emailto';
    //const REQUEST_URL = 'https://www.google.com/recaptcha/api/siteverify';
    //const REQUEST_RESPONSE = 'g-recaptcha-response';

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Solwin\Contactwidget\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var Ipragmatech\Contactme\Model\Contactme;
     **/
    protected $_contactmeModel;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Solwin\Contactwidget\Helper\Data $helper,
        \Ipragmatech\Contactme\Model\ContactmeFactory $contactme
    ) {
        parent::__construct($context);
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_scopeConfig = $scopeConfig;
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
        $this->_contactmeModel = $contactme;
    }

    public function execute() {

      $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/mylog.log');
          $logger = new \Zend\Log\Logger();
          $logger->addWriter($writer);
          $logger->info("Here your cmment");


        $remoteAddr = filter_input(
                INPUT_SERVER,
                'REMOTE_ADDR',
                FILTER_SANITIZE_STRING
                );
        $data = $this->getRequest()->getParams();
        //print_r($data, true);die();
        $logger->info(json_encode($data));
        $resultRedirect = $this->resultRedirectFactory->create();
        $redirectUrl = $data['currUrl'];
        // $secretkey = $this->_helper
        //         ->getConfigValue(
        //                 'contactwidget_section/recaptcha/recaptcha_secretkey'
        //                 );
        // $captchaErrorMsg = $this->_helper
        //         ->getConfigValue(
        //                 'contactwidget_section/recaptcha/recaptcha_errormessage'
        //                 );

        // if ($data['enablerecaptcha']) {
        //     if ($captchaErrorMsg == '') {
        //         $captchaErrorMsg = 'Invalid captcha. Please try again.';
        //     }
        //     $captcha = '';
        //     if (filter_input(INPUT_POST, 'g-recaptcha-response') !== null) {
        //         $captcha = filter_input(INPUT_POST, 'g-recaptcha-response');
        //     }
        //
        //     if (!$captcha) {
        //         $this->messageManager->addError($captchaErrorMsg);
        //         return $resultRedirect->setUrl($redirectUrl);
        //     } else {
        //         $response = file_get_contents(
        //                 "https://www.google.com/recaptcha/api/siteverify"
        //                 . "?secret=" . $secretkey
        //                 . "&response=" . $captcha
        //                 . "&remoteip=" . $remoteAddr);
        //         $response = json_decode($response, true);
        //
        //         if ($response["success"] === false) {
        //             $this->messageManager->addError($captchaErrorMsg);
        //             return $resultRedirect->setUrl($redirectUrl);
        //         }
        //     }
        // }

        try {

            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($data);

            $error = false;

            if (!\Zend_Validate::is(trim($data['name']), 'NotEmpty')) {
                $error = true;
            }

            if (!\Zend_Validate::is(trim($data['email']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($data['telephone']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($data['packagename']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($data['city_name']), 'NotEmpty')) {
                $error = true;
            }

            if ($error) {
                throw new \Exception();
            }
            $packageArray  = array(
              "WWTP"=>"WellWise Total Profile",
              "WWEP"=>"WellWise Essential Profile",
              "WWAP"=>"WellWise Advanced Profile",
              "OTHERS"=>"Some Other Test",
              "MFPB"=>"Max Fever Panel (Basic)",
              "MFPC"=>"Max Fever Panel (Comprehensive)",
              "MDCP"=>"Max Dengue/chikungunya Panel",
              "MDP"=>"Max Dengue Panel",
              "CBC"=>"CBC (Complete Blood Count)",
              "MCP"=>"Max Chikungunya Panel",
              "MFP"=>"Max Fever Panel (Fever &gt; 1 Week)",
              "MFPO"=>"Max Fever Of Unknown Origin Panel (Fever &gt; 3 Weeks)",
              "MAWP"=>"Max Adolescent Wellness Profile",
              "MBP"=>"Max Bone Profile",
              "MCARP"=>"Max Cardiac Profile",
               "DSP"=>"Diabetes Screening Profile",
              "DSP2"=>"Diabetes Screening Profile 2",
              "MDP1"=>"Max Diabetes Profile",
              "MDEP"=>"Max Diabetes Extended Profile",
              "MNP"=>"Max Nutrition Profile",
              "MEP"=>"Max Electrolyte Panel",
              "MKHPC"=>"Max Kidney Health Profile- Comprehensive",
              "MCS"=>"Max Cervical Screening",
              "LP"=>" Lipid Profile",
              "LFT"=>"Liver Function Test",
              "MVPB"=>" Max - Vitamine Panel (basic)"
            );
            $selectedPackageName = $packageArray[trim($data['packagename'])];
            //save  to DB
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $objDate = $objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
            $date = $objDate->gmtDate();
            $contactData = array(
              'name' => trim($data['name']),
              'email' => trim($data['email']),
              'mobile' => trim($data['telephone']),
              'test_code' => trim($data['packagename']),
              'test_name' => $selectedPackageName,
              'city_name' => trim($data['city_name']),
              'query' => isset($data['query']) ? trim($data['query']) : '',
              'creation_time'=> $date
            );

            $postObject['test_code'] = trim($data['packagename']);
            $postObject['test_name'] = $selectedPackageName;
            $postObject['query'] = isset($data['query']) ? trim($data['query']) : '';


            $timezoneInterface = $this->_objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
            $bdate = $timezoneInterface->date($date)->format('m/d/Y');

            $postObject['creation_time'] = $bdate;

            $contactModel = $this->_contactmeModel->create();
            $contactModel->setData($contactData);
            $contactModel->save();

            //set Session
            //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $maxOfferCustomerSession = $objectManager->get('Magento\Customer\Model\Session');
            $maxOfferCustomerSession->setOfferEmail(trim($data['email']));
            $maxOfferCustomerSession->setOfferPhone(trim($data['telephone']));
            $maxOfferCustomerSession->setOfferUtm(trim($data['utm_campaign']));

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $sendToString = $this->_scopeConfig->getValue(
                    self::XML_PATH_EMAIL_RECIPIENT, $storeScope
                  );
            $sendToArray = (explode(",",$sendToString));
            $logger->info('after save:'. json_encode($sendToArray));
            // send mail to recipients
            $this->_inlineTranslation->suspend();
            $transport = $this->_transportBuilder->setTemplateIdentifier(
                            $this->_scopeConfig->getValue(
                                    self::EMAIL_TEMPLATE,
                                    $storeScope
                                    )
                    )->setTemplateOptions(
                            [
                                'area' => Area::AREA_FRONTEND,
                                'store' => $this->_storeManager
                                        ->getStore()
                                        ->getId(),
                            ]
                    )->setTemplateVars(['data' => $postObject])
                    ->setFrom($this->_scopeConfig->getValue(
                            self::EMAIL_SENDER, $storeScope
                            ))
                    ->addTo($sendToArray)
                    ->getTransport();

            $transport->sendMessage();
            $this->_inlineTranslation->resume();

            //$this->messageManager->addSuccess(__('Thanks for the details. Our customer care executive will contact you shortly.'));
            //return $resultRedirect->setUrl($redirectUrl);
            return $resultRedirect->setUrl('https://maxlab.co.in/offer/thankyou');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $logger->info("Ex1: ".$e->getMessage());
            $this->messageManager->addError($e->getMessage());
            return $resultRedirect->setUrl($redirectUrl);

        } catch (\RuntimeException $e) {
          $logger->info("Ex2: ".$e->getMessage());
            $this->messageManager->addError($e->getMessage());
            return $resultRedirect->setUrl($redirectUrl);
        } catch (\Exception $e) {
            $logger->info("Ex3: ".$e->getMessage());
            $this->_inlineTranslation->resume();
            $this->messageManager->addException($e, __('Something went wrong '
                    . 'while sending the contact us request.'));
                    return $resultRedirect->setUrl($redirectUrl);
        }

    }

}
