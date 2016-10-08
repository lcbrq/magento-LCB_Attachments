<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Adminhtml_AttachmentController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("lcb_attachments/attachment")->_addBreadcrumb(Mage::helper("adminhtml")->__("Attachment  Manager"), Mage::helper("adminhtml")->__("Attachment Manager"));
        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/attachments');
    }

    public function indexAction()
    {
        $this->_title($this->__("Attachments"));
        $this->_title($this->__("Manager Attachment"));

        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__("Attachments"));
        $this->_title($this->__("Attachment"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("attachment_id");
        $model = Mage::getModel("lcb_attachments/attachment")->load($id);
        if ($model->getId()) {
            Mage::register("attachment_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("lcb_attachments/attachment");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Attachment Manager"), Mage::helper("adminhtml")->__("Attachment Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Attachment Description"), Mage::helper("adminhtml")->__("Attachment Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("lcb_attachments/adminhtml_attachment_edit"))->_addLeft($this->getLayout()->createBlock("lcb_attachments/adminhtml_attachment_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("lcb_attachments")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction()
    {

        $this->_title($this->__("Attachments"));
        $this->_title($this->__("Attachment"));
        $this->_title($this->__("New Item"));

        $id = $this->getRequest()->getParam("attachment_id");
        $model = Mage::getModel("lcb_attachments/attachment")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("attachment_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("lcb_attachments/attachment");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Attachment Manager"), Mage::helper("adminhtml")->__("Attachment Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Attachment Description"), Mage::helper("adminhtml")->__("Attachment Description"));


        $this->_addContent($this->getLayout()->createBlock("lcb_attachments/adminhtml_attachment_edit"))->_addLeft($this->getLayout()->createBlock("lcb_attachments/adminhtml_attachment_edit_tabs"));

        $this->renderLayout();
    }

    public function saveAction()
    {

        $post_data = $this->getRequest()->getPost();


        if ($post_data) {

            try {
               
                /**
                 * Save attachment
                 */
                try {

                    if (isset($post_data['file']['delete']) && (bool) $post_data['file']['delete'] == 1) {

                        $post_data['file'] = '';
                        
                    } else {

                        unset($post_data['file']);

                        if (isset($_FILES)) {

                            if ($_FILES['file']['name']) {

                                if ($this->getRequest()->getParam("attachment_id")) {
                                    $model = Mage::getModel("lcb_attachments/attachment")->load($this->getRequest()->getParam("attachment_id"));
                                    if ($model->getData('file')) {
                                        $io = new Varien_Io_File();
                                        $io->rm(Mage::getBaseDir('media') . DS . implode(DS, explode('/', $model->getData('file'))));
                                    }
                                }
                                $path = Mage::getBaseDir('media') . DS . 'attachments' . DS;
                                $uploader = new Varien_File_Uploader('file');
                                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif', 'pdf', 'eps', 'txt', 'mp4', 'avi'));
                                $uploader->setAllowRenameFiles(false);
                                $uploader->setFilesDispersion(false);
                                $destFile = $path . $_FILES['file']['name'];
                                $filename = $uploader->getNewFileName($destFile);
                                $result = $uploader->save($path, $filename);
                                $post_data['file'] = $result['file'];
                            }
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('attachment_id')));
                    return;
                }

                /**
                 * Save image for preview
                 */
                try {

                    if (isset($post_data['image']['delete']) && (bool) $post_data['image']['delete'] == 1) {

                        $post_data['image'] = '';
                        
                    } else {

                        unset($post_data['image']);

                        if (isset($_FILES)) {

                            if ($_FILES['image']['name']) {

                                if ($this->getRequest()->getParam("attachment_id")) {
                                    $model = Mage::getModel("lcb_attachments/attachment")->load($this->getRequest()->getParam("attachment_id"));
                                    if ($model->getData('image')) {
                                        $io = new Varien_Io_File();
                                        $io->rm(Mage::getBaseDir('media') . DS . implode(DS, explode('/', $model->getData('image'))));
                                    }
                                }
                                $path = Mage::getBaseDir('media') . DS . 'attachments' . DS . 'images'. DS;
                                $uploader = new Varien_File_Uploader('image');
                                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                                $uploader->setAllowRenameFiles(false);
                                $uploader->setFilesDispersion(false);
                                $destFile = $path . $_FILES['image']['name'];
                                $filename = $uploader->getNewFileName($destFile);
                                $result = $uploader->save($path, $filename);
                                $post_data['image'] = 'attachments' . DS . 'images' . DS . $result['file'];
                            }
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('attachment_id')));
                    return;
                }
                
                $model = Mage::getModel("lcb_attachments/attachment")
                        ->addData($post_data)
                        ->setAttachmentId($this->getRequest()->getParam("attachment_id"))
                        ->save();

                if (isset($post_data['product_ids'])) {
                    $products = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post_data['product_ids']);
                    $attachmentId = $model->getAttachmentId();
                    $collection = Mage::getModel('lcb_attachments/product')->loadByAttachment($attachmentId);
                    foreach ($collection as $data) {
                        $data->delete();
                    }
                    foreach ($products as $product) {
                        $productModel = Mage::getModel('lcb_attachments/product');
                        $productModel->setProductId($product);
                        $productModel->setAttachmentId($attachmentId);
                        $productModel->save();
                    }
                }

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Attachment was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setAttachmentData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("attachment_id" => $model->getAttachmentId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setAttachmentData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("attachment_id" => $this->getRequest()->getParam("attachment_id")));
                return;
            }
        }
        $this->_redirect("*/*/");
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam("attachment_id") > 0) {
            try {
                $model = Mage::getModel("lcb_attachments/attachment");
                $model->setAttachmentId($this->getRequest()->getParam("attachment_id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("attachment_id" => $this->getRequest()->getParam("attachment_id")));
            }
        }
        $this->_redirect("*/*/");
    }

    public function massRemoveAction()
    {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("lcb_attachments/attachment");
                $model->setAttachmentId($id)->delete();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'attachment.csv';
        $grid = $this->getLayout()->createBlock('lcb_attachments/adminhtml_attachment_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'attachment.xml';
        $grid = $this->getLayout()->createBlock('lcb_attachments/adminhtml_attachment_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    public function productsTabAction()
    {
        $this->loadLayout();
        $products = array();
        if ($id = $this->getRequest()->getParam('attachment_id')) {
            $products = Mage::getModel('lcb_attachments/product')->loadByAttachment($id);
        }
        $this->getLayout()->getBlock('attachments.attachment.edit.tab.products')->setProducts($products);
        $this->renderLayout();
    }

    /*
     * http://magebase.com/magento-tutorials/understanding-the-grid-serializer-block/
     * http://inchoo.net/magento/magento-grid-serializer-ajax-grids/
     */

    public function productsGridAction()
    {
        $this->loadLayout();
        $products = $this->getRequest()->getPost('attachment_products', null);
        $this->getLayout()
                ->getBlock('attachments.attachment.edit.tab.products')
                ->setAttachmentProducts($products);

        $this->renderLayout();
    }

}
