<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo;
use open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloAll;

/**
 * Class m210402_160246_fix_organizzazioni_widgets
 */
class m210402_160246_fix_organizzazioni_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'organizzazioni';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconProfilo::className(),
                'update' => true,
                'child_of' => null,
            ],
            [
                'old_classname' => 'open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloDashboard',
                'classname' => WidgetIconProfiloAll::className(),
                'dashboard_visible' => 0,
                'child_of' => WidgetIconProfilo::className(),
            ],
            [
                'classname' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloToValidate::className(),
                'update' => true,
                'child_of' => WidgetIconProfilo::className(),
            ],
        ];
    }
}
