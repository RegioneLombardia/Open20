<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use yii\db\Migration;

/**
 * Class m171201_115022_add_partnership_profiles_field_community_id
 */
class m171201_115022_add_partnership_profiles_field_community_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(PartnershipProfiles::tableName(), 'community_id', $this->integer()->null()->after('intellectual_property_id')->comment('Community ID'));
        MigrationCommon::printConsoleMessage('Added partnership profiles field: community_id');
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(PartnershipProfiles::tableName(), 'community_id');
        MigrationCommon::printConsoleMessage('Removed partnership profiles field: community_id');
        return true;
    }
}
