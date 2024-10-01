<?php

namespace app\modules\cms\components;

use luya\admin\models\UserOnline;
use Yii;

class AdminUser extends \luya\admin\components\AdminUser {

    /**
     * @inheritdoc
     */
    public $identityClass = '\app\models\LuyaUser';

    public function init() {
        parent::init();
        $this->idParam = '__luyaAdmin_id';
    }

    public static function isAdmin() {

        $user_id = Yii::$app->adminuser->id;
        $isAdmin = false;

        $query = (new \yii\db\Query)->from('admin_user_group')->where(['user_id' => $user_id])->one();
        if ($query && $query['group_id'] == 1) {
            $isAdmin = true;
        }

        return $isAdmin;
    }

    /**
     * After loging out, the useronline status must be refreshed and the current user must be deleted from the user online list.
     */
    public function onBeforeLogout() {
        UserOnline::removeUser($this->id);

        $this->identity->updateAttributes([
            'auth_token' => Yii::$app->security->hashData(Yii::$app->security->generateRandomString(), $this->identity->password_salt),
        ]);
    }
}
