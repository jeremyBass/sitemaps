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

class Wsu_SeoSuite_Model_Catalog_Product_Attribute_Backend_Urlkey extends Mage_Catalog_Model_Product_Attribute_Backend_Urlkey
{
    public function beforeSave($object)
    {
        $attributeName = $this->getAttribute()->getName();

        $urlKey = $object->getData($attributeName);
        if ($this->_product = Mage::registry('current_product')){
            if ($urlKey == '') {
                $this->_useDefault = (array) Mage::app()->getRequest()->getPost('use_default');

                if (in_array('url_key', $this->_useDefault)){
                    return $this;
                }

                if ($this->_product->getStore()->getId() > 0){
                    $this->_defaultProduct = Mage::getModel('catalog/product')->load($this->_product->getId());
                }

                $urlKeyTemplate = (string) Mage::getStoreConfig('wsu_seo/seosuite/product_url_key', $this->_product->getStore()->getId());
                $template = Mage::getModel('seosuite/catalog_product_template_url');
                $template->setTemplate($urlKeyTemplate)
                    ->setUseDefault($this->_useDefault)
                    ->setProduct($this->_product);

                $urlKey = $template->process();

                if ($urlKey == '') {
                    $urlKey = $object->getName();
                }
            }
        } else {
            return parent::beforeSave($object);
        }

        /*if ($this->_product->formatUrlKey($urlKey) != $this->_product->getUrlKey()){
            $urlRewrites = Mage::getModel('core/url_rewrite')->getCollection()->filterAllByProductId($this->_product->getId(), true)->load();
            foreach ($urlRewrites as $urlRewrite){
                $urlRewrite->setIsSystem(0)->setOptions('RP')->save();
            }
        }*/

        $object->setData($attributeName, $object->formatUrlKey($urlKey));

        return $this;
    }


}
