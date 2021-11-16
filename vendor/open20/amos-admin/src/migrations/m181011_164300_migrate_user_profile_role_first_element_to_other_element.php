<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m181011_164300_migrate_user_profile_role_first_element_to_other_element
 */
class m181011_164300_migrate_user_profile_role_first_element_to_other_element extends Migration
{
    private $tableName;
    private $firstElementUserIds = [];
    private $otherElementUserIds = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = UserProfile::tableName();
    }

    private function findUserProfiles($userProfileRoleId)
    {
        $this->tableName = UserProfile::tableName();
        $query = new Query();
        $query->select(['id']);
        $query->from($this->tableName);
        $query->where(['user_profile_role_id' => $userProfileRoleId]);
        return $query->column();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->firstElementUserIds = $this->findUserProfiles(1);
        $this->otherElementUserIds = $this->findUserProfiles(7);
        try {
            $this->update($this->tableName, ['user_profile_role_id' => 7], ['id' => $this->firstElementUserIds]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Errore durante l'aggiornamento dei profili utente con primo elemento");
            return false;
        }
        try {
            $this->update($this->tableName, ['user_profile_role_id' => 1], ['id' => $this->otherElementUserIds]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Errore durante l'aggiornamento dei profili utente con altro elemento");
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->firstElementUserIds = $this->findUserProfiles(7);
        $this->otherElementUserIds = $this->findUserProfiles(1);
        try {
            $this->update($this->tableName, ['user_profile_role_id' => 1], ['id' => $this->firstElementUserIds]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Errore durante l'aggiornamento dei profili utente con primo elemento");
            return false;
        }
        try {
            $this->update($this->tableName, ['user_profile_role_id' => 7], ['id' => $this->otherElementUserIds]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Errore durante l'aggiornamento dei profili utente con altro elemento");
            return false;
        }
        return true;
    }
}
