<?php

namespace  FME\Contactus\Block;

use Magento\Framework\View\Element\Template;

//use Magento\Framework\ObjectManagerInterface;

class Contactblock extends Template
{
    
   
    protected $_storeInfo;

    public function __construct(Template\Context $context, array $data = [], \FME\Contactus\Helper\Data $myModuleHelper, \Magento\Store\Model\Information $storeInfo)
    {
        
        parent::__construct($context, $data);
        $this->_mymoduleHelper = $myModuleHelper;
        $this->_storeInfo = $storeInfo;
        $this->_isScopePrivate = true;
    }

    protected function _prepareLayout()
    {
    
    
    
        parent::_prepareLayout();
        if ($this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]) == $this->getUrl('contactus/front/index', ['_secure' => true])) {
            $this->pageConfig->getTitle()->set($this->_mymoduleHelper->metatittle());
            $this->pageConfig->setKeywords($this->_mymoduleHelper->metakeyword());
            $this->pageConfig->setDescription($this->_mymoduleHelper->metadescription());
        }
    

    
        return $this;
    }

    
    public function getFormAction()
    {
        return $this->getUrl('contactus/front/save', ['_secure' => true]);
    }

    public function isimage()
    {
        return $this->_mymoduleHelper->isNewsImageEnabled();
    }

    public function isContactEnabled()
    {
        return $this->_mymoduleHelper->isContactEnabled();
    }


    public function isCaptchaEnabled()
    {
         return $this->_mymoduleHelper->isCaptchaEnabled();
    }
    public function getsitekey()
    {
         return $this->_mymoduleHelper->getsitekey();
    }
    public function getsecurekey()
    {
         return $this->_mymoduleHelper->getsecurekey();
    }

    public function isMapEnabled()
    {
         return $this->_mymoduleHelper->isMapEnabled();
    }
    public function getmapkey()
    {
         return $this->_mymoduleHelper->getmapkey();
    }

    public function metatittle()
    {
        return $this->_mymoduleHelper->metatittle();
    }

    public function metakeyword()
    {
         return $this->_mymoduleHelper->metakeyword();
    }
    public function metadescription()
    {
        return $this->_mymoduleHelper->metadescription();
    }

    public function pageheading()
    {
         return $this->_mymoduleHelper->pageheading();
    }

    public function pagedescription()
    {
         return $this->_mymoduleHelper->pagedescription();
    }
    public function pagelink()
    {
         return $this->_mymoduleHelper->pagelink();
    }
 ////////////////////////////////////////////////////////////////////////////////////////////////

    public function isPopupEnabled()
    {
         return $this->_mymoduleHelper->isPopupEnabled();
    }

    public function popupposition()
    {
         return $this->_mymoduleHelper->popupposition();
    }

    ////////////////////////////////////////////////////////////

    public function nametittle()
    {
         return $this->_mymoduleHelper->nametittle();
    }
    public function emailtittle()
    {
         return $this->_mymoduleHelper->emailtittle();
    }
    public function phonetittle()
    {
         return $this->_mymoduleHelper->phonetittle();
    }
    public function subjecttittle()
    {
         return $this->_mymoduleHelper->subjecttittle();
    }
    public function messagetittle()
    {
         return $this->_mymoduleHelper->messagetittle();
    }
    public function buttontext()
    {
         return $this->_mymoduleHelper->buttontext();
    }

    /////////////////////////////////////////////////////////////////////////////
    ////////////////////Store information /////////////////////////////////

    public function getStoreName()
    {
        return $this->_mymoduleHelper->getStoreName();
    }
    public function getstreet1()
    {
        return $this->_mymoduleHelper->getstreet1();
    }

    public function getstreet2()
    {
        return $this->_mymoduleHelper->getstreet2();
    }

    public function getcity()
    {
        return $this->_mymoduleHelper->getcity();
    }

    public function getzip()
    {
        return $this->_mymoduleHelper->getzip();
    }

    public function getregion()
    {
        return $this->_mymoduleHelper->getregion();
    }
    public function getcountry()
    {
        return $this->_mymoduleHelper->getcountry();
    }
    public function getphone()
    {
        return $this->_mymoduleHelper->getphone();
    }
    public function getstoreemail()
    {
        return $this->_mymoduleHelper->getstoreemail();
    }
}
