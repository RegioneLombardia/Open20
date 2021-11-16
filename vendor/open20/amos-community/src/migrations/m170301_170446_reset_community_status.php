<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\community\models\Community;

/**
 * Class m170301_170446_reset_community_status
 *
 * if present in db any status not null from those defined in community workflow reset status to Draft
 */
class m170301_170446_reset_community_status extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update(Community::tableName(), ['status' => Community::COMMUNITY_WORKFLOW_STATUS_DRAFT] , 'status is not null' );
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "No need of safe down method implementation";
        return true;
    }
}
