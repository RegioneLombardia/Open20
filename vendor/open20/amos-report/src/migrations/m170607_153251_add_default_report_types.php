<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m170607_153251_add_default_report_types
 */
class m170607_153251_add_default_report_types extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $reportType = new \open20\amos\report\models\ReportType();
        $reportType->name = "Inappropriate contents";
        $reportType->description = "Inappropriate contents";
        $ok = $reportType->detachBehaviors();
        $ok = $reportType->save(false);

        if(!$ok){
            echo "Error occurred while saving report type '$reportType->name'";
            return false;
        }

        $reportType = new \open20\amos\report\models\ReportType();
        $reportType->name = "Errors";
        $reportType->description = "Errors";
        $ok = $reportType->detachBehaviors();
        $ok = $reportType->save(false);

        if(!$ok){
            echo "Error occurred while saving report type '$reportType->name'";
            return false;
        }

        return true;

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m170607_153251_add_default_report_types cannot be reverted.\n";

        return true;
    }
}
