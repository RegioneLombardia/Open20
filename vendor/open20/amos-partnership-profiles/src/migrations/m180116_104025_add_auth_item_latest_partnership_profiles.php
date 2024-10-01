<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180116_103825_add_auth_item_latest_partnership_profiles*/
class m180116_104025_add_auth_item_latest_partnership_profiles extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
                [
                    'name' =>  \open20\amos\partnershipprofiles\widgets\graphics\WidgetGraphicsLatestPartnershipProfiles::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => $prefixStr . 'WidgetGraphicsLatestPartnershipProfiles',
                    'ruleName' => null,
                    'parent' => ['ADMIN','VALIDATED_BASIC_USER']
                ]

            ];
    }
}
