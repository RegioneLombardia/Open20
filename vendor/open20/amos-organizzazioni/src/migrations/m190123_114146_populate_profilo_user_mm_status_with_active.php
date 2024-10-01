<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use open20\amos\organizzazioni\Module;
use yii\db\Migration;

/**
 * Class m190123_114146_populate_profilo_user_mm_status_with_active
 */
class m190123_114146_populate_profilo_user_mm_status_with_active extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        try {
            /** @var ProfiloUserMm $profiloUserMm */
            $profiloUserMm = Module::instance()->createModel('ProfiloUserMm');
            $this->update($profiloUserMm::tableName(), ['status' => ProfiloUserMm::STATUS_ACTIVE]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        try {
            /** @var ProfiloUserMm $profiloUserMm */
            $profiloUserMm = Module::instance()->createModel('ProfiloUserMm');
            $this->update($profiloUserMm::tableName(), ['status' => null]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        return true;
    }
}
