<?php
namespace open20\design\utility;

use Yii;
use luya\admin\components\Auth;
use luya\admin\models\Group;
use luya\admin\models\User;
use luya\cms\admin\Module as CmsAdminModule;
use luya\cms\models\NavPermission;
use open20\design\Module;
use open20\amos\core\user\User as AmosUser;

class CmsPermissionsUtility
{

    /**
     *
     * @return boolean
     */
    public static function checkUserPermissionsPageUpdate()
    {
        $ret = false;

        $module = Module::getInstance();
        if ($module->checkCmsPermissions) {
            $ret = Yii::$app->auth->matchApi(Yii::$app->adminuser->getId(), CmsAdminModule::ROUTE_PAGE_UDPATE, Auth::CAN_UPDATE);
        }

        return $ret;
    }

    /**
     *
     * @return boolean
     */
    public static function checkUserPermissionsPageDelete()
    {
        $ret = false;

        $module = Module::getInstance();
        if ($module->checkCmsPermissions) {
            $ret = Yii::$app->auth->matchApi(Yii::$app->adminuser->getId(), CmsAdminModule::ROUTE_PAGE_DELETE, Auth::CAN_DELETE);
        }

        return $ret;
    }

    /**
     *
     * @return boolean
     */
    public static function checkUserPermissionsPageCreate()
    {
        $ret = false;

        $module = Module::getInstance();
        if ($module->checkCmsPermissions) {
            $ret = Yii::$app->auth->matchApi(Yii::$app->adminuser->getId(), CmsAdminModule::ROUTE_PAGE_CREATE, Auth::CAN_CREATE);
        }

        return $ret;
    }

    /**
     *
     * @return boolean
     */
    public static function matchRoute()
    {
        $match = false;
        $email = AmosUser::findOne([
            'id' => Yii::$app->user->id
        ])->email;
        $cmsUser = User::findOne([
            'email' => $email
        ]);
        if (! is_null($cmsUser)) {
            $groups = $cmsUser->groups;
            /** @var Group $group */
            foreach ($groups as $group) {
                $permissions = NavPermission::find()->andWhere([
                    'group_id' => $group->id
                ])->count();
                if (! $permissions) {
                    $match = true;
                    break;
                } else {
                    $permissions = NavPermission::find()->andWhere([
                        'group_id' => $group->id,
                        'nav_id' => Yii::$app->menu->current->id
                    ])->count();
                    if ($permissions) {
                        $match = true;
                        break;
                    }
                }
            }
        }
        // check the access inside auth->matchRoute and return true/false.
        return $match;
    }

    /**
     *
     * @return boolean
     */
    public static function canUpdate()
    {
        // se non è settata oppure se è stettata a true controlla i permessi sui gruppi del CMS!
        if (!isset(Yii::$app->params['cms-enable-permission-groups']) || (isset(Yii::$app->params['cms-enable-permission-groups']) && Yii::$app->params['cms-enable-permission-groups'])) {
            return static::matchRoute();
        } else {
            // altrimenti non controllare, il permesso alla modifica del CMS sarà dato solo dal CMS_PAGE_EDITOR
            return false;
        }
    }
}

