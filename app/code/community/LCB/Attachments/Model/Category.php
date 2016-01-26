<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Model_Category extends Mage_Core_Model_Abstract {

    protected function _construct()
    {
        $this->_init("lcb_attachments/category");
    }

    /**
     * Get options for attachment form
     * 
     * @return array
     */
    public function getOptions()
    {
        $options = array();
        foreach ($this->getCollection() as $category) {
            $options[] = array('value' => $category->getId(), 'label' => $category->getName());
        }

        return $options;
    }

    /**
     * Get category attachments
     * 
     * @return LCB_Attachments_Model_Resource_Attachment_Collection
     */
    public function getAttachments()
    {
        return Mage::getModel('lcb_attachments/attachment')->getCollection()->addFieldToFilter('category', $this->getId());
    }

    /**
     * Get display options for select
     * 
     * @return array
     */
    public function getDisplayOptionArray()
    {
        return array(
            array(
                'value' => 'small',
                'label' => Mage::helper("lcb_attachments")->__("Small tiles"),
            ),
            array(
                'value' => 'big',
                'label' => Mage::helper("lcb_attachments")->__("Big tiles"),
            )
        );
    }

}
