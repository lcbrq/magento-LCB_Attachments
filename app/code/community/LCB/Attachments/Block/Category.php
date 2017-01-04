<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Category extends Mage_Core_Block_Template {

    /**
     * Get current attachments category
     * 
     * @return LCB_Attachments_Model_Category
     */
    public function getCategory() {
        return Mage::getModel('lcb_attachments/category')->load($this->getRequest()->getParam('id'));
    }

}
