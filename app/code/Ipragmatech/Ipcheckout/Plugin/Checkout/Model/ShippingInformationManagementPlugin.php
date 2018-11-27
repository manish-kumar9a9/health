<?php
namespace Ipragmatech\Ipcheckout\Plugin\Checkout\Model;


class ShippingInformationManagementPlugin
{
    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/otp.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info("Checkout Plugins");

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        //$patientData = $customerSession->getPatients();
        $patientData = $customerSession->getCurrentPatients();
        $logger->info(json_encode($patientData));
        $logger->info('max id:');
        $maxId = '';
        if($patientData){
            $maxId = $patientData['MaxID'];
        }

        $extAttributes = $addressInformation->getExtensionAttributes();
        $logger->info("Ex attibutes...");
        $logger->info(json_encode((array)$extAttributes));
        $maxId = $extAttributes->getMaxId();
        $cityId = $extAttributes->getMaxCityId();
        $cityName = $extAttributes->getMaxCityName();
        $gender = $extAttributes->getMaxGender();
        $dob = $extAttributes->getMaxDob();
        $schedule = $extAttributes->getMaxSchedule();
        $scheduleDate = $extAttributes->getMaxScheduleDate();

        $logger->info("Checkout Plugins>".$maxId.">".$cityName.">>".$cityId.">".$gender.">".$dob.">".$scheduleDate.':'.$schedule);

        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setMaxId( $maxId );
        $quote->setMaxCityId( $cityId );
        $quote->setMaxGender( $gender);
        $quote->setMaxDob( $dob );
        $quote->setMaxCityName( $cityName );
        $quote->setMaxSchedule( $schedule );
        $quote->setMaxScheduleDate( $scheduleDate );

        //$logger->info(print_r($quote,true));

    }
}
