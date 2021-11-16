<?php
/**
 */

namespace conquer\oauth2\granttypes;

use conquer\oauth2\BaseModel;
use conquer\oauth2\models\AccessToken;

/**
 */
class ClientCredentials extends BaseModel
{
    /**
     * Value MUST be set to "client_credentials"
     * @var string
     */
    public $grant_type;

    /**
     * Access Token Scope
     * @var string
     */
    public $scope;

    /**
     * @var string
     */
    public $client_id;

    /**
     * @var string
     */
    public $client_secret;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grant_type', 'client_id', 'client_secret'], 'required'],
            ['grant_type', 'required', 'requiredValue' => 'client_credentials'],
            [['client_id'], 'string', 'max' => 80],
            [['client_id'], 'validateClientId'],
            [['client_secret'], 'validateClientSecret'],
            [['scope'], 'validateScope'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getResponseData()
    {
        $accessToken = AccessToken::createAccessToken([
            'client_id' => $this->client_id,
            'expires' => $this->accessTokenLifetime + time(),
            'scope' => $this->scope,
        ]);

        return [
            'access_token' => $accessToken->access_token,
            'expires_in' => $this->accessTokenLifetime,
            'token_type' => $this->tokenType,
            'scope' => $this->scope,
        ];
    }
}
