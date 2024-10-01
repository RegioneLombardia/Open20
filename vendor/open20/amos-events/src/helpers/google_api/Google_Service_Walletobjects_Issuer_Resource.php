<?php
namespace open20\amos\events\helpers\google_api;

use Google_Service_Resource;

/**
 * The "issuer" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $issuer = $walletobjectsService->issuer;
 *  </code>
 */
class Google_Service_Walletobjects_Issuer_Resource extends Google_Service_Resource
{

    /**
     * Returns the issuer with the given issuer ID. (issuer.get)
     *
     * @param string $resourceId The unique identifier for an issuer. This ID must
     * be unique across all issuers.
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_Issuer
     */
    public function get($resourceId, $optParams = array())
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);
        return $this->call('get', array($params), "Google_Service_Walletobjects_Issuer");
    }

    /**
     * Inserts an issuer with the given ID and properties. (issuer.insert)
     *
     * @param Google_Issuer $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_Issuer
     */
    public function insert(Google_Service_Walletobjects_Issuer $postBody, $optParams = array())
    {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('insert', array($params), "Google_Service_Walletobjects_Issuer");
    }

    /**
     * Returns a list of all issuers shared to the caller. (issuer.listIssuer)
     *
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_IssuerListResponse
     */
    public function listIssuer($optParams = array())
    {
        $params = array();
        $params = array_merge($params, $optParams);
        return $this->call('list', array($params), "Google_Service_Walletobjects_IssuerListResponse");
    }

    /**
     * Updates the issuer referenced by the given issuer ID. This method supports
     * patch semantics. (issuer.patch)
     *
     * @param string $resourceId The unique identifier for an issuer. This ID must
     * be unique across all issuers.
     * @param Google_Issuer $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_Issuer
     */
    public function patch($resourceId, Google_Service_Walletobjects_Issuer $postBody, $optParams = array())
    {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('patch', array($params), "Google_Service_Walletobjects_Issuer");
    }

    /**
     * Updates the issuer referenced by the given issuer ID. (issuer.update)
     *
     * @param string $resourceId The unique identifier for an issuer. This ID must
     * be unique across all issuers.
     * @param Google_Issuer $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_Issuer
     */
    public function update($resourceId, Google_Service_Walletobjects_Issuer $postBody, $optParams = array())
    {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('update', array($params), "Google_Service_Walletobjects_Issuer");
    }
}
