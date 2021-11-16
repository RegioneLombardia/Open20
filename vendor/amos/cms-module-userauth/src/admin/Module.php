<?php

namespace amos\userauth\admin;

use luya\admin\base\Module;
use luya\admin\components\AdminMenuBuilder;


class Module extends Module
{
    public $apis = [
        'api-userauth-user' => 'amos\userauth\admin\apis\UserController',
    ];

    public function getMenu()
    {
        return (new AdminMenuBuilder($this))
                ->node('userauthadmin.admin.menu.node', 'verified_user')
                ->group('userauthadmin.admin.menu.group')
                ->itemApi('userauthadmin.admin.menu.item.user',
                    'userauthadmin/user/index', 'verified_user',
                    'api-userauth-user');
    }

    /**
     * @inheritdoc
     */
    public static function onLoad()
    {
        self::registerTranslation('userauthadmin',
            static::staticBasePath().'/messages',
            [
            'userauthadmin' => 'userauthadmin.php',
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function t($message, array $params = [])
    {
        return parent::baseT('userauthadmin', $message, $params);
    }
}