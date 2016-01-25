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

    /**
     * Get attachment url
     * 
     * @return string
     */
    public function getUrl()
    {
        return Mage::app()->getStore(Mage::app()->getStore())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "attachments" . DS . $this->getFile();
    }
    
    /**
     * Get attachment absolute url
     * 
     * @return string
     */
    public function getPath()
    {
        return Mage::getBaseDir('media') . DS . "attachments" . DS . $this->getFile();
    }

    /**
     * Get attachment size
     * 
     * @return float file size in kb
     */
    public function getSize()
    {
        return filesize($this->getPath()) * .0009765625;
    }

    /**
     * Get attachment extension
     * 
     * @return string file extension
     */
    public function getExtension()
    {
        return pathinfo($this->getUrl(), PATHINFO_EXTENSION);
    }

}
