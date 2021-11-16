<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\admin\models\UserProfile;

/**
 * Class m180628_125015_logout_default
 */
class m180628_125015_logout_default extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update(
            UserProfile::tableName(),
            ['ultimo_logout' => new \yii\db\Expression('ultimo_accesso')],
            ['is', 'ultimo_logout', null]
        );

        $this->alterColumn(
            UserProfile::tableName(),
            'ultimo_logout',
            $this->dateTime()->defaultValue(new \yii\db\Expression('now()'))->comment('Ultimo logout')
        );

        $this->update(
            UserProfile::tableName(),
            ['ultimo_logout' => new \yii\db\Expression('now()')],
            new \yii\db\Expression('CAST(ultimo_logout AS CHAR(20)) = \'0000-00-00 00:00:00\'')
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

}