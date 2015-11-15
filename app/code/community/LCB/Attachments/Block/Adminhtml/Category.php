<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct()
    {
        $this->_controller = "adminhtml_category";
        $this->_blockGroup = "lcb_attachments";
        $this->_headerText = Mage::helper("lcb_attachments")->__("Category Manager");
        $this->_addButtonLabel = Mage::helper("lcb_attachments")->__("Add New Item");
        parent::__construct();
    }

}
