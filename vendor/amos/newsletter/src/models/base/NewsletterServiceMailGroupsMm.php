<?php

namespace open20\amos\newsletter\models\base;

use Yii;

/**
 * This is the base-model class for table "newsletter_service_mail_groups_mm".
 *
 * @property integer $id
 * @property integer $newsletter_id
 * @property integer $service_mail_group_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  NewsletterServiceMailGroupsMm extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newsletter_service_mail_groups_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsletter_id', 'service_mail_group_id'], 'required'],
            [['newsletter_id', 'service_mail_group_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosnews', 'ID'),
            'newsletter_id' => Yii::t('amosnews', 'Newsletter'),
            'service_mail_group_id' => Yii::t('amosnews', 'Service mail Group'),
            'created_at' => Yii::t('amosnews', 'Created at'),
            'updated_at' => Yii::t('amosnews', 'Updated at'),
            'deleted_at' => Yii::t('amosnews', 'Deleted at'),
            'created_by' => Yii::t('amosnews', 'Created by'),
            'updated_by' => Yii::t('amosnews', 'Updated at'),
            'deleted_by' => Yii::t('amosnews', 'Deleted at'),
        ];
    }
}
