<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Model_Attachment extends Mage_Core_Model_Abstract {

    protected $_absolutePath;
    protected $_urlPath;
    protected $_supportedImages = array(
        'gif',
        'jpg',
        'jpeg',
        'png'
    );
    protected $_enableImagick;
    protected $_defaultImage;
    protected $_defaultImageAbsolute;

    protected function _construct()
    {
        $mediaUrl = Mage::app()->getStore(Mage::app()->getStore())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $this->_urlPath = $mediaUrl . "attachments";
        $this->_absolutePath = Mage::getBaseDir('media') . DS . "attachments";
        $this->_enableImagick = Mage::getStoreConfig('attachments/general/imagick');
        $this->_defaultImage = $mediaUrl . 'catalog' . DS . 'product' . DS . 'watermark' . DS . Mage::getStoreConfig('attachments/general/thumbnail');
        $this->_defaultImageAbsolute = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product' . DS . 'watermark' . DS . Mage::getStoreConfig('attachments/general/thumbnail');
        $this->_init("lcb_attachments/attachment");
    }

    /**
     * Get attachment url
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->_urlPath . DS . $this->getFile();
    }

    /**
     * Get attachment image
     * 
     * @return string $imageUrl
     */
    public function getImage($resize = false)
    {

        $imageUrl = null;
        $imagePath = null;
        $fileName = $this->getFile();

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($this->_enableImagick && $this->getExtension() == "pdf" && class_exists('Imagick')) {

            $fileName = substr($fileName, 0, -strlen($extension)) . 'jpg';
            $imagePath = $this->_absolutePath . DS . $fileName;

            if (!file_exists($imagePath)) {
                try {
                    $imagick = new Imagick($this->getPath() . '[0]');
                    $imagick->setImageFormat('jpeg');
                    $imagick->writeImage($imagePath);
                    $imagick->clear();
                    $imagick->destroy();
                } catch (Exception $e) {
                    Mage::log($e->getMessage());
                }
            }

            $imageUrl = $this->_urlPath . DS . $fileName;
        }

        if (!$imageUrl && in_array($extension, $this->_supportedImages)) {
            $imageUrl = $this->getUrl();
            $imagePath = $this->getPath();
        }

        if (!$imageUrl) {
            $fileName = basename($this->_defaultImageAbsolute);
            $imageUrl = $this->_defaultImage;
            $imagePath = $this->_defaultImageAbsolute;
        }

        if ($resize) {
            $width = $resize[0];
            $height = $resize[1];
            $resized = $this->_absolutePath . "/resized/$width/$height" . DS . $fileName;
            if (!file_exists($resized) && file_exists($imagePath)) {
                $image = new Varien_Image($imagePath);
                $image->resize($width, $height);
                $image->save($resized);
            }
            $imageUrl = $this->_urlPath . "/resized/$width/$height" . DS . $fileName;
        }

        return $imageUrl;
    }

    /**
     * Alias for getImage()
     *
     * @return string Image url
     */
    public function getThumbnail()
    {
        return $this->getImage();
    }

    /**
     * Get attachment absolute url
     * 
     * @return string
     */
    public function getPath()
    {
        return Mage::getBaseDir('media') . DS . "attachments" . DS . $this->getFile();
    }

    /**
     * Get attachment size
     * 
     * @return float file size in kb
     */
    public function getSize()
    {
        return filesize($this->getPath()) * .0009765625;
    }

    /**
     * Get attachment extension
     * 
     * @todo database replacement for performance
     * @return string file extension
     */
    public function getExtension()
    {
        return pathinfo($this->getUrl(), PATHINFO_EXTENSION);
    }

}
