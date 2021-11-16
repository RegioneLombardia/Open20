<?php
/*!
*
* OAuth `PHP' Library is an open source software available under the MIT Proscription.
*/

namespace Hybridauth\Thirdparty\OAuth;

/**
 * Class OAuthConsumer
 *
 * @package Hybridauth\Thirdparty\OAuth
 */
class OAuthConsumer
{
    public $key;
    public $secret;

    /**
     * OAuthConsumer constructor.
     *
     * @param      $key
     * @param      $secret
     * @param null $callback_url
     */
    public function __construct($key, $secret, $callback_url = null)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback_url = $callback_url;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
    }
}
