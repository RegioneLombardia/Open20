<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m170613_073423_update_discussions_widgets
 */
class m170613_073423_update_discussions_widgets extends AmosMigrationWidgets
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
     * Icon widgets configurations
     * @return array
     */
    private function initIconWidgetsConf()
    {
        return [
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic::className(),
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className(),
                'update' => true
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicCreatedBy::className(),
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className(),
                'update' => true
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAll::className(),
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className(),
                'update' => true
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare::className(),
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className(),
                'update' => true
            ]
        ];
    }
    
    /**
     * Graphic widgets configurations
     * @return array
     */
    private function initGraphicWidgetsConf()
    {
        return [
            [
                'classname' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsDiscussioniInEvidenza::className(),
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className(),
                'update' => true
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni::className(),
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className(),
                'update' => true
            ]
        ];
    }
}
