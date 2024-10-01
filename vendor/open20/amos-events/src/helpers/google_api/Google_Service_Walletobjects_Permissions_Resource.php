<?php
namespace open20\amos\events\helpers\google_api;

use Google_Service_Resource;


/**
 * The "permissions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $permissions = $walletobjectsService->permissions;
 *  </code>
 */
class Google_Service_Walletobjects_Permissions_Resource extends Google_Service_Resource
{

    /**
     * Returns the permissions for the given issuer id. (permissions.get)
     *
     * @param string $resourceId The unique identifier for an issuer. This ID must
     * be unique across all issuers.
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_Permissions
     */
    public function get($resourceId, $optParams = array())
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);
        return $this->call('get', array($params), "Google_Service_Walletobjects_Permissions");
    }

    /**
     * Updates the permissions for the given issuer. (permissions.update)
     *
     * @param string $resourceId The unique identifier for an issuer. This ID must
     * be unique across all issuers.
     * @param Google_Permissions $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_Permissions
     */
    public function update($resourceId, Google_Service_Walletobjects_Permissions $postBody, $optParams = array())
    {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('update', array($params), "Google_Service_Walletobjects_Permissions");
    }
}
