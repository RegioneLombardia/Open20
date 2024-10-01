<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\migrations
 */
use yii\db\Migration;

/**
 * 
 */
class m221206_132924_add_new_value_to_collaboration_partnership_type
    extends Migration
{
    /**
     * 
     */
    const TABLE_COLLABORATION_PARTNERSHIP_TYPE = '{{%collaboration_partnership_type}}';

    /**
     * 
     */
    public function safeUp()
    {
        $this->insert(
            self::TABLE_COLLABORATION_PARTNERSHIP_TYPE,
            [
                'id' => 4,
                'name' => 'Accordo Professionale',
            ]
        );
    }

    /**
     * 
     * @return boolean
     */
    public function safeDown()
    {
        $this->delete(
            self::TABLE_COLLABORATION_PARTNERSHIP_TYPE,
            'id = 4'
        );
    }

}