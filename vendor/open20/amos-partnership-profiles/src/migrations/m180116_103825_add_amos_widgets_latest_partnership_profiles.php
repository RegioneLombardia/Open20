<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180116_103825_add_amos_widgets_latest_partnership_profiles*/
class m180116_103825_add_amos_widgets_latest_partnership_profiles extends AmosMigrationWidgets
{
    const MODULE_NAME = 'partnershipprofiles';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\graphics\WidgetGraphicsLatestPartnershipProfiles::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC ,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 10
            ],
        ];
    }
}
