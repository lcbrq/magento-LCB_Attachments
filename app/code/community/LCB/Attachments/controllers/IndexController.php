<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_IndexController extends Mage_Core_Controller_Front_Action {

    public function IndexAction()
    {

        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Downloads"));
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");

        if ($breadcrumbs) {

            $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link" => Mage::getBaseUrl()
            ));

            $breadcrumbs->addCrumb("downloads", array(
                "label" => $this->__("Downloads"),
                "title" => $this->__("Downloads")
            ));
        }

        $this->renderLayout();
    }

}
