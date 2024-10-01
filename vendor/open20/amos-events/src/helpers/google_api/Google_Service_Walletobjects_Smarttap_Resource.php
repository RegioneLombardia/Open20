<?php
namespace open20\amos\events\helpers\google_api;

use Google_Service_Resource;

/**
 * The "smarttap" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $smarttap = $walletobjectsService->smarttap;
 *  </code>
 */
class Google_Service_Walletobjects_Smarttap_Resource extends Google_Service_Resource
{

    /**
     * Inserts the smart tap. (smarttap.insert)
     *
     * @param Google_SmartTap $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_SmartTap
     */
    public function insert(Google_Service_Walletobjects_SmartTap $postBody, $optParams = array())
    {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('insert', array($params), "Google_Service_Walletobjects_SmartTap");
    }
}
