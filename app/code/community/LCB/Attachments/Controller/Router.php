<?php

class LCB_Attachments_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{

    /**
     * Match the request
     *
     * @param Zend_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request)
    {

        if (!$this->_beforeModuleMatch()) {
            return false;
        }

        $route = $request->getRouteName();

        if ($route === 'lcb_attachments') {

            $pathInfo = explode('/', $request->getPathInfo());

            $action = new Varien_Object;
            $action->setActionName(end($pathInfo));

            Mage::dispatchEvent('attachments_download_action', array(
                'action' => $action
            ));

            if ($action->getFileId()) {
                $request->setControllerName('index');
                $request->setActionName('index');
                $request->setParam('id', $action->getFileId());
                $request->setParam('type', $action->getType());
                return true;
            }
        }

        return false;
    }

}
