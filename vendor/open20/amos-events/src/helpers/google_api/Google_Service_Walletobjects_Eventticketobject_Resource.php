<?php
namespace open20\amos\events\helpers\google_api;

use Google_Service_Resource;

/**
 * The "eventticketobject" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $eventticketobject = $walletobjectsService->eventticketobject;
 *  </code>
 */
class Google_Service_Walletobjects_Eventticketobject_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the event ticket object referenced by the given object ID.
     * (eventticketobject.addmessage)
     *
     * @param string $resourceId The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID.identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param Google_AddMessageRequest $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObjectAddMessageResponse
     */
    public function addmessage($resourceId, Google_Service_Walletobjects_AddMessageRequest $postBody, $optParams = array())
    {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('addmessage', array($params), "open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventTicketObjectAddMessageResponse");
    }

    /**
     * Returns the event ticket object with the given object ID.
     * (eventticketobject.get)
     *
     * @param string $resourceId The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID.identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     */
    public function get($resourceId, $optParams = array())
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);
        return $this->call('get', array($params), "open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventTicketObject");
    }

    /**
     * Inserts an event ticket object with the given ID and properties.
     * (eventticketobject.insert)
     *
     * @param Google_EventTicketObject $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     */
    public function insert(Google_Service_Walletobjects_EventTicketObject $postBody, $optParams = array())
    {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('insert', array($params), "open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventTicketObject");
    }

    /**
     * Returns a list of all event ticket objects for a given issuer ID.
     * (eventticketobject.listEventticketobject)
     *
     * @param array $optParams Optional parameters.
     *
     * @opt_param string classId The ID of the class whose objects will be listed.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` objects are available in a list. For
     * example, if you have a list of 200 objects and you call list with
     * `maxResults` set to 20, list will return the first 20 objects and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * objects.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     * @return Google_Service_Walletobjects_EventTicketObjectListResponse
     */
    public function listEventticketobject($optParams = array())
    {
        $params = array();
        $params = array_merge($params, $optParams);
        return $this->call('list', array($params), "open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventTicketObjectListResponse");
    }

    /**
     * Modifies linked offer objects for the event ticket object with the given ID.
     * (eventticketobject.modifylinkedofferobjects)
     *
     * @param string $resourceId The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID.identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param Google_ModifyLinkedOfferObjectsRequest $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     */
    public function modifylinkedofferobjects($resourceId, Google_Service_Walletobjects_ModifyLinkedOfferObjectsRequest $postBody, $optParams = array())
    {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('modifylinkedofferobjects', array($params), "open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventTicketObject");
    }

    /**
     * Updates the event ticket object referenced by the given object ID. This
     * method supports patch semantics. (eventticketobject.patch)
     *
     * @param string $resourceId The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID.identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param Google_EventTicketObject $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     */
    public function patch($resourceId, Google_Service_Walletobjects_EventTicketObject $postBody, $optParams = array())
    {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('patch', array($params), "open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventTicketObject");
    }

    /**
     * Updates the event ticket object referenced by the given object ID.
     * (eventticketobject.update)
     *
     * @param string $resourceId The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID.identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param Google_EventTicketObject $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     */
    public function update($resourceId, Google_Service_Walletobjects_EventTicketObject $postBody, $optParams = array())
    {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);
        return $this->call('update', array($params), "open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventTicketObject");
    }
}

