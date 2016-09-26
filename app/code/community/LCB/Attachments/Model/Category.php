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
    public function getAttachments($store = false)
    {
        $attachments = Mage::getModel('lcb_attachments/attachment')->getCollection()->addFieldToFilter('category', $this->getId());
        if ($store) {
            $attachments->addStoreFilter($store);
        }
        return $attachments;
    }

   /**
     * Get display options for select
     * 
     * @return array
     */
    public function getDisplayOptionArray()
    {

        $displayOptions = array(
            array(
                'value' => 'small',
                'label' => Mage::helper("lcb_attachments")->__("Small tiles"),
            ),
            array(
                'value' => 'big',
                'label' => Mage::helper("lcb_attachments")->__("Big tiles"),
            )
        );

        $this->setDisplayOptions($displayOptions);
        Mage::dispatchEvent('attachments_get_category_display_options', array('category' => $this));
        return $this->getDisplayOptions();
    }

    /**
     * Get category url
     * 
     * @return string
     */
   public function getUrl() {
        if (parent::getUrl()) {
            return parent::getUrl();
        }
        return Mage::getUrl('downloads/category/view', array('id' => $this->getId()));
    }

}
