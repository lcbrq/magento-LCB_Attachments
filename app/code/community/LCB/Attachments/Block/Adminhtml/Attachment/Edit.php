<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Attachment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct()
    {

        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = "lcb_attachments";
        $this->_controller = "adminhtml_attachment";
        $this->_updateButton("save", "label", Mage::helper("lcb_attachments")->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper("lcb_attachments")->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("lcb_attachments")->__("Save And Continue Edit"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);

        $this->_formScripts[] = "function saveAndContinueEdit(){ editForm.submit($('edit_form').action+'back/edit/'); }";
    }

    public function getHeaderText()
    {
        if (Mage::registry("attachment_data") && Mage::registry("attachment_data")->getAttachmentId()) {
            return Mage::helper("lcb_attachments")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("attachment_data")->getTitle()));
        } else {

            return Mage::helper("lcb_attachments")->__("Add Item");
        }
    }

}
