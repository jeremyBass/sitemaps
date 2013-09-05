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

class Wsu_Adminhtml_Block_Sitemaper_Cms_Page_Edit_Tab_Meta extends Wsu_Adminhtml_Block_Sitemaper_Cms_Page_Edit_Tab_Meta_Abstract
{
    protected function _prepareForm() {
        parent::_prepareForm();        
        $form = $this->getForm();
        $fieldset = $form->getElements()->searchById('meta_fieldset');
        
        $values = Mage::getModel('eav/entity_attribute_source_boolean')->getAllOptions();
        $fieldset->addField('exclude_from_sitemap', 'select', array(
            'name'      => 'exclude_from_sitemap',
            'label'     => Mage::helper('sitemaper')->__('Exclude from XML Sitemap'),
            'values'    => $values,
        ));                
        
        $model = Mage::registry('cms_page');
        $form->setValues($model->getData());
        $this->setForm($form);

        return $this;
    }
}
