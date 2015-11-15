<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Attachment extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct()
    {
        $this->_controller = "adminhtml_attachment";
        $this->_blockGroup = "lcb_attachments";
        $this->_headerText = Mage::helper("lcb_attachments")->__("Attachment Manager");
        $this->_addButtonLabel = Mage::helper("lcb_attachments")->__("Add New Item");
        parent::__construct();
    }

}
