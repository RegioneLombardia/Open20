<?php

namespace open20\amos\cwh\base;

/**
 * Interface ModelNetworkInterface
 *
 * @package open20\amos\cwh\base
 */
interface ModelNetworkInterface
{
    /**
     * Get the user id used in network-users association table
     * @return int
     */
    public function getUserId();

    /**
     * Get the name of the table storing network-users associations
     * @return string
     */
    public function getMmTableName();

    /**
     * Get the name of field that contains user id in network-users association table
     * @return string
     */
    public function getMmNetworkIdFieldName();

    /**
     * Get the name of field that contains network id in network-users association table
     * @return string
     */
    public function getMmUserIdFieldName();

    /**
     * Return true if the user with id $userId belong to the network with id $networkId; if $userId is null the logged User id is considered
     * @param  int $networkId
     * @param int $userId
     * @return bool
     */
    public function isNetworkUser($networkId, $userId = null);

    /**
     * Return true if the network is validated or no validation process is implemented for the network.
     * if $networkId is null, current network (this) is condidered
     * @param int $networkId
     * @return bool
     */
    public function isValidated($networkId = null);

    /**
     * Return classname of the MM table connecting user and network
     * @return string
     */
    public function getMmClassName();
}