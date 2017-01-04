<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_CategoryController extends Mage_Core_Controller_Front_Action {

    public function viewAction() {
        $this->loadLayout();
        $category = Mage::getModel('lcb_attachments/category')->load($this->getRequest()->getParam('id'));
        $this->getLayout()->getBlock("head")->setTitle($category->getName());
        $this->renderLayout();
    }

}