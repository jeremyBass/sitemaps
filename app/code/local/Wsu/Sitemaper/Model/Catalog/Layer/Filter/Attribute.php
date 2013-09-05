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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@wsu.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.wsu.com/ for more information
 * or send an email to sales@wsu.com
 *
 * @category   Wsu
 * @package    Wsu_SeoSuite
 * @copyright  Copyright (c) 2010 Wsu (http://www.wsu.com/)
 * @license    http://www.wsu.com/LICENSE-1.0.html
 */

/**
 * SEO Suite extension
 *
 * @category   Wsu
 * @package    Wsu_SeoSuite
 * @author     Wsu Dev Team <dev@wsu.com>
 */

class Wsu_SeoSuite_Model_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{
    protected function _getOptionId($label)
    {
        if ($source = $this->getAttributeModel()->getSource()){
            return $source->getOptionId($label);
        }
        return false;
    }

    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $text = $request->getParam($this->_requestVar);
        if (is_array($text)) {
            return $this;
        }
        $filter = $this->_getOptionId($text);
        if ($filter && $text) {
            if (method_exists($this, '_getResource')){
                $this->_getResource()->applyFilterToCollection($this, $filter);
            } else {
                Mage::getSingleton('catalogindex/attribute')->applyFilterToCollection(
                    $this->getLayer()->getProductCollection(),
                    $this->getAttributeModel(),
                    $filter
                );
            }
            $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
            $this->_items = array();
        }
        return $this;
    }
}
