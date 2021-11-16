<?php

namespace app\modules\cmsapi\frontend\models;

use yii\db\ActiveRecord;

class CmsMailAfterLogin extends ActiveRecord
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            // name, email, subject and body are required
            [[ 'subject', 'body', 'layout_email'], 'string'],
            // email has to be a valid email address
            ['email_from', 'string'],
            ['email_to', 'string'],
            ['email_cc', 'string'],
        ];
    }

    /**
     */
    public static function tableName()
    {
        return '{{%cms_mail_after_login}}';
    }

    
}