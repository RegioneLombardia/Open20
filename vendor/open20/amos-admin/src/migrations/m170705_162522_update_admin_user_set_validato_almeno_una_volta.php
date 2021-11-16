<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\core\user\User;
use yii\db\Migration;

class m170705_162522_update_admin_user_set_validato_almeno_una_volta extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $adminUser = User::findOne(['username' => 'admin']);
        if (is_null($adminUser)) {
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', 'Utente admin non trovato'));
            return true;
        }
        try {
            $this->update(UserProfile::tableName(),
                [
                    'validato_almeno_una_volta' => 1,
                    'status' => UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED,
                ],
                [
                    'user_id' => $adminUser->id
                ]);
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', 'Utente admin aggiornato correttamente'));
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $adminUser = User::findOne(['username' => 'admin']);
        if (is_null($adminUser)) {
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', 'Utente admin non trovato'));
            return true;
        }
        try {
            $this->update(UserProfile::tableName(),
                [
                    'validato_almeno_una_volta' => 0,
                    'status' => UserProfile::USERPROFILE_WORKFLOW_STATUS_DRAFT,
                ],
                [
                    'user_id' => $adminUser->id
                ]);
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', 'Utente admin aggiornato correttamente'));
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }
}
