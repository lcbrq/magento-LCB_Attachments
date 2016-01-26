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

    public function GetAction()
    {
        $ids = $this->getRequest()->getParam('attachments');
        $categoryId = $this->getRequest()->getParam('category');

        $attachments = Mage::getModel('lcb_attachments/attachment')->getCollection();
        
        if($ids){
        $attachments->addFieldToFilter('attachment_id', array('in' => $ids));
        }
        
        if($categoryId){
            $attachments->addFieldToFilter('category', $categoryId);
        }

        $file = tempnam("tmp", "zip");
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::OVERWRITE);

        foreach ($attachments as $attachment) {
            $zip->addFile($attachment->getPath(), $attachment->getFile());
        }

        $zip->close();
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename="download.zip"');
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', filesize($file));
        $response->setHeader('Content-type', 'application/zip');
        $this->getResponse()->sendHeaders();
        $response->setBody(readfile($file));
        $response->sendResponse();
        unlink($file);
    }

}
