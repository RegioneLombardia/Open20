<?php
namespace open20\amos\events\helpers\google_api;

use Google_Service_Resource;

/**
 * The "jwt" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $jwt = $walletobjectsService->jwt;
 *  </code>
 */
class Google_Service_Walletobjects_Jwt_Resource extends Google_Service_Resource
{

    /**
     * Inserts the resources in the JWT. (jwt.insert)
     *
     * @param Google_JwtResource $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_JwtInsertResponse
     */
    public function insert(Google_Service_Walletobjects_JwtResource $postBody, $optParams = array())
    {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('insert', array($params), "Google_Service_Walletobjects_JwtInsertResponse");
    }
}
