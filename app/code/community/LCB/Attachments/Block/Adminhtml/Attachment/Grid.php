<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Block_Adminhtml_Attachment_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setId("attachmentGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("lcb_attachments/attachment")->getCollection();
        $collection->setFirstStoreFlag(true);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn("attachment_id", array(
            "header" => Mage::helper("lcb_attachments")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "attachment_id",
        ));

        $this->addColumn("title", array(
            "header" => Mage::helper("lcb_attachments")->__("Title"),
            "index" => "title",
        ));

        $this->addColumn("file", array(
            "header" => Mage::helper("lcb_attachments")->__("File"),
            "index" => "file",
        ));

        $this->addColumn("category", array(
            "header" => Mage::helper("lcb_attachments")->__("Category"),
            "index" => "category",
            'renderer' => 'LCB_Attachments_Block_Adminhtml_Attachment_Renderer_Category',
        ));
        
        $this->addColumn("is_active", array(
            "header" => Mage::helper("lcb_attachments")->__("Active"),
            "index" => "is_active",
            "type" => "options",
            "options" => Mage::getSingleton('adminhtml/system_config_source_yesno')->toArray()
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('lcb_attachments')->__('Store View'),
                'index' => 'store_id',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => false,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition'),
            ));
        }


        $this->addExportType('*/*/exportCsv', Mage::helper('lcb_attachments')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('lcb_attachments')->__('Excel'));

        return parent::_prepareColumns();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/edit", array("attachment_id" => $row->getAttachmentId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('attachment_id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_attachment', array(
            'label' => Mage::helper('lcb_attachments')->__('Remove Attachments'),
            'url' => $this->getUrl('*/adminhtml_attachment/massRemove'),
            'confirm' => Mage::helper('lcb_attachments')->__('Are you sure?')
        ));
        return $this;
    }

}
