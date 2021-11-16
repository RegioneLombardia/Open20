<?php

namespace amos\newsletter\models\base;

use Yii;

/**
 * This is the base-model class for table "newsletter_service_mail_fields_mm".
 *
 * @property integer $id
 * @property integer $newsletter_id
 * @property integer $service_mail_field_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  NewsletterServiceMailFieldsMm extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newsletter_service_mail_fields_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsletter_id', 'service_mail_field_id'], 'required'],
            [['newsletter_id', 'service_mail_field_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'newsletter_id' => Yii::t('app', 'Newsletter'),
            'service_mail_field_id' => Yii::t('app', 'Service mail Field'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'deleted_at' => Yii::t('app', 'Deleted at'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated at'),
            'deleted_by' => Yii::t('app', 'Deleted at'),
        ];
    }
}
