<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\community\models\CommunityType;

/**
 * Class m170509_154311_update_community_types_name_and_description
 */
class m170509_154311_update_community_types_name_and_description extends \yii\db\Migration
{

    const COMMUNITY_TYPES_TABLE = 'community_types';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $rowsToUpdate = Array(
            ['id' => CommunityType::COMMUNITY_TYPE_OPEN, 'name' => 'Open', 'description' => 'An open community is visible to everyone and it is possible to join it without sending any authorization request'],
            ['id' => CommunityType::COMMUNITY_TYPE_PRIVATE, 'name' => 'Reserved', 'description' => 'A private community is visible to everyone but it is possible to sign into it only after the subscription request acceptance of the Community Manager'],
            ['id' => CommunityType::COMMUNITY_TYPE_CLOSED, 'name' => 'Limited to members', 'description' => 'A community limited to members is not visible to everyone and it is possible to sign into it only on Community Manager invitation, when the invitation is accepted by the user']
        );

        foreach ($rowsToUpdate as $row){
            $communityType = CommunityType::findOne($row['id']);
            $communityType->name = $row['name'];
            $communityType->description = $row['description'];
            $communityType->detachBehaviors();
            $communityType->save(false);
        }
        return true;
    }

    public function safeDown()
    {
        $rowsToUpdate = Array(
            ['id' => CommunityType::COMMUNITY_TYPE_OPEN, 'name' => 'Aperta', 'description' => 'Una community aperta è visibile a tutti e vi ci si può iscrivere liberamente senza richieste di autorizzazione.'],
            ['id' => CommunityType::COMMUNITY_TYPE_PRIVATE, 'name' => 'Riservata', 'description' => 'Una community riservata è visibile a tutti ma vi ci si può accedere solo in seguito all\'acettazione della richiesta di iscrizione da parte del Community Manager.'],
            ['id' => CommunityType::COMMUNITY_TYPE_CLOSED, 'name' => 'Chiusa', 'description' => 'Una community chiusa non è visibile a tutti e vi ci si può accedere solo su invito del Community Manager quando l\'invito viene accettato dall\'utente).']
        );

        foreach ($rowsToUpdate as $row){
            $communityType = CommunityType::findOne($row['id']);
            $communityType->name = $row['name'];
            $communityType->description = $row['description'];
            $communityType->detachBehaviors();
            $communityType->save(false);
        }
        return true;
    }
}
