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

class Wsu_Sitemaper_Block_Links extends Mage_Core_Block_Template
{
    const XML_PATH_ADD_LINKS = 'wsu_seo/sitemaper/add_links';
    const XML_PATH_SHOW_FOOTER_LINKS = 'wsu_seo/sitemaper/show_footer_links';

    protected $_links;

    protected function _prepareLayout()
    {
        $links = array();
        if (Mage::getStoreConfigFlag(self::XML_PATH_SHOW_FOOTER_LINKS)){
            $block = $this->getLayout()->getBlock('footer_links');
            if ($block){
                $footerLinks = $block->getLinks();
                if (count($footerLinks)){
                    foreach ($footerLinks as $link){
                        $links[] = $link;
                    }
                }
            }
        }

        $addLinks = array_filter(preg_split('/\r?\n/', Mage::getStoreConfig(self::XML_PATH_ADD_LINKS)));
        if (count($addLinks)){
            foreach ($addLinks as $link){
                $_link = explode(',', $link, 2);
                if (count($_link) == 2){
                    $links[] = new Varien_Object(array('label' => $_link[1], 'url' => Mage::getUrl((string) $_link[0])));
                }
            }
        }

        /* Leaved for compatibility with v1.0 */
        $xml = Mage::getStoreConfig(self::XML_PATH_ADD_LINKS);
        try {
            $xmlLinks = simplexml_load_string($xml);
        } catch (Exception $e){}
        if (!empty($xmlLinks) && count($xmlLinks)){
            foreach ($xmlLinks as $link){
                $links[] = new Varien_Object(array('label' => (string) $link->text, 'url' => Mage::getUrl((string) $link->href)));
            }
        }

        $this->setLinks($links);
        return $this;
    }

    public function getItemUrl($item)
    {
        return Mage::getUrl((string) $item->href);
    }

}
