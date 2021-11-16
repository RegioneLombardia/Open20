<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\ActiveQuery;
use yii\db\Migration;

/**
 * Class m191128_122304_auto_add_community_managers_to_configured_roles
 */
class m191128_122304_auto_add_community_managers_to_configured_roles extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Time limit to 8 hours
        set_time_limit(8 * 60 * 60);
        ini_set('memory_limit', '4096M');
        
        $className = 'm191128_122304_auto_add_community_managers_to_configured_roles';
        $communityModule = AmosCommunity::instance();
        if (!is_null($communityModule)) {
            $autoCommunityManagerRoles = $communityModule->autoCommunityManagerRoles;
            if (is_array($autoCommunityManagerRoles)) {
                if (!empty($autoCommunityManagerRoles)) {
                    /** @var ActiveQuery $query */
                    $query = Community::find();
                    $allCommunities = $query->all();
                    foreach ($allCommunities as $community) {
                        /** @var Community $community */
                        $ok = CommunityUtil::autoAddCommunityManagersToCommunity($community, CommunityUserMm::STATUS_ACTIVE, $communityModule);
                        if (!$ok) {
                            MigrationCommon::printConsoleMessage('Errore aggiunta dei community manager alla community con ID "' . $community->id . '"');;
                        }
                    }
                } else {
                    MigrationCommon::printConsoleMessage($className . ': autoCommunityManagerRoles vuoto.');
                }
            } else {
                MigrationCommon::printConsoleMessage($className . ': autoCommunityManagerRoles non è un array.');
            }
        } else {
            MigrationCommon::printConsoleMessage($className . ': modulo community non configurato. Nulla da fare.');
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m191128_122304_auto_add_community_managers_to_configured_roles cannot be reverted.\n";
        return false;
    }
}
