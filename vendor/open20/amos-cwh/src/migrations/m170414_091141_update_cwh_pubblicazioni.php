<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m170414_091141_update_cwh_pubblicazioni
 */
class m170414_091141_update_cwh_pubblicazioni extends Migration
{
    const TABLE = '{{%cwh_regole_pubblicazione}}';

    public function safeUp()
    {
        $this->update(
            self::TABLE,
            [
                'nome' => 'Tutti gli utenti',
            ],
            [
                'id' => 1
            ]
        );
        $this->update(
            self::TABLE,
            [
                'nome' => 'Tutti gli utenti con specifici "tag aree di interesse"',
            ],
            [
                'id' => 2
            ]
        );
        $this->update(
            self::TABLE,
            [
                'nome' => 'Tutti gli utenti in determinati ambiti',
            ],
            [
                'id' => 3
            ]
        );
        $this->update(
            self::TABLE,
            [
                'nome' => 'Tutti gli utenti in determinati ambiti e con specifici "tag aree di interesse"',
            ],
            [
                'id' => 4
            ]
        );
    }

    public function safeDown()
    {
        echo "Down() non previsto per il file m170414_091141_update_cwh_pubblicazioni";
        return true;
    }
}
