<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Model_Attachment extends Mage_Core_Model_Abstract {

    protected function _construct()
    {
        $this->_init("lcb_attachments/attachment");
    }

    public function getUrl()
    {
        return Mage::app()->getStore(Mage::app()->getStore())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)  . "attachments" . DS . $this->getFile();
    }
  

}