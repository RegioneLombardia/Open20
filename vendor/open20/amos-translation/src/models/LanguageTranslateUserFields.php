<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\models
 * @category   CategoryName
 */

namespace open20\amos\translation\models;

use open20\amos\translation\AmosTranslation;
use yii\db\ActiveRecord;

/**
 * Class LanguageTranslateUserFields
 *
 * This is the model class for table "language_translate_user_fields".
 *
 * @property integer $id
 * @property integer $language_translate_id
 * @property string $language_translate_language
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package open20\amos\translation\models
 */
class LanguageTranslateUserFields extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language_translate_user_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_translate_id', 'language_translate_language'], 'required'],
            [['language_translate_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['language_translate_language'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosTranslation::t('amostranslation', 'ID'),
            'language_translate_id' => AmosTranslation::t('amostranslation', 'Language Translate ID'),
            'language_translate_language' => AmosTranslation::t('amostranslation', 'Language Translate Language'),
            'created_at' => AmosTranslation::t('amostranslation', 'Created at'),
            'updated_at' => AmosTranslation::t('amostranslation', 'Updated at'),
            'deleted_at' => AmosTranslation::t('amostranslation', 'Deleted at'),
            'created_by' => AmosTranslation::t('amostranslation', 'Created by'),
            'updated_by' => AmosTranslation::t('amostranslation', 'Updated by'),
            'deleted_by' => AmosTranslation::t('amostranslation', 'Deleted by')
        ];
    }
}
