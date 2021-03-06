<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Returns path of the related products template file if attachment is found
     *
     * @return string
     */
    public function getProductTab()
    {
        $product = Mage::registry('current_product');
        if ($product && $product->getId()) {
            $data = Mage::getModel('lcb_attachments/product')->load($product->getId(), 'product_id');
            if ($data->getId()) {
                return 'lcb/attachments/catalog/product/tab.phtml';
            }
        }

        return false;
    }
    
    /**
     * Get all attachments categories
     * 
     * @return LCB_Attachments_Model_Resource_Category_Collection
     */
    public function getCategories()
    {
        return Mage::getModel('lcb_attachments/category')->getCollection();
    }
    
    /**
     * Check if visibility groups are enabled
     * 
     * @return boolean
     */
    public function isVisibilityGroupsEnabled()
    {
        return Mage::getStoreConfig('attachments/groups/enable');
    }

}
