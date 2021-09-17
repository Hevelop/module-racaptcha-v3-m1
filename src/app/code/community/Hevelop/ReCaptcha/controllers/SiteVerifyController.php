<?php

/**
 * Class Hevelop_ReCaptcha_SiteVerifyController
 */
class Hevelop_ReCaptcha_SiteVerifyController extends Mage_Core_Controller_Front_Action
{
    /**
     * @var Hevelop_ReCaptcha_Helper_Data|null
     */
    private $helper;

    /**
     * Hevelop_ReCaptcha_SiteVerifyController constructor.
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs
     */
    public function __construct(
        Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->helper = Mage::helper('hevelop_racaptcha');
        parent::__construct($request, $response, $invokeArgs);
    }


    public function indexAction() {
        // Token Response
        $requestData = $this->getRequest()->getRawBody();
        $token = "";

        if (!empty($requestData)) {
            $requestData = json_decode($requestData, true);
            if (is_array($requestData) && isset($requestData['response'])) {
                $token = $requestData['response'];
            }
        }

        // We build the request to the google api endpoint
        $curlVerifyRecaptcha = curl_init();
        curl_setopt($curlVerifyRecaptcha, CURLOPT_URL,
            Hevelop_ReCaptcha_Helper_Data::HEVELOP_RECAPTCHA_SITE_VERIFY_ENDPOINT);
        curl_setopt($curlVerifyRecaptcha, CURLOPT_POST, true);
        curl_setopt($curlVerifyRecaptcha, CURLOPT_POSTFIELDS, http_build_query(
            array(
                "secret"    =>  $this->helper->getApiSecret(),
                "response"  =>  $token,
                "remoteip"  =>  $this->helper->getVisitorIp()
            )
        ));
        curl_setopt($curlVerifyRecaptcha, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlVerifyRecaptcha, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curlVerifyRecaptcha);

        return $this->getResponse()->setBody($result);
    }
}