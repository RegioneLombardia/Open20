<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models
 * @category   CategoryName
 */

namespace open20\amos\admin\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\models\AmosModel as Model;
use yii\helpers\ArrayHelper;

/**
 * Class ChangeUserCreateForm
 * @package open20\amos\admin\models
 */
class ChangeUserCreateForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_email_alert")],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'email' => AmosAdmin::t('amosadmin', 'Email'),
        ]);
    }
}
