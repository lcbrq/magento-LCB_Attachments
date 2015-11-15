<?php

class LCB_Attachments_Block_Catalog_Product_View_Tab extends Mage_Adminhtml_Block_Widget implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    public function canShowTab()
    {
        return true;
    }

    public function getTabLabel()
    {
        return $this->__('Custom Tab');
    }

    public function getTabTitle()
    {
        return $this->__('Custom Tab');
    }

    public function isHidden()
    {
        return false;
    }

}
