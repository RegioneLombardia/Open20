<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\migrations
 * @category   CategoryName
 */

/**
 * Class m170613_150034_permission_translation_administration
 */
class m170613_150034_permission_translation_administration extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $roleAdmin = Yii::$app->authManager->getRole('ADMIN');
        $permTranslAdm = Yii::$app->authManager->getPermission('TRANSLATION_ADMINISTRATOR');
        if (!(Yii::$app->authManager->hasChild($roleAdmin, $permTranslAdm))) {
            Yii::$app->authManager->addChild($roleAdmin, $permTranslAdm);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $roleAdmin = Yii::$app->authManager->getRole('ADMIN');
        $permTranslAdm = Yii::$app->authManager->getPermission('TRANSLATION_ADMINISTRATOR');
        Yii::$app->authManager->removeChild($roleAdmin, $permTranslAdm);
    }
}
