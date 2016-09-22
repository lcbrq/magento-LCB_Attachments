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

    /**
     * Download attachments zip
     * 
     * @return void
     */
    public function GetAction()
    {

        $_isValidFormKey = $this->_validateFormKey();
        if (!$_isValidFormKey && Mage::getStoreConfig('attachments/general/CSRF')) {
            return Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl());
        }

        $productId = $this->getRequest()->getParam('product');
        $ids = $this->getRequest()->getParam('attachments');
        $categoryId = $this->getRequest()->getParam('category');

        $attachments = Mage::getModel('lcb_attachments/attachment')->getCollection();

        if ($ids) {
            $attachments->addFieldToFilter('attachment_id', array('in' => $ids));
        }

        if ($categoryId) {
            $attachments->addFieldToFilter('category', $categoryId);
        }

        $file = tempnam("tmp", "zip");
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::OVERWRITE);

        if ($productId && !$ids) {
            $attachments = array(); // reset for single
        }

        foreach ($attachments as $attachment) {
            $zip->addFile($attachment->getPath(), $attachment->getFile());
        }

        $images = array();
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
                    if (!$image->getLabel()) {
                        $image->setLabel($product->getName() . $image->getId());
                    }
                    $image->setMime($mime);
                    $image->setFilename($image->getLabel() . '.' . $image->getMime());
                    Mage::dispatchEvent('attachments_image_zip_before', array(
                        'image' => $image,
                        'product' => $product
                    ));
                    $images[] = $image;
                }
            }
        }
        
        foreach ($images as $image) {
            $zip->addFile($image->getPath(), $image->getFilename());
        }

        $ytmovies = $this->getRequest()->getParam('ytmovies');
        if ($ytmovies) {
            $ytDownloader = Mage::getBaseDir('lib') . DS . 'Youtube' . DS . 'youtube-dl.class.php';
            require($ytDownloader);
            foreach ($ytmovies as $movie => $id) {
                try {
                    $mytube = new yt_downloader();
                    $mytube->set_youtube($movie);
                    $mytube->set_video_quality(1);
                    $mytube->set_thumb_size('s');
                    $download = $mytube->download_video();
                    $video = $mytube->get_video();
                    $thumb = $mytube->get_thumb();
                    $path = Mage::getBaseDir('media') . DS . "attachments" . DS . 'youtube' . DS . $video;
                    $zip->addFile($path, $video);
                } catch (Exception $e) {
                    
                }
            }
        }

        $movies = $this->getRequest()->getParam('movies');
        if ($movies) {
            foreach ($movies as $id => $movie) {
                try {
                    $fileName = basename($movie);
                    $downloadDir = Mage::getBaseDir('media') . '/attachments/movies/';
                    $filePath = $downloadDir . $fileName;
                    if (!file_exists($filePath)) {
                        $video = file_get_contents($movie);
                        file_put_contents($filePath, $video);
                    }
                    $zip->addFile($filePath, $fileName);
                } catch (Exception $e) {
                    
                }
            }
        }
       
        $zip->close();
        
        $archive = new Varien_Object();
        $archive->setProducts($products);
        $archive->setImages($images);
        $archive->setMovies($movies);
        $archive->setFilename('download.zip');
        
        Mage::dispatchEvent('attachments_image_zip_after', array(
            'archive' => $archive
        ));
        
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename="'.$archive->getFilename().'"');
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', filesize($file));
        $response->setHeader('Content-type', 'application/zip');
        $this->getResponse()->sendHeaders();
        $response->setBody(file_get_contents($file));
        unlink($file);
    }

    /**
     * Download single file with form key protection
     * 
     * @return void
     */
    public function DownloadAction()
    {
        $fileUrl = $this->getRequest()->getParam('url');
        $formKey = $this->getRequest()->getParam('form_key');
        if (!empty($formKey) && $formKey == Mage::getSingleton('core/session')->getFormKey()) {
            $fileUrl = base64_decode($fileUrl);
            $fileUrl = str_replace(" ", "%20", $fileUrl);
            $fileName = basename($fileUrl);
            header("Content-disposition: attachment; filename=$fileName");
            header("Content-type: application/octet-stream");
            readfile($fileUrl);
            exit();
        }
    }

}
