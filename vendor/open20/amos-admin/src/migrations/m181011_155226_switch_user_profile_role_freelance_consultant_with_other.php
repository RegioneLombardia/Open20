<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileRole;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;

/**
 * Class m181011_155226_switch_user_profile_role_freelance_consultant_with_other
 */
class m181011_155226_switch_user_profile_role_freelance_consultant_with_other extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = UserProfileRole::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $firstElement = UserProfileRole::findOne(1);
        $otherElement = UserProfileRole::findOne(7);
        $ok = $this->switchTableElements($firstElement, $otherElement);
        return $ok;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $firstElement = UserProfileRole::findOne(7);
        $otherElement = UserProfileRole::findOne(1);
        $ok = $this->switchTableElements($firstElement, $otherElement);
        return $ok;
    }

    /**
     * @param UserProfileRole $firstElement
     * @param UserProfileRole $otherElement
     * @return bool
     */
    private function switchTableElements($firstElement, $otherElement)
    {
        if ($this->db->driverName === 'mysql') {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        }

        try {
            $this->delete($this->tableName, ['id' => $firstElement->id]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore cancellazione primo elemento con id ' . $firstElement->id);
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        MigrationCommon::printConsoleMessage('Cancellato primo elemento');

        try {
            $this->delete($this->tableName, ['id' => $otherElement->id]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore cancellazione altro elemento con id ' . $otherElement->id);
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        MigrationCommon::printConsoleMessage('Cancellato altro elemento');

        try {
            $otherElementToFirstElementValues = $otherElement->attributes;
            $otherElementToFirstElementValues['id'] = $firstElement->id;
            $this->insert($this->tableName, $otherElementToFirstElementValues);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore inserimento altro elemento al posto del primo');
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        MigrationCommon::printConsoleMessage('Inserito altro elemento al posto del primo');

        try {
            $firstElementToOtherElementValues = $firstElement->attributes;
            $firstElementToOtherElementValues['id'] = $otherElement->id;
            $this->insert($this->tableName, $firstElementToOtherElementValues);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore inserimento primo elemento al posto di altro');
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        MigrationCommon::printConsoleMessage('Inserito primo elemento al posto di altro');

        if ($this->db->driverName === 'mysql') {
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        }

        return true;
    }
}
