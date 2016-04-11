<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Index extends Mage_Core_Block_Template {

    /**
     * Get media images from products
     * 
     * @return Varien_Data_Collection
     */
    public function getProductImages()
    {
        $collection = new Varien_Data_Collection();

        $categoryId = $this->getRequest()->getParam('category');
        $subcategoryId = $this->getRequest()->getParam('subcategory');
        $productId = $this->getRequest()->getParam('product');
        $photoIds = $this->getRequest()->getParam('photos');

        $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('small_image')
                ->addAttributeToSelect('thumbnail')
                ->addAttributeToSelect('image');
        $products->addAttributeToFilter('small_image', array('neq' => "no_selection"));
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);

        if (!empty($categoryId) && empty($subcategoryId)) {
            $category = Mage::getModel('catalog/category')->load($this->getRequest()->getParam('category'));
            $products->addCategoryFilter($category);
        }

        if (!empty($subcategoryId)) {
            $category = Mage::getModel('catalog/category')->load($this->getRequest()->getParam('subcategory'));
            $products->addCategoryFilter($category);
        }

        if (!empty($productId)) {
            $products->addAttributeToFilter('entity_id', array('in' => array($this->getRequest()->getParam('product'))));
        }

        if (empty($photoIds)) {
            $products->getSelect()->order('RAND()');
            $products->getSelect()->limit(16);
        }

        foreach ($products as $_product) {
            $product = Mage::getModel('catalog/product')->load($_product->getId());
            foreach ($product->getMediaGalleryImages() as $image) {

                if (!empty($photoIds)) {
                    if (!in_array($image->getId(), $photoIds)) {
                        continue;
                    }
                }

                if (!$image->getLabel()) {
                    $image->setLabel($product->getName());
                }

                $mime = pathinfo($image->getPath(), PATHINFO_EXTENSION);
                $image->setMime($mime);

                $filesize = filesize($image->getPath()) * .0009765625; // bytes to KB
                $image->setSize(round($filesize));

                $collection->addItem($image);
            }
        }

        return $collection;
    }

    /**
     * Get categories to populate first select
     * 
     * @return Mage_Catalog_Model_Resource_Category_Collection $categories
     */
    public function getCategories()
    {
        $categories = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('level', 2)
                ->addAttributeToFilter('is_active', 1);
        return $categories;
    }

    /**
     * Get subcategories to populate second select
     * 
     * @return mixed array|Mage_Catalog_Model_Resource_Category_Collection $categories
     */
    public function getSubcategories()
    {
        if (!$this->getRequest()->getParam('category')) {
            return array();
        }

        $categories = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('level', 3)
                ->addAttributeToFilter('parent_id', $this->getRequest()->getParam('category'))
                ->addAttributeToFilter('is_active', 1);
        return $categories;
    }

    /**
     * Get subcategories to populate second select
     * 
     * @return mixed array|Mage_Catalog_Model_Resource_Product_Collection $products
     */
    public function getProducts()
    {
        if (!$this->getRequest()->getParam('subcategory')) {
            return array();
        }

        $products = Mage::getModel('catalog/category')->load($this->getRequest()->getParam('subcategory'))
                ->getProductCollection()
                ->addAttributeToSelect('*');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->getSelect()->limit(240);
        return $products;
    }

    /**
     * Get attachments within specific category id
     * 
     * @param int $categoryId
     * @return LCB_Attachments_Model_Resource_Attachment_Collection  $attachments
     */
    public function getAttachments($categoryId, $store = false)
    {
        $attachments = Mage::getModel('lcb_attachments/category')->load($categoryId)->getAttachments();
        if ($store) {
            $attachments->addStoreFilter($store);
        }
        return $attachments;
    }

}
