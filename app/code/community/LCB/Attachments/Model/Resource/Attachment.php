<?php

/**
 * Attachments
 *
 * @category   LCB
 * @package    LCB_Attachments
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Attachments_Model_Resource_Attachment extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct()
    {
        $this->_init("lcb_attachments/attachment", "attachment_id");
    }

    /**
     * Process block data before deleting
     *
     * @param Mage_Core_Model_Abstract $object
     * @return LCB_Attachments_Model_Resource_Attachment
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $condition = array(
            'attachment_id = ?' => (int) $object->getAttachmentId(),
        );
        $this->_getWriteAdapter()->delete($this->getTable('lcb_attachments/store'), $condition);
        return parent::_beforeDelete($object);
    }

    /**
     * Perform operations after object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return LCB_Attachments_Model_Resource_Attachment
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getAttachmentId());
        $newStores = (array) $object->getStores();
        $table = $this->getTable('lcb_attachments/store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'attachment_id = ?' => (int) $object->getAttachmentId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'attachment_id' => (int) $object->getAttachmentId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
    
    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return LCB_Attachments_Model_Resource_Attachment
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getAttachmentId()) {
            $stores = $this->lookupStoreIds($object->getAttachmentId());
            $object->setData('store_id', $stores);
            $object->setVisibilityGroups(explode(',', $object->getVisibilityGroups()));
        }

        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param LCB_Attachments_Model_Attachment $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $stores = array(
                (int) $object->getStoreId(),
                Mage_Core_Model_App::ADMIN_STORE_ID,
            );
            $select->join(
                            array('as' => $this->getTable('lcb_attachments/store')), $this->getMainTable() . '.attachment_id = as.attachment_id', array('store_id')
                    )->where('is_active = ?', 1)
                    ->where('as.store_id in (?) ', $stores)
                    ->order('store_id DESC')
                    ->limit(1);
        }
        return $select;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                ->from($this->getTable('lcb_attachments/store'), 'store_id')
                ->where('attachment_id = :attachment_id');
        $binds = array(
            ':attachment_id' => (int) $id
        );
        return $adapter->fetchCol($select, $binds);
    }

}
