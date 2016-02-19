<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_ImagesController extends Mage_Core_Controller_Front_Action {

    public function GetAction()
    {

        $imageData = $this->getRequest()->getParam('photos');

        if ($imageData) {
            foreach ($imageData as $productId => $imagesArray) {
                $productIds[] = $productId;
                foreach ($imagesArray as $imageId) {
                    $photoIds[] = $imageId;
                }
            }
            $products = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToFilter('entity_id', array('in' => $productIds));

            foreach ($products as $_product) {
                $product = Mage::getModel('catalog/product')->load($_product->getId());
                foreach ($product->getMediaGalleryImages() as $image) {
                    if (!in_array($image->getId(), $photoIds)) {
                        continue;
                    }

                    $mime = pathinfo($image->getPath(), PATHINFO_EXTENSION);
                    if (empty($image->getLabel())) {
                        $image->setLabel($product->getName() . $image->getId());
                    }
                    $image->setMime($mime);
                    $images[] = $image;
                }
            }
        } else {
            $images = $this->getLayout()->getBlockSingleton('lcb_attachments/index')->getProductImages();
        }

        $file = tempnam("tmp", "zip");
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::OVERWRITE);

        foreach ($images as $image) {
            $zip->addFile($image->getPath(), $image->getLabel() . '.' . $image->getMime());
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
