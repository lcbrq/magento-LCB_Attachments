<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Category_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct()
    {
        parent::__construct();
        $this->setId("category_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("lcb_attachments")->__("Item Information"));
    }

    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("lcb_attachments")->__("Item Information"),
            "title" => Mage::helper("lcb_attachments")->__("Item Information"),
            "content" => $this->getLayout()->createBlock("lcb_attachments/adminhtml_category_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
