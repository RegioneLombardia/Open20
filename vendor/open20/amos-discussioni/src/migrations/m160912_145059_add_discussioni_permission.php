<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigration;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\rules\DeleteFacilitatorOwnDiscussioniRule;
use open20\amos\discussioni\rules\DeleteOwnDiscussioniRule;
use open20\amos\discussioni\rules\UpdateFacilitatorOwnDiscussioniRule;
use open20\amos\discussioni\rules\UpdateOwnDiscussioniCommentiRule;
use open20\amos\discussioni\rules\UpdateOwnDiscussioniRule;
use open20\amos\discussioni\rules\UpdateOwnDiscussioniRisposteRule;
use open20\amos\discussioni\rules\DeleteOwnDiscussioniCommentiRule;
use open20\amos\discussioni\rules\DeleteOwnDiscussioniRisposteRule;
use yii\rbac\Permission;

class m160912_145059_add_discussioni_permission extends AmosMigration
{

    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {        
        return $this->addAuthorizations();
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('amos_widgets', true) !== null) {
        $this->delete('amos_widgets', ['module' => 'discussioni']);
        }
        // If you want to remove permissions and roles. If you don't need this delete the code below.
        return $this->removeAuthorizations();
    }

    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = array_merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setDiscussioniTopicAllegatiModelPermissions(),
            $this->setDiscussioniCommentiModelPermissions(),
            $this->setDiscussioniRisposteModelPermissions(),
            $this->setWorkflowStatusPermissions(),
            $this->setWidgetsPermissions()
        );
    }

    /**
     * News plugin roles.
     *
     * @return array
     */
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'CREATORE_DISCUSSIONI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di creatore di discussioni',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'VALIDATORE_DISCUSSIONI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di validatore di discussioni',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'LETTORE_DISCUSSIONI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di lettore di discussioni',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'AMMINISTRATORE_DISCUSSIONI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di amministratore discussioni',
                'ruleName' => null
            ],
            [
                'name' => 'FACILITATORE_DISCUSSIONI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Facilitatore discussioni',
                'ruleName' => null,
            ]
        ];
    }

    /**
     * Model permissions
     *
     * @return array
     */
    private function setModelPermissions()
    {
        return [
            [
                'name' => 'DISCUSSIONITOPIC_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DiscussioniTopic',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONITOPIC_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DiscussioniTopic',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'LETTORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONITOPIC_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DiscussioniTopic',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', DeleteOwnDiscussioniRule::className(), DeleteFacilitatorOwnDiscussioniRule::className()]
            ],
            [
                'name' => 'DISCUSSIONITOPIC_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DiscussioniTopic',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', UpdateOwnDiscussioniRule::className(), UpdateFacilitatorOwnDiscussioniRule::className()]
            ],
            [
                'name' => UpdateOwnDiscussioniRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica di una propria discussione',
                'ruleName' => UpdateOwnDiscussioniRule::className(),
                'parent' => ['CREATORE_DISCUSSIONI']
            ],
            [
                'name' => UpdateOwnDiscussioniCommentiRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica di una proprio commento discussione',
                'ruleName' => UpdateOwnDiscussioniCommentiRule::className(),
                'parent' => ['LETTORE_DISCUSSIONI']
            ],
            [
                'name' => UpdateOwnDiscussioniRisposteRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica di una proprio commento discussione',
                'ruleName' => UpdateOwnDiscussioniRisposteRule::className(),
                'parent' => ['LETTORE_DISCUSSIONI']
            ],
            [
                'name' => DeleteOwnDiscussioniCommentiRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di eliminazione di una proprio commento discussione',
                'ruleName' => DeleteOwnDiscussioniCommentiRule::className(),
                'parent' => ['LETTORE_DISCUSSIONI']
            ],
            [
                'name' => DeleteOwnDiscussioniRisposteRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di eliminazione di una proprio commento discussione',
                'ruleName' => DeleteOwnDiscussioniRisposteRule::className(),
                'parent' => ['LETTORE_DISCUSSIONI']
            ],
            [
                'name' => DeleteOwnDiscussioniRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellazione di una propria discussione',
                'ruleName' => DeleteOwnDiscussioniRule::className(),
                'parent' => ['CREATORE_DISCUSSIONI']
            ],
            [
                'name' => UpdateFacilitatorOwnDiscussioniRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di modifica di una discussione da parte del facilitatore del creatore',
                'ruleName' => UpdateFacilitatorOwnDiscussioniRule::className(),
                'parent' => ['FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => DeleteFacilitatorOwnDiscussioniRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di cancellazione di una discussione da parte del facilitatore del creatore',
                'ruleName' => DeleteFacilitatorOwnDiscussioniRule::className(),
                'parent' => ['FACILITATORE_DISCUSSIONI']
            ]
        ];
    }

    /**
     * News attachments model permissions
     *
     * @return array
     */
    private function setDiscussioniTopicAllegatiModelPermissions()
    {
        return [
            [
                'name' => 'DISCUSSIONIALLEGATI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ]
        ];
    }

    /**
     * DiscussioniCommenti model permissions
     *
     * @return array
     */
    private function setDiscussioniCommentiModelPermissions()
    {
        return [
            [
                'name' => 'DISCUSSIONICOMMENTI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DiscussioniCommenti',
                'ruleName' => null,
                'parent' => ['LETTORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONICOMMENTI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DiscussioniCommenti',
                'ruleName' => null,
                'parent' => ['LETTORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONICOMMENTI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DiscussioniCommenti',
                'ruleName' => null,
                'parent' => [DeleteOwnDiscussioniCommentiRule::className()]
            ],
            [
                'name' => 'DISCUSSIONICOMMENTI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DiscussioniCommenti',
                'ruleName' => null,
                'parent' => [UpdateOwnDiscussioniCommentiRule::className()]
            ],
        ];
    }

    /**
     * DiscussioniRisposte model permissions
     *
     * @return array
     */
    private function setDiscussioniRisposteModelPermissions()
    {
        return [
            [
                'name' => 'DISCUSSIONIRISPOSTE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DiscussioniRisposte',
                'ruleName' => null,
                'parent' => ['LETTORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIRISPOSTE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DiscussioniRisposte',
                'ruleName' => null,
                'parent' => ['LETTORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIRISPOSTE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DiscussioniRisposte',
                'ruleName' => null,
                'parent' => [DeleteOwnDiscussioniRisposteRule::className()]
            ],
            [
                'name' => 'DISCUSSIONIRISPOSTE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DiscussioniRisposte',
                'ruleName' => null,
                'parent' => [UpdateOwnDiscussioniRisposteRule::className()]
            ],
        ];
    }

    /**
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso WorkFlow sul DiscussioniTopic BOZZA',
                'ruleName' => null,
                'parent' => ['CREATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso WorkFlow sul DiscussioniTopic DAVALIDARE',
                'ruleName' => null,
                'parent' => ['CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso WorkFlow sul DiscussioniTopic ATTIVA',
                'ruleName' => null,
                'parent' => ['VALIDATORE_DISCUSSIONI', 'LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DISATTIVA,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso WorkFlow sul DiscussioniTopic DISATTIVA',
                'ruleName' => null,
                'parent' => ['VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ]
        ];
    }

    /**
     * Plugin widgets permissions
     *
     * @return array
     */
    private function setWidgetsPermissions()
    {
        return [
            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicCreatedBy::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget delle discussioni create da se stesso',
                'ruleName' => null,
                'parent' => ['CREATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget delle discussioni da validare',
                'ruleName' => null,
                'parent' => ['VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget delle discussioni validate',
                'ruleName' => null,
                'parent' => ['LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget della dashboard interna delle discussioni',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget della dashboard interna delle discussioni',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget grafico delle ultime discussioni',
                'ruleName' => null,
                'parent' => ['LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => open20\amos\discussioni\widgets\graphics\WidgetGraphicsDiscussioniInEvidenza::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget grafico delle discussioni in evidenza',
                'ruleName' => null,
                'parent' => ['LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ]
        ];
    }
}
