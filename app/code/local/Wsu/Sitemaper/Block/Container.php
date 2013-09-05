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
 * @package    Wsu_Sitemaper
 * @copyright  Copyright (c) 2009 Wsu (http://www.wsu.com/)
 * @license    http://www.wsu.com/LICENSE-1.0.html
 */

/**
 * Extended Sitemap extension
 *
 * @category   Wsu
 * @package    Wsu_Sitemaper
 * @author     Wsu Dev Team <dev@wsu.com>
 */

class Wsu_Sitemaper_Block_Container extends Mage_Core_Block_Template
{
    const XML_PATH_SHOW_STORES = 'wsu_seo/sitemaper/show_stores';
    const XML_PATH_SHOW_CATEGORIES = 'wsu_seo/sitemaper/show_categories';
    const XML_PATH_SHOW_PAGES = 'wsu_seo/sitemaper/show_pages';
    const XML_PATH_SHOW_LINKS = 'wsu_seo/sitemaper/show_links';

    protected function _construct()
    {
        $this->setTitle($this->__('Site Map'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->getTitle());
    }

    public function showStores()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_SHOW_STORES);
    }

    public function showCategories()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_SHOW_CATEGORIES);
    }

    public function showPages()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_SHOW_PAGES);
    }

    public function showLinks()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_SHOW_LINKS);
    }
}
