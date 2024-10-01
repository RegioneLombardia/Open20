<?php

namespace open20\amos\audit\panels;

use open20\amos\audit\components\panels\DataStoragePanelTrait;

use Yii;

/**
 * ConfigPanel
 * @package open20\amos\audit\panels
 */
class ConfigPanel extends \yii\debug\panels\ConfigPanel
{
    use DataStoragePanelTrait;

    /**
     * @return string
     */
    public function getDetail()
    {
        return Yii::$app->view->render('@yii/debug/views/default/panels/config/detail', [
            'panel' => $this,
        ]);
    }

}