<?php

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

class m170621_145752_remove_widget_WidgetGraphicsDiscussioniInEvidenza extends AmosMigrationWidgets
{
    const MODULE_NAME = 'discussioni';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = array_merge(
            $this->initIconWidgetsConf(),
            $this->initGraphicWidgetsConf()
        );
    }

    /**
     * Init the icon widgets configurations
     * @return array
     */
    private function initIconWidgetsConf()
    {
        return [

        ];
    }

    /**
     * Init the graphic widgets configurations
     * @return array
     */
    private function initGraphicWidgetsConf()
    {
        return [
            [
                'classname' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsDiscussioniInEvidenza::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_DISABLED,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'default_order' => 1,
                'update' => true
            ],
        ];
    }
}
