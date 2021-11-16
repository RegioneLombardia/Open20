<?php

namespace open20\amos\admin\models\base;

use Yii;

/**
 * This is the base-model class for table "token_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $string_code
 * @property string $Description
 * @property string $url_redirect
 * @property string $target_class
 * @property integer $target_id
 * @property integer $consumable
 * @property string $expire_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property TokenUsers[] $tokenUsers
 */
class TokenGroup extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','string_code'], 'required'],
//            [['target_class', 'target_id'], 'unique', 'targetAttribute' => ['target_class', 'target_id']],
            [['target_id', 'consumable', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['expire_date', 'created_at', 'updated_at', 'deleted_at','string_code'], 'safe'],
            [['name', 'Description', 'url_redirect', 'target_class'], 'string', 'max' => 255],
            ['string_code', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosadmin', 'ID'),
            'name' => Yii::t('amosadmin', 'Name'),
            'Description' => Yii::t('amosadmin', 'Description'),
            'url_redirect' => Yii::t('amosadmin', 'Url redirect'),
            'target_class' => Yii::t('amosadmin', 'Target class'),
            'target_id' => Yii::t('amosadmin', 'Target id'),
            'consumable' => Yii::t('amosadmin', 'Consumable'),
            'expire_date' => Yii::t('amosadmin', 'Expire date'),
            'created_at' => Yii::t('amosadmin', 'Created at'),
            'updated_at' => Yii::t('amosadmin', 'Updated at'),
            'deleted_at' => Yii::t('amosadmin', 'Deleted at'),
            'created_by' => Yii::t('amosadmin', 'Created by'),
            'updated_by' => Yii::t('amosadmin', 'Updated at'),
            'deleted_by' => Yii::t('amosadmin', 'Deleted at'),
        ];
    }

    public function checkTargetClass($attribute){
        TokenGroup::find()->andWhere(['target_class' => $this->target_class])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokenUsers()
    {
        return $this->hasMany(TokenUsers::className(), ['token_group_id' => 'id']);
    }


}
