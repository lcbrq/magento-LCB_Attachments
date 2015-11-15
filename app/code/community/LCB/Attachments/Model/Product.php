<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Model_Product extends Mage_Core_Model_Abstract {

    protected function _construct()
    {
        $this->_init("lcb_attachments/product");
    }

    public function loadByAttachment($attachmentId)
    {
        return $this->getCollection()->addFieldToFilter('attachment_id', $attachmentId);
    }

}
