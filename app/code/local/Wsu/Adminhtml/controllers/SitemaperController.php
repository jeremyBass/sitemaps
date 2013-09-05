<?php
/**
 * Wsu
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Wsu EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.wsu.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.wsu.com/ for more information
 *
 * @category   Wsu
 * @package    Wsu_Sitemaper
 * @copyright  Copyright (c) 2012 Wsu (http://www.wsu.com/)
 * @license    http://www.wsu.com/LICENSE-1.0.html
 */

/**
 * Extended Sitemap extension
 *
 * @category   Wsu
 * @package    Wsu_Sitemaper
 * @author     Wsu Dev Team
 */
class Wsu_Adminhtml_SitemaperController extends  Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/sitemaper_generate')
            ->_addBreadcrumb(Mage::helper('catalog')->__('Catalog'), Mage::helper('catalog')->__('Catalog'))
            ->_addBreadcrumb(Mage::helper('sitemaper')->__('Google Sitemap (Extended)'), Mage::helper('sitemaper')->__('Google Sitemap (Extended)'))
        ;
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('wsu/sitemaper'))
            ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('sitemap_id');
        $model = Mage::getModel('sitemaper/sitemap');

        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('sitemaper')->__('This sitemap no longer exists'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $model->setData($data);
        }

        Mage::register('sitemap_sitemap', $model);

        $this->_initAction()
            ->_addBreadcrumb($id ? Mage::helper('sitemaper')->__('Edit Sitemap') : Mage::helper('sitemaper')->__('New Sitemap'), $id ? Mage::helper('sitemaper')->__('Edit Sitemap') : Mage::helper('sitemaper')->__('New Sitemap'))
            ->_addContent($this->getLayout()->createBlock('wsu/sitemaper_edit'))
            ->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('sitemaper/sitemap');

            if ($this->getRequest()->getParam('sitemap_id')) {
                $model ->load($this->getRequest()->getParam('sitemap_id'));

                if ($model->getSitemapFilename() && file_exists($model->getPreparedFilename())){
                    unlink($model->getPreparedFilename());
                }
            }


            $model->setData($data);
			
			

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('sitemaper')->__('Sitemap was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('sitemap_id' => $model->getId()));
                    return;
                }
                if ($this->getRequest()->getParam('generate')) {
                    $this->getRequest()->setParam('sitemap_id', $model->getId());
                    $this->_forward('generate');
                    return;
                }
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('sitemap_id' => $this->getRequest()->getParam('sitemap_id')));
                return;
            }
        }
        $this->_redirect('*/*/');

    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('sitemap_id')) {
            try {
                $model = Mage::getModel('sitemaper/sitemap');
                $model->setId($id);

                /* @var $sitemap Wsu_Sitemaper_Model_Sitemap */
                $model->load($id);
                if ($model->getSitemapFilename() && file_exists($model->getPreparedFilename())){
                    unlink($model->getPreparedFilename());
                }
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('sitemaper')->__('Sitemap was successfully deleted'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('sitemap_id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('sitemaper')->__('Unable to find a sitemap to delete'));
        $this->_redirect('*/*/');
    }

    public function generateAction() {
//        $sitemapId = intval($this->getRequest()->getParam('sitemap_id'));        
//        $sitemap = Mage::getModel('sitemaper/sitemap')->load($sitemapId);        
//        $sitemap->generateXml();
//        exit;
        $this->loadLayout();
        $this->renderLayout();
    }    
    
    public function runGenerateAction() {
        
        $sitemapId = intval($this->getRequest()->getParam('sitemap_id'));
        if (!$sitemapId) return false;
        
        $sitemap = Mage::getModel('sitemaper/sitemap')->load($sitemapId);        
        if (!$sitemap->getId()) return false;
                
        $action = $this->getRequest()->getParam('action', '');
        if (!$action) return false;
        
        
        $sitemap->_sitemapInc = intval($this->getRequest()->getParam('sitemap_inc', 1));
        $sitemap->_linkInc = intval($this->getRequest()->getParam('link_inc', 0));
        $sitemap->_currentInc = intval($this->getRequest()->getParam('current_inc', 0));
        
        
        $sitemap->generateXml($action);
        
        $result = array();
        // 'category', 'product', 'tag', 'cms', 'additional_links', 'sitemap_finish'
        switch ($action) {
            case 'category': 
                $result['text'] = $this->__('Generated categories (100%)...');
                $action = 'product'; 
                break;
            case 'product':                                                
                if ($sitemap->_currentInc>=$sitemap->_totalProducts) {                                                                
                    $result['text'] = $this->__('Generated products (100%)...');
                    $sitemap->_currentInc = 0;
                    $action = 'tag';
                } else {
                    $result['text'] = $this->__('Generating products, processed %2$s of %1$s products (%3$s%%)...', $sitemap->_totalProducts, $sitemap->_currentInc, round($sitemap->_currentInc*100/$sitemap->_totalProducts, 2));
                }                
                break;
            case 'tag': 
                $result['text'] = $this->__('Generated tags (100%)...');
                $action = 'cms'; 
                break;
            case 'cms': 
                $result['text'] = $this->__('Generated CMS pages (100%)...');
                $action = 'additional_links';
                break;
            case 'additional_links': 
                $result['text'] = $this->__('Generated additional links (100%)...');
                $action = 'sitemap_finish'; 
                break;            
            case 'sitemap_finish': 
                $result['text'] = $this->__('Generated sitemap index (100%)...');
                $result['stop'] = 1;
				$sitemapurl = Mage::app()->getHelper('sitemaper/data')->getSitemapUrl();
				
		        $fileName = preg_replace('/^\//', '',  $sitemap ->getSitemapPath() . $sitemap->getSitemapFilename());
        		$url = Mage::app()->getStore( $sitemap->getStoreId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . $fileName;
				
				$mess = Mage::helper('sitemaper')->__('<p>You can submit your Sitemap <a href="http://www.google.com/webmasters/tools/" target="_blank">Webmaster Tools</a> directly or<a target="_blank" href="http://www.google.com/webmasters/sitemaps/ping?sitemap=%s">Ping  Google</a>.</p>',$url);
				
                $this->_getSession()->addSuccess(Mage::helper('sitemaper')->__('Sitemap "%s" has been successfully generated %s', $url , $mess));
                $action = ''; 
                break;
        }
        
        $result['url'] = $this->getUrl('*/*/runGenerate/', array('sitemap_id'=>$sitemapId, 'action'=>$action, 'sitemap_inc'=>$sitemap->_sitemapInc, 'link_inc'=>$sitemap->_linkInc, 'current_inc'=>$sitemap->_currentInc));
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    
    

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/sitemaper');
    }

}