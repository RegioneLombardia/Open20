<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

class m150803_000002_insert_cwh_regole_pubblicazione extends \yii\db\Migration
{

    const TABLE = '{{%cwh_regole_pubblicazione}}';

    public function safeUp()
    {
        $this->insert(self::TABLE, [
            'id' => '1',
            'nome' => 'Tutti gli utenti',
            'codice' => 'PUBLIC'
        ]);
        $this->insert(self::TABLE, [
            'id' => '2',
            'nome' => 'Tutti gli utenti in base ai loro interessi',
            'codice' => 'PUBLIC_TAG'
        ]);
        $this->insert(self::TABLE, [
            'id' => '3',
            'nome' => 'Tutti utenti della rete indicata',
            'codice' => 'NETWORK'
        ]);
        $this->insert(self::TABLE, [
            'id' => '4',
            'nome' => 'Tutti gli utenti della rete indicata in base ai loro interessi',
            'codice' => 'NETWORK_TAG'
        ]);

    }

    public function safeDown()
    {
        $this->delete(self::TABLE, ['id' => '1']);
        $this->delete(self::TABLE, ['id' => '2']);
        $this->delete(self::TABLE, ['id' => '3']);
        $this->delete(self::TABLE, ['id' => '4']);
    }

}