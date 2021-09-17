<?php

/**
 * Class Hevelop_ReCaptcha_Helper_Data
 */
class Hevelop_ReCaptcha_Helper_Data extends Mage_Core_Helper_Abstract
{
    const HEVELOP_RECAPTCHA_ENABLE_XML_PATH = 'hevelop_recaptcha/general/enable';
    const HEVELOP_RECAPTCHA_API_KEY_XML_PATH = 'hevelop_recaptcha/general/apy_key';
    const HEVELOP_RECAPTCHA_API_SECRET_XML_PATH = 'hevelop_recaptcha/general/apy_secret';
    const HEVELOP_RECAPTCHA_FORMS_TO_VALIDATE_XML_PATH = 'hevelop_recaptcha/general/forms_to_validate';
    const HEVELOP_RECAPTCHA_SITE_VERIFY_ENDPOINT = "https://www.google.com/recaptcha/api/siteverify";

    /**
     * @var bool
     */
    private $isEnabled;
    /**
     * @var mixed
     */
    private $apiKey;
    /**
     * @var mixed
     */
    private $apiSecret;
    /**
     * @var mixed
     */
    private $formsToValidate;

    /**
     * Hevelop_ReCaptcha_Helper_Data constructor.
     */
    public function __construct()
    {
        $this->isEnabled = Mage::getStoreConfigFlag(self::HEVELOP_RECAPTCHA_ENABLE_XML_PATH);
        $this->apiKey = Mage::getStoreConfig(self::HEVELOP_RECAPTCHA_API_KEY_XML_PATH);
        $this->apiSecret = Mage::getStoreConfig(self::HEVELOP_RECAPTCHA_API_SECRET_XML_PATH);
        $this->formsToValidate = Mage::getStoreConfig(self::HEVELOP_RECAPTCHA_FORMS_TO_VALIDATE_XML_PATH);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled && (!empty($this->apiKey) && !empty($this->apiSecret));
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return mixed
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @return false|string[]
     */
    public function getFormsToValidate()
    {
        if (empty($this->formsToValidate)) {
            return false;
        }

        return explode(",", $this->formsToValidate);
    }

    /**
     * @return array|false|string
     */
    public function getVisitorIp()
    {
        return getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
            getenv('HTTP_X_FORWARDED')?:
            getenv('HTTP_FORWARDED_FOR')?:
            getenv('HTTP_FORWARDED')?:
            getenv('REMOTE_ADDR');

    }
}