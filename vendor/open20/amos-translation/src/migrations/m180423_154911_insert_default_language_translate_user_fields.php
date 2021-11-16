<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\migrations
 * @category   CategoryName
 */

use open20\amos\translation\AmosTranslation;
use open20\amos\translation\models\LanguageTranslateUserFields;
use yii\db\ActiveRecord;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m180423_154911_insert_default_language_translate_user_fields
 */
class m180423_154911_insert_default_language_translate_user_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /** @var AmosTranslation $translationModule */
        $translationModule = Yii::$app->getModule(AmosTranslation::getModuleName());
        /** @var ActiveRecord $className */
        $className = $translationModule->modelOwnerPlatformTranslation;
        $query = new Query();
        $query->from($className::tableName());
        $allTranslations = $query->all();
        foreach ($allTranslations as $translation) {
            $langTranslateUserFieldsQuery = new Query();
            $langTranslateUserFieldsQuery->from(LanguageTranslateUserFields::tableName());
            $langTranslateUserFieldsQuery->andWhere([
                'language_translate_id' => $translation[$translationModule->modelOwnerPlatformTrIdField],
                'language_translate_language' => $translation[$translationModule->modelOwnerPlatformTrLanguageField]
            ]);
            $exists = $langTranslateUserFieldsQuery->exists();
            if (!$exists) {
                $now = date('Y-m-d H:i:s');
                $this->insert(LanguageTranslateUserFields::tableName(), [
                    'language_translate_id' => $translation[$translationModule->modelOwnerPlatformTrIdField],
                    'language_translate_language' => $translation[$translationModule->modelOwnerPlatformTrLanguageField],
                    'created_at' => $now,
                    'created_by' => 1,
                    'updated_at' => $now,
                    'updated_by' => 1
                ]);
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180423_154911_insert_default_language_translate_user_fields cannot be reverted.\n";
        return false;
    }
}
