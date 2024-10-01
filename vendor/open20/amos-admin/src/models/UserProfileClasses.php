<?php

namespace open20\amos\admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\admin\AmosAdmin;

/**
 * This is the model class for table "user_profile_classes".
 */
class UserProfileClasses extends \open20\amos\admin\models\base\UserProfileClasses {

    public $configuration;

    /**
     * @var Item
     */
    private $_item;

    public function representingColumn() {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints() {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute) {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
                    ['configuration', 'safe']
        ]);
    }

    public function attributeLabels() {
        return
                ArrayHelper::merge(
                        parent::attributeLabels(),
                        [
                            'configuration' => AmosAdmin::t('amosadmin', 'Configurazione dei permessi e dei ruoli'),
        ]);
    }

    public function getEditFields() {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'code',
                'label' => $labels['code'],
                'type' => 'string'
            ],
            [
                'slug' => 'enabled',
                'label' => $labels['enabled'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker() {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents() {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent() {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event 
     */
    public function getColorEvent() {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent() {
        return NULL; //TODO
    }

    public function getItems() {
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRoles();
        $permissions = $authManager->getPermissions();
        $advanced = \mdm\admin\components\Configs::instance()->advanced;

        foreach (array_keys($roles) as $name) {
            $available[$name] = 'role';
        }

        foreach (array_keys($permissions) as $name) {
            $available[$name] = $name[0] == '/' || $advanced && $name[0] == '@' ? 'route' : 'permission';
        }

        $assigned = [];
        foreach ($authManager->getChildren($this->_item->name) as $item) {
            $assigned[$item->name] = $item->type == 1 ? 'role' : ($item->name[0] == '/' || $advanced && $item->name[0] == '@' ? 'route' : 'permission');
            unset($available[$item->name]);
        }
        ksort($available);
        ksort($assigned);

        $allPermissions = [];
        foreach ($available as $k => $v) {
            $allPermissions[] = [$k => $k];
        }

        return $allPermissions;
    }

    public function assignPermissions($post) {
        if (!empty($post['UserProfileClasses']['configuration'])) {
            $allPermissions = $post['UserProfileClasses']['configuration'];
            \open20\amos\admin\models\UserProfileClassesAuthMm::deleteAll(['user_profile_classes_id' => $this->id]);
            foreach ($allPermissions as $v) {
                $newAuth = new UserProfileClassesAuthMm();
                $newAuth->user_profile_classes_id = $this->id;
                $newAuth->item_id = $v;
                $newAuth->save(false);
            }
        }
    }

    public function updatePermissionToUsers() {
        $module = \Yii::$app->getModule(AmosAdmin::getModuleName());
        if ($module->enableForceRoleByProfiles == true) {

            $userMm = UserProfileClassesUserMm::find()->asArray()->all();
            $auth = \Yii::$app->authManager;
            try {
                \Yii::$app->authManager->cache->flush();
            } catch (Exception $ex) {
                
            }
            foreach ($userMm as $mm) {
                $user = \open20\amos\core\user\User::findOne($mm['user_id']);
                if (!empty($user) && $user->id > 1) {
                    $userAssignments = [];
                    $assignments = $auth->getAssignments($user->id);
                    foreach ((array) $assignments as $k => $s) {
                        $userAssignments[] = $k;
                    }

                    $allPermissions = UserProfileClassesUserMm::find()
                            ->innerJoin(UserProfileClassesAuthMm::tableName(),
                                    'user_profile_classes_auth_mm.user_profile_classes_id = user_profile_classes_user_mm.user_profile_classes_id')
                            ->andWhere(['user_id' => $user->id])
                            ->select('item_id')
                            ->distinct()
                            ->column();
                    $del = array_diff($userAssignments, $allPermissions);

                    if (!empty($del)) {
                        foreach ($del as $d) {
                            $role = $auth->getRole($d);
                            if (empty($role)) {
                                $role = $auth->getPermission($d);
                            }
                            if (!empty($role) && array_key_exists($role->name, $assignments)) {
                                $auth->revoke($role, $user->id);
                            }
                        }
                    }
                    foreach ($allPermissions as $v) {
                        $role = $auth->getRole($v);
                        if (empty($role)) {
                            $role = $auth->getPermission($v);
                        }
                        if (!empty($role) && !array_key_exists($role->name, $assignments)) {
                            try {
                                $auth->assign($role, $user->id);
                            } catch (\yii\db\IntegrityException $ex) {
                                
                            }
                        }
                    }
                }
            }
        }
    }
}
