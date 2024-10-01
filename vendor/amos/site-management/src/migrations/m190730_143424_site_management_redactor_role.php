<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m190730_143424_site_management_redactor_role extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            // ----- role
            [
                'name' => 'SITE_MANAGEMENT_EDITOR',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Redattore site mangement',
                'ruleName' => null,
            ],
            // ----- PAGE CONTENT
            [
                'name' => 'PAGECONTENT_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'PAGECONTENT_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'PAGECONTENT_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'PAGECONTENT_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            // ------- METADATA
            [
                'name' => 'METADATA_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'METADATA_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'METADATA_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'METADATA_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            //--------------- SECTION 
            [
                'name' =>  'SITEMANAGEMENTSECTION_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSECTION_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSECTION_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSECTION_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            // --------------------- SLIDERR

            [
                'name' =>  'SITEMANAGEMENTSLIDER_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDER_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDER_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDER_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],

            // ------------------ SLIDER ELEM
            [
                'name' =>  'SITEMANAGEMENTSLIDERELEM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDERELEM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDERELEM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDERELEM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            // ------------------ PERMISISON WIDGET
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementSection::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]

            ],
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementSlider::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]

            ],
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]

            ],
            // -------------- CONTAINER
            [
                'name' =>  'SITEMANAGEMENTCONTAINER_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINER_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINER_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINER_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            //----------------------

            [
                'name' =>  'SITEMANAGEMENTTEMPLATE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            // ----------------
            [
                'name' =>  'SITEMANAGEMENTELEMENT_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTELEMENT_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTELEMENT_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTELEMENT_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],

            // -------------------

            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMENTMM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMENTMM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMENTMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMENTMM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            //----------------

            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATION_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATION_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATION_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATION_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            // ----------------

            [
                'name' =>  'SITEMANAGEPUBBLICATIONTYPE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEPUBBLICATIONTYPE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEPUBBLICATIONTYPE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGEPUBBLICATIONTYPE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],

            // ---------------
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONCLASS_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONCLASS_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONCLASS_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONCLASS_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],

            // -------------

            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONUSERMM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONUSERMM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONUSERMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONUSERMM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
    
      
            //-------------------

            [
                'name' => 'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
 // read PERMISSIONS
            [
                'name' => 'SITEMANAGEMENTTEMPLATE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTFIELDSTYPE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTTEMPLATEFIELDS_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]
            ],

            // ------- widgets
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainerFather::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]

            ] ,
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingFather::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_EDITOR'],
                ]

            ],






        ];
    }
}
