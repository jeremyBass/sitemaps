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
 * @package    Wsu_SeoSuite
 * @copyright  Copyright (c) 2012 Wsu (http://www.wsu.com/)
 * @license    http://www.wsu.com/LICENSE-1.0.html
 */

/**
 * SEO Suite extension
 *
 * @category   Wsu
 * @package    Wsu_SeoSuite
 * @author     Wsu Dev Team
 */
class Wsu_SeoSuite_Model_Mysql4_Core_Url_Rewrite_Collection extends Mage_Core_Model_Mysql4_Url_Rewrite_Collection {

    protected function _initSelect() {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()), array('*', new Zend_Db_Expr('LENGTH(request_path)')));
        return $this;
    }

    public function sortByLength($spec = 'DESC') {
        $this->getSelect()->order(new Zend_Db_Expr('LENGTH(request_path) ' . $spec));
        return $this;
    }

    public function filterAllByProductId($productId, $useCategories = false) {
        if ($productId != null) {
            if ($useCategories==1) {
                // longest
                $this->getSelect()->where('product_id = ? AND category_id is not null AND is_system = 1', $productId, Zend_Db::INT_TYPE);
                $this->sortByLength('DESC');
            } else if ($useCategories == 2) {
                // shortest
                $this->getSelect()->where('product_id = ? AND category_id is null AND is_system = 1', $productId, Zend_Db::INT_TYPE);
                $this->sortByLength('ASC');
            } else {
                // root or other
                $this->getSelect()->where('product_id = ? AND is_system = 1', $productId, Zend_Db::INT_TYPE);
            }
        }

        return $this;
    }

    public function groupByUrl() {
        $this->getSelect()->group('request_path');
        return $this;
    }
    
    public function filterByIdPath($idPath) {
        $this->getSelect()
                ->where('id_path = ?', $idPath);
        return $this;
    }

}