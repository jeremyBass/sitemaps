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
 * @package    Wsu_Adminhtml
 * @copyright  Copyright (c) 2011 Wsu (http://www.wsu.com/)
 * @license    http://www.wsu.com/LICENSE-1.0.html
 */

/**
 * Wsu Adminhtml extension
 *
 * @category   Wsu
 * @package    Wsu_Adminhtml
 * @author     Wsu Dev Team <dev@wsu.com>
 */

class Wsu_Adminhtml_Model_Support extends Varien_Object
{
    const XML_PATH_SUPPORT_EMAIL = 'wsu/support/email';
    const XML_PATH_SUPPORT_NAME = 'wsu/support/name';
    const XML_PATH_SUPPORT_EMAIL_TEMPLATE = 'wsu/support/template';

    public function send()
    {
        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $errors = array();

        $this->_emailModel = Mage::getModel('core/email_template');
        $subject = $this->getSubject();
        $message = $this->getMessage();
        $reason  = $this->getReason();
        $version = Mage::getVersion();
        $url     = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $sender  = array(
            'name' => strip_tags($this->getName()),
            'email' => strip_tags($this->getEmail())
        );

        $this->_emailModel->setDesignConfig(array('area'=>'admin'))
            ->sendTransactional(
                Mage::getStoreConfig(self::XML_PATH_SUPPORT_EMAIL_TEMPLATE),
                $sender,
                base64_decode(Mage::getStoreConfig(self::XML_PATH_SUPPORT_EMAIL)),
                Mage::getStoreConfig(self::XML_PATH_SUPPORT_NAME),
                array(
                    'reason'        => $reason,
                    'subject'		=> $subject,
                    'message'		=> $message,
                    'version'       => $version,
                    'url'           => $url,
                )
        );
        $translate->setTranslateInline(true);

        return $this;
    }
}