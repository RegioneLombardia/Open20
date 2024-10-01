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

class m160905_081752_update_permission_cwh extends Migration
{
    const TABLE_PERMISSION = '{{%auth_item}}';

    public function up()
    {
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHAUTHASSIGNMENTSEARCH_CREATE',
            'type' => '2',
            'description' => 'Permesso di CREATE sul model CwhAuthAssignment'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHAUTHASSIGNMENTSEARCH_DELETE',
            'type' => '2',
            'description' => 'Permesso di DELETE sul model CwhAuthAssignment'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHAUTHASSIGNMENTSEARCH_READ',
            'type' => '2',
            'description' => 'Permesso di READ sul model CwhAuthAssignment'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHAUTHASSIGNMENTSEARCH_UPDATE',
            'type' => '2',
            'description' => 'Permesso di UPDATE sul model CwhAuthAssignment'
        ]);

        return true;
    }


}
