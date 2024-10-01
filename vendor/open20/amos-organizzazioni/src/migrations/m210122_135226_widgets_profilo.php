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

/**
 * Class m210122_135226_widgets_profilo
 */
class m210122_135226_widgets_profilo extends AmosMigrationWidgets
{
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconProfilo::className(),
                'update' => true,
                'child_of' => 'open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloDashboard',
            ]
        ];
    }
}
