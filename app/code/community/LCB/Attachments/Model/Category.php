<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Model_Category extends Mage_Core_Model_Abstract {

    protected function _construct()
    {
        $this->_init("lcb_attachments/category");
    }

    public function getOptions()
    {
        $option = array();
        foreach ($this->getCollection() as $category) {
            $options[] = array('value' => $category->getId(), 'label' => $category->getName());
        }

        return $options;
    }
    
   
    public function getAttachments(){
        return Mage::getModel('lcb_attachments/attachment')->getCollection()->addFieldToFilter('category', $this->getId());
    }

}
