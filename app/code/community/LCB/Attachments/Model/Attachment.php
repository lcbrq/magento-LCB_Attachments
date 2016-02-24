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

    protected function _construct()
    {
        $mediaUrl = Mage::app()->getStore(Mage::app()->getStore())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $this->_urlPath = $mediaUrl . "attachments";
        $this->_absolutePath = Mage::getBaseDir('media') . DS . "attachments";
        $this->_enableImagick = Mage::getStoreConfig('attachments/general/imagick');
        $this->_defaultImage =  $mediaUrl . 'catalog' . DS . 'product' . DS . 'watermark' . DS . Mage::getStoreConfig('attachments/general/thumbnail');
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
     * @return string Image url
     */
    public function getImage()
    {

        $extension = pathinfo($this->getFile(), PATHINFO_EXTENSION);

        if ($this->_enableImagick && $this->getExtension() == "pdf" && class_exists('Imagick')) {

            $filename = substr($this->getFile(), 0, -strlen($extension)) . 'jpg';

            if (!file_exists($this->_absolutePath . DS . $filename)) {
                try {
                    $imagick = new Imagick($this->getPath() . '[0]');
                    $imagick->setImageFormat('jpeg');
                    $imagick->writeImage($this->_absolutePath . DS . $filename);
                    $imagick->clear();
                    $imagick->destroy();
                } catch (Exception $e) {
                    Mage::log($e->getMessage());
                }
            }

            return $this->_urlPath . DS . $filename;
        }

        if (in_array($extension, $this->_supportedImages)) {
            return $this->getUrl();
        }
        
        return $this->_defaultImage;
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
