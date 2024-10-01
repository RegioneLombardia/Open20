<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\helpers
 * @category   CategoryName
 */

namespace open20\amos\core\helpers;

use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\record\Record;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Class PermissionHelper
 * Generic functionality of use for permission management
 * @package open20\amos\core\helpers
 */
class PermissionHelper {

    /**
     * Map of the shares, this array describes specific actions in Actions attributable to Permission
     * @var array
     */
    private static $exceptionActionMap = [
        'VIEW' => 'READ',
        'INDEX' => 'READ',
        'UPDATE-PROFILE' => 'UPDATE',
    ];

    /**
     * @param $modelName
     * @param $action
     * Depending on the model name and the action passed it will check that it exists between system permits.
     * if this permission exists is returned the string rappresent the permission, else, that the action is
     * an "exception action". if so string represents the permission is returned
     * if no permission match return null
     * @return null|string - NULL is for no permission find - STRING reppresent the permission find
     */
    public static function findPermissionModelAction($modelName, $action) {
        $permission = strtoupper($modelName) . '_' . strtoupper($action);
        $permissionsAvaiable = \Yii::$app->authManager->getPermissions();
        if (isset($permissionsAvaiable[$permission])) {
            return $permission;
        } else {
            if (isset(self::$exceptionActionMap[strtoupper($action)])) {
                return strtoupper($modelName) . '_' . self::$exceptionActionMap[strtoupper($action)];
            } else {
                return null;
            }
            $permission = strtoupper($modelName) . '-' . strtoupper($action);
        }
    }

    public static function checkPermissionModelByUser($model, $action) {
        if (!\Yii::$app->user->isGuest) {
            $permission = strtoupper(StringHelper::baseName(get_class($model)) . '_' . $action);
            $permission2 = get_class($model) . '_' . strtoupper($action);
            if (\Yii::$app->user->can($permission, ['model' => $model])) {
                return true;
            } else if (\Yii::$app->user->can($permission2, ['model' => $model])) {
                return true;
            }
        }
        return false;
    }

    /**
     *  You can view this content return true other case false
     *
     * @param Record $model
     * @return bool
     * @throws InvalidConfigException
     */
    public static function canViewContent($model) {
        $check = self::checkVisibilityContent($model);
        if ((\Yii::$app->user->isGuest)) {
            return $check;
        } else if (!$check) {
            return self::checkPermissionModelByUser($model, 'update');
        }
        return true;
    }

    /**
     *
     * @param type $model
     * @return bool
     * @throws InvalidConfigException
     */
    protected static function checkVisibilityContent($model) {
        // verifico se lo stato non è pubblicato (validato) (getValidatedStatus)        
        if (method_exists($model, 'getValidatedStatus') && property_exists($model, 'status')) {
            if ($model->status != $model->getValidatedStatus()) {
                return false;
            }
        }

        // se la data odierna è minore alla data di pubblicazione (getPublicatedFrom)       
        if (method_exists($model, 'getPublicatedFrom')) {
            if (\Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s') < $model->getPublicatedFrom()) {
                return false;
            }
        }

        // se la data termine pubblicazione, se impostata, è minore
        // della data odierna (getPublicatedAt) non visualizzo il contenuto
        if (method_exists($model, 'getPublicatedAt')) {
            if (!empty($model->getPublicatedAt()) &&
                    $model->getPublicatedAt() < \Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s')) {
                return false;
            }
        }
        return true;
    }
}
