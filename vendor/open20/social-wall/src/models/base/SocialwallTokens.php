<?php

namespace open20\socialwall\models\base;

use open20\amos\core\record\Record;
use Yii;

/**
 * This is the base-model class for table "socialwall_tokens".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $data
 * @property string $secret
 * @property string $expires_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  SocialwallTokens extends Record
{
    public $isSearch = false;

    public function beforeSave($insert)
    {
        $this->secret = Yii::$app->security->generateRandomString(30);
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socialwall_tokens';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['data'], 'string'],
            [['data'], 'default', 'value' => '{}'],
            [['expires_at', 'created_at', 'updated_at', 'deleted_at', 'twitter_username', 'twitter_token'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'type'], 'string', 'max' => 255],
            [['secret'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('socialwall', 'ID'),
            'name' => Yii::t('socialwall', 'Nome'),
            'type' => Yii::t('socialwall', 'Tipo'),
            'data' => Yii::t('socialwall', 'Token Data'),
            'secret' => Yii::t('socialwall', 'Secret for getting api'),
            'twitter_username' => Yii::t('socialwall', 'Nome utente Twitter'),
            'twitter_token' => Yii::t('socialwall', 'Token Twitter'),
            'expires_at' => Yii::t('socialwall', 'Scadrà il'),
            'created_at' => Yii::t('socialwall', 'Created at'),
            'updated_at' => Yii::t('socialwall', 'Updated at'),
            'deleted_at' => Yii::t('socialwall', 'Deleted at'),
            'created_by' => Yii::t('socialwall', 'Created by'),
            'updated_by' => Yii::t('socialwall', 'Updated at'),
            'deleted_by' => Yii::t('socialwall', 'Deleted at'),
        ];
    }

}
