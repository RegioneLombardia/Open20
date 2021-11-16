<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-mobile-bridge
 * @category   CategoryName
 */
namespace open20\amos\mobile\bridge\modules\v1\models;

use common\components\helpers\UserHelper;
use yii\db\Expression;

class AccessTokens extends \open20\amos\mobile\bridge\modules\v1\models\base\AccessTokens
{

    public static function primaryKey()
    {
        return [
            'access_token'
        ];
    }

    public function logout()
    {
        $this->logout_at = new Expression('NOW()');
        $this->logout_by = UserHelper::get()->getId();
        $this->save(false);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
