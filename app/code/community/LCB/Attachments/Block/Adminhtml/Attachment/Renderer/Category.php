<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Attachment_Renderer_Category extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $id = $row->getData($this->getColumn()->getIndex());
        $category = Mage::getModel('lcb_attachments/category')->load($id);
        return $category->getName();
    }

}
