<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\news\models\News;
use open20\amos\news\rules\DeleteFacilitatorOwnNewsRule;
use open20\amos\news\rules\DeleteOwnNewsRule;
use open20\amos\news\rules\UpdateFacilitatorOwnNewsRule;
use open20\amos\news\rules\UpdateOwnNewsRule;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m160912_144417_add_news_permissions_roles
 */
class m160912_144417_add_news_permissions_roles extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setNewsCategorieModelPermissions(),
            $this->setNewsAllegatiModelPermissions(),
            $this->setWorkflowStatusPermissions(),
            $this->setWidgetsPermissions()
        );
    }
    
    /**
     * Plugin roles.
     *
     * @return array
     */
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_NEWS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore news',
            ],
            [
                'name' => 'CREATORE_NEWS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Creatore news',
                'parent' => ['AMMINISTRATORE_NEWS']
            ],
            [
                'name' => 'VALIDATORE_NEWS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validatore news',
                'parent' => ['AMMINISTRATORE_NEWS']
            ],
            [
                'name' => 'LETTORE_NEWS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Lettore news',
                'parent' => ['AMMINISTRATORE_NEWS']
            ],
            [
                'name' => 'AMMINISTRATORE_CATEGORIE_NEWS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore categorie news',
                'parent' => ['AMMINISTRATORE_NEWS']
            ],
            [
                'name' => 'FACILITATORE_NEWS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Facilitatore news',
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
                'name' => 'NEWS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model News',
                'parent' => ['AMMINISTRATORE_NEWS', 'CREATORE_NEWS']
            ],
            [
                'name' => 'NEWS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model News',
                'parent' => ['AMMINISTRATORE_NEWS', 'CREATORE_NEWS', 'VALIDATORE_NEWS', 'LETTORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => 'NEWS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model News',
                'parent' => ['AMMINISTRATORE_NEWS', DeleteOwnNewsRule::className(), DeleteFacilitatorOwnNewsRule::className()]
            ],
            [
                'name' => 'NEWS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model News',
                'parent' => ['AMMINISTRATORE_NEWS', 'VALIDATORE_NEWS', UpdateOwnNewsRule::className(), UpdateFacilitatorOwnNewsRule::className()]
            ],
            [
                'name' => UpdateOwnNewsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di modifica di una propria notizia',
                'ruleName' => UpdateOwnNewsRule::className(),
                'parent' => ['CREATORE_NEWS']
            ],
            [
                'name' => DeleteOwnNewsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di cancellazione di una propria notizia',
                'ruleName' => DeleteOwnNewsRule::className(),
                'parent' => ['CREATORE_NEWS']
            ],
            [
                'name' => UpdateFacilitatorOwnNewsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di modifica di una notizia da parte del facilitatore del creatore',
                'ruleName' => UpdateFacilitatorOwnNewsRule::className(),
                'parent' => ['FACILITATORE_NEWS']
            ],
            [
                'name' => DeleteFacilitatorOwnNewsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permessso di cancellazione di una notizia da parte del facilitatore del creatore',
                'ruleName' => DeleteFacilitatorOwnNewsRule::className(),
                'parent' => ['FACILITATORE_NEWS']
            ]
        ];
    }
    
    /**
     * News categories model permissions
     *
     * @return array
     */
    private function setNewsCategorieModelPermissions()
    {
        return [
            [
                'name' => 'NEWSCATEGORIE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsCategorie',
                'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
            ],
            [
                'name' => 'NEWSCATEGORIE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsCategorie',
                'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
            ],
            [
                'name' => 'NEWSCATEGORIE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsCategorie',
                'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
            ],
            [
                'name' => 'NEWSCATEGORIE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsCategorie',
                'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
            ]
        ];
    }
    
    /**
     * News attachments model permissions
     *
     * @return array
     */
    private function setNewsAllegatiModelPermissions()
    {
        return [
            [
                'name' => 'NEWSALLEGATI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsAllegati',
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => 'NEWSALLEGATI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsAllegati',
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => 'NEWSALLEGATI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsAllegati',
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => 'NEWSALLEGATI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsAllegati',
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ]
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
                'name' => News::NEWS_WORKFLOW_STATUS_BOZZA,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow news stato bozza',
                'parent' => ['CREATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => News::NEWS_WORKFLOW_STATUS_DAVALIDARE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow news stato da validare',
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow news stato validato',
                'parent' => ['VALIDATORE_NEWS', 'LETTORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => News::NEWS_WORKFLOW_STATUS_NONVALIDATO,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow news stato non validato',
                'parent' => ['VALIDATORE_NEWS', 'FACILITATORE_NEWS']
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
                'name' => open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconNewsDashboard',
                'parent' => ['AMMINISTRATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => open20\amos\news\widgets\icons\WidgetIconNews::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconNews',
                'parent' => ['LETTORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetGraphicsUltimeNews',
                'parent' => ['LETTORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => open20\amos\news\widgets\icons\WidgetIconNewsCategorie::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconNewsCategorie',
                'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
            ],
            [
                'name' => open20\amos\news\widgets\icons\WidgetIconNewsCreatedBy::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconNewsCreatedBy',
                'parent' => ['CREATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => open20\amos\news\widgets\icons\WidgetIconNewsDaValidare::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconNewsDaValidare',
                'parent' => ['VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ]
        ];
    }
}
