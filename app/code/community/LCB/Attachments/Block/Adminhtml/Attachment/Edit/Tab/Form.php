<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Attachment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("attachments_form", array("legend" => Mage::helper("lcb_attachments")->__("Item information")));

        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('lcb_attachments')->__('Active'),
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toArray(),
            'name' => 'is_active'
        ));
        
        $fieldset->addField("title", "text", array(
            "label" => Mage::helper("lcb_attachments")->__("Title"),
            "name" => "title",
            'required' => true,
        ));

        $fieldset->addField("caption", "text", array(
            "label" => Mage::helper("lcb_attachments")->__("Caption"),
            "name" => "caption",
            'note' => "Optional",
            'required' => false,
        ));        

        $fieldset->addType('file', Mage::getConfig()->getBlockClassName('lcb_attachments/adminhtml_attachment_helper_file'));
        $fieldset->addField('file', 'file', array(
            'label' => Mage::helper('lcb_attachments')->__('File'),
            'name' => 'file',
            'note' => '(*.pdf, *.txt, *.jpg, *jpeg, *.png, *.gif, *.mp4, *.avi)',
        ));
        
        if (Mage::registry("attachment_data") && Mage::registry("attachment_data")->getFile() && !Mage::registry("attachment_data")->isImageable()) {
            $fieldset->addField('image', 'image', array(
                'label' => Mage::helper('lcb_attachments')->__('Preview image'),
                'name' => 'image',
                'note' => '(*.jpg, *.png, *.gif)',
            ));
        }

        $fieldset->addField('category', 'select', array(
            'label' => Mage::helper('lcb_attachments')->__('Category'),
            'values' => Mage::getModel('lcb_attachments/category')->getOptions(),
            'name' => 'category',
        ));

        if (Mage::helper('lcb_attachments')->isVisibilityGroupsEnabled()) {
            $fieldset->addField("visibility_groups", "multiselect", array(
                'label' => Mage::helper("lcb_attachments")->__("Visibility"),
                'name' => 'visibility_groups',
                'values' => Mage::getSingleton('lcb_attachments/system_config_groups')->toOptionArray(),
                'required' => true
            ));
        }

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('lcb_attachments')->__('Store View'),
                'title' => Mage::helper('lcb_attachments')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }

        if (Mage::getSingleton("adminhtml/session")->getAttachmentData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getAttachmentData());
            Mage::getSingleton("adminhtml/session")->setAttachmentData(null);
        } elseif (Mage::registry("attachment_data")) {
            $form->setValues(Mage::registry("attachment_data")->getData());
        }
        return parent::_prepareForm();
    }

}
