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

class Wsu_Sitemaper_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getSitemapUrl() {
        return Mage::getUrl('sitemap');
    }
}