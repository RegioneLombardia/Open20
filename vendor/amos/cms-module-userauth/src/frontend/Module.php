<?php

namespace amos\userauth\frontend;

use luya\base\Module as BaseModule;

class Module extends BaseModule
{
    const USERAUTH_CONFIG_REDIRECT_NAV_ID     = 'userauth_redirect_nav_id';
    const USERAUTH_CONFIG_AFTER_LOGIN_NAV_ID  = 'userauth_afterlogin_nav_id';
    const NOPERMISSION_CONFIG_REDIRECT_NAV_ID = 'nopermission_redirect_nav_id';

    private static $moduleName = 'userauthfrontend';
    public $enableSPID   = false;
    public $enableSocial = false;
    public $enableRegister = true;
    public $enableUserPasswordLogin = true;
    public $remember_length = 8600 * 24 * 30;
    public $viewLayout = null;
    
    /**
     * @var bool
     */
    public $precompileUsernameOnFirstAccess = false;

    public static function getModuleName()
    {
        return static::$moduleName;
    }

    public static function setModuleName($name)
    {
        static::$moduleName = $name;
    }

    /**
     * Return an instance of module
     *
     * @return AmosModule
     */
    public static function instance()
    {
        /*         * @var AmosModule $module */
        $module = \Yii::$app->getModule(static::getModuleName());
        return $module;
    }


    /**
     *
     * @param string $url
     * @return string
     */
    public static function toUrlModule($url)
    {
        return '/' . static::getModuleName().$url;
    }
    
    /**
     * @inheritdoc
     */
    public static function onLoad()
    {
        self::registerTranslation('userauth',
            static::staticBasePath().'/messages',
            [
                'userauth' => 'userauth.php',
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function t($message, array $params = [])
    {
        return parent::baseT('userauth', $message, $params);
    }
}