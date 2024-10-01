<?php

namespace open20\amos\translation\models;

use Yii;

/**
 * This is the base-model class for table "translation_user_language_mm".
 *
 * @property integer $user_id
 * @property string $language
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \backend\models\Language $language0
 * @property \backend\models\User $user
 */
class TranslationUserLanguageMm extends \open20\amos\core\record\Record {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'translation_user_language_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'required'],
            [['user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['language'], 'safe'],
            [['user_id', 'language'], 'unique', 'targetAttribute' => ['user_id', 'language']],
            [['language'], 'exist', 'skipOnError' => true, 'targetClass' => \lajax\translatemanager\models\Language::className(), 'targetAttribute' => ['language' => 'language_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => Yii::t('amostranslation', 'User ID'),
            'language' => Yii::t('amostranslation', 'Language'),
            'created_at' => Yii::t('amostranslation', 'Created At'),
            'updated_at' => Yii::t('amostranslation', 'Updated At'),
            'deleted_at' => Yii::t('amostranslation', 'Deleted At'),
            'created_by' => Yii::t('amostranslation', 'Created By'),
            'updated_by' => Yii::t('amostranslation', 'Updated By'),
            'deleted_by' => Yii::t('amostranslation', 'Deleted By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage0() {
        return $this->hasOne(\lajax\translatemanager\models\Language::className(), ['language_id' => 'language']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }

}
