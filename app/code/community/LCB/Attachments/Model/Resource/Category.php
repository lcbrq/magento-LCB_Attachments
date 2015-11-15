<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Model_Resource_Category extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct()
    {
        $this->_init("lcb_attachments/category", "id");
    }

}
