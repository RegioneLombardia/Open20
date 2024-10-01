<?php
namespace open20\amos\socialauth\providers;

use Hybridauth\Adapter\OAuth2;

class Amos extends OAuth2 {
    /**
     * {@inheritdoc}
     */
    protected $domain;

    /**
     * {@inheritdoc}
     */
    protected $protocol='https';

    /**
     * {@inheritdoc}
     */
    protected $scope = 'userinfo.profile';

    /**
     * Define codes for tags tree to syncronize, for ex. 'ambiti-tecnologici'
     *
     * @var array $syncronizeTagsTreeCode array of tagsTree codes on taget platform to retrieve
     */
    public $syncronizeTagsTreeCodes = [];


    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = '';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = '';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = '';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenInfoUrl = '';

    /**
     * {@inheritdoc}
     */
    public function initialize() {
        parent::initialize();

        //Provider configs from the collection
        $configs = $this->config->toArray();

        //Pull work domain
        $this->domain = $configs['wrapper']['domain'];

        // Provider api end-points
        $this->authorizeUrl = "{$this->protocol}://{$this->domain}/socialauth/oauth2/auth";
        $this->accessTokenUrl = "{$this->protocol}://{$this->domain}/socialauth/oauth2/token";
        $this->accessTokenInfoUrl = "{$this->protocol}://{$this->domain}/socialauth/oauth2/tokeninfo";


        $this->tokenRefreshParameters['client_id'] = $this->clientId;
        $this->tokenRefreshParameters['client_secret'] = $this->clientSecret;

        if (isset($configs['redirect_uri']) && !empty($configs['redirect_uri'])) {
            $this->setCallback($configs['redirect_uri']);
        }

        // Define TagsTree to syncronize on target platform
        $this->syncronizeTagsTreeCodes = $this->config->get('syncronizeTagsTreeCodes')
            ? $this->config->get('syncronizeTagsTreeCodes')
            : [];
    }

    /**
     * {@inheritdoc}
     */
    public function loginBegin() {
        //Provider configs from the collection
        $configs = $this->config->toArray();

        $parameters = array("scope" => $this->scope, "access_type" => "offline");
        $optionals = array("scope", "access_type", "redirect_uri", "approval_prompt", "hd", "state", 'tagstree');

        foreach ($optionals as $parameter) {
            if (isset($configs[$parameter]) && !empty($configs[$parameter])) {
                $parameters[$parameter] = $configs[$parameter];
            }
            if (isset($configs["scope"]) && !empty($configs["scope"])) {
                $this->scope = $configs["scope"];
            }
        }

        if (isset($configs['force']) && $configs['force'] === true) {
            $parameters['approval_prompt'] = 'force';
        }

        if ($this->syncronizeTagsTreeCodes != []) {
            $parameters['tagstree'] = json_encode($this->syncronizeTagsTreeCodes);
        }

        \Hybrid_Auth::redirect($this->api->authorizeUrl($parameters));
    }

    /**
     * {@inheritdoc}
     */
    public function getUserProfile() {
        // refresh tokens if needed
        $this->refreshAccessToken();

        $userInfoUrl = $this->addUrlParam("{$this->protocol}://{$this->domain}/socialauth/oauth2/userinfo", [
            'tagstree' => $this->syncronizeTagsTreeCodes
        ]);

        $response = $this->apiRequest($userInfoUrl);

        if (!isset($response->sub) || isset($response->error)) {
            throw new \Exception("User profile request failed! {$this->providerId} returned an invalid response:" . \Hybrid_Logger::dumpData( $response ), 6);
        }

        $this->user->profile->identifier = (property_exists($response, 'sub')) ? $response->sub : "";
        $this->user->profile->firstName = (property_exists($response, 'name')) ? $response->name : "";
        $this->user->profile->lastName = (property_exists($response, 'family_name')) ? $response->family_name : "";
        $this->user->profile->displayName = (property_exists($response, 'given_name')) ? $response->given_name : "";
        $this->user->profile->photoURL = (property_exists($response, 'picture')) ? $response->picture : "";
        $this->user->profile->profileURL = (property_exists($response, 'profile')) ? $response->profile : "";
        $this->user->profile->gender = (property_exists($response, 'gender')) ? $response->gender : "";
        $this->user->profile->language = (property_exists($response, 'locale')) ? $response->locale : "";
        $this->user->profile->email = (property_exists($response, 'email')) ? $response->email : "";
        $this->user->profile->emailVerified = (property_exists($response, 'email_verified')) ? ($response->email_verified === true || $response->email_verified === 1 ? $response->email : "") : "";
        $this->user->profile->tagscode = $response->tagscode;

        return $this->user->profile;
    }

    /**
     * Add query parameters to the $url
     *
     * @param string $url    URL
     * @param array  $params Parameters to add
     * @return string
     */
    public function addUrlParam($url, array $params){
        $query = parse_url($url, PHP_URL_QUERY);

        // Returns the URL string with new parameters
        if ($query) {
            $url .= '&' . http_build_query($params);
        } else {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

}

