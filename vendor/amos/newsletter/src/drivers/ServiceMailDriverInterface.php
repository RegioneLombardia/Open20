<?php

namespace amos\newsletter\drivers;


interface ServiceMailDriverInterface
{

    // ACTIONS API
    public function getSubscribtionsToGroup($group_id, $params);

    public function subscribeToGroup($group_id, $params);

    public function subscribeToList($list_id, $params);

    public function oauth2Autentication();

    public function getDynamicFields($params);

    public function getLists();

    public function getSubscribersByList($id, $params);

    public function getGroupsByList($id);

    public function checkIfSubscriberExist($list_id, $email);


    //CONFIGURATIONS
    //----------------------------------
    /** @return string */
    public function getMailServiceName();

    /** @return  array ['field', 'field2]*/
    public function getListFields();

    /** @return  array ['field' => 'label']*/
    public function getListLabels();

    /** @return  array ['field', 'field2]*/
    public function getGroupFields();

    /** @return  array ['field' => 'label']*/
    public function getGroupLabels();

    /** @return  array ['field', 'field2]*/
    public function getSubscriberFields();

    /** @return  array ['field' => 'label']*/
    public function getSubscriberLabels();

    /**  @return  array */
    public function getPaginationConfigs();

    /**
     * @param $queryParams
     * @param $searchParams
     * @return mixed
     */
    public function buildQueryParams($queryParams, $searchParams);

    /**
     * @return array
     */
    public function getSearchField();




}