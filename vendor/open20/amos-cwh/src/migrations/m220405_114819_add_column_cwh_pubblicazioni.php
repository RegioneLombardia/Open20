<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\migrations
 * @category   CategoryName
 */

use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m171120_094019_alter_cwh_tables
 */
class m220405_114819_add_column_cwh_pubblicazioni extends Migration
{


    const PUBBLICAZIONI = 'cwh_pubblicazioni';


    public function safeUp()
    {
        $this->addColumn(self::PUBBLICAZIONI, 'ignore_notify_editorial_staff', $this->integer(1)->defaultValue(0)->after('cwh_regole_pubblicazione_id'));

    }

    public function safeDown()
    {
        $this->dropColumn(self::PUBBLICAZIONI, 'ignore_notify_editorial_staff');

    }

}
