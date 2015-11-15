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
            $attachment = Mage::getModel('lcb_attachments/product')->load($product->getId(), 'product_id');
            if ($attachment->getId()) {
                return 'lcb/attachments/catalog/product/tab.phtml';
            }
        }

        return false;
    }

}
