<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Attachment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct()
    {
        parent::__construct();
        $this->setId("attachment_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("lcb_attachments")->__("Item Information"));
    }

    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("lcb_attachments")->__("Item Information"),
            "title" => Mage::helper("lcb_attachments")->__("Item Information"),
            "content" => $this->getLayout()->createBlock("lcb_attachments/adminhtml_attachment_edit_tab_form")->toHtml(),
        ));

        $this->addTab("products_section", array(
            "label" => Mage::helper("lcb_attachments")->__("Attachment Products"),
            "title" => Mage::helper("lcb_attachments")->__("Attachment Products"),
            'class' => 'ajax',
            'url' => $this->getUrl('attachments/adminhtml_attachment/productstab', array('_current' => true)),
        ));

        return parent::_beforeToHtml();
    }

}
