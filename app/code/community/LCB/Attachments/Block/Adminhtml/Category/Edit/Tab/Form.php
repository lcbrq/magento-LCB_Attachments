<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("attachments_form", array("legend" => Mage::helper("lcb_attachments")->__("Item information")));


        $fieldset->addField("name", "text", array(
            "label" => Mage::helper("lcb_attachments")->__("Name"),
            "name" => "name",
        ));


        if (Mage::getSingleton("adminhtml/session")->getCategoryData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getCategoryData());
            Mage::getSingleton("adminhtml/session")->setCategoryData(null);
        } elseif (Mage::registry("category_data")) {
            $form->setValues(Mage::registry("category_data")->getData());
        }
        return parent::_prepareForm();
    }

}
