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
class Wsu_Sitemaper_Model_Catalog_Resource_Eav_Mysql4_Product_Attribute_Backend_Media extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Backend_Media
{
    public function loadGallery($product, $object) {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
        $result = $connection->fetchAll("
                    SELECT `main`.`value_id`, `main`.`value` AS `file`, `value`.`label`, `value`.`position`,
                        `value`.`disabled`, `default_value`.`label` AS `label_default`, `default_value`.`position` AS `position_default`,
                        `default_value`.`disabled` AS `disabled_default`
                    FROM `" . $tablePrefix . "catalog_product_entity_media_gallery` AS `main`
                    LEFT JOIN `". $this->getTable(self::GALLERY_VALUE_TABLE) . "` AS `value`
                        ON main.value_id=value.value_id
                        AND value.store_id=" . (int)$product->getStoreId() . "
                    LEFT JOIN `" . $this->getTable(self::GALLERY_VALUE_TABLE) . "` AS `default_value`
                        ON main.value_id=default_value.value_id AND default_value.store_id=0
                    WHERE (main.attribute_id = '" . $object->getAttribute()->getId() . "')
                        AND (main.entity_id = '" . $product->getId() . "')
                    ORDER BY IF(value.position IS NULL, default_value.position, value.position) ASC");

        $this->_removeDuplicates($result);
        return $result;
    }
}