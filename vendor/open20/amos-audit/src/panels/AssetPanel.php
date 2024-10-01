<?php

namespace open20\amos\audit\panels;

use open20\amos\audit\components\panels\DataStoragePanelTrait;

use Yii;

/**
 * AssetPanel
 * @package open20\amos\audit\panels
 */
class AssetPanel extends \yii\debug\panels\AssetPanel
{
    use DataStoragePanelTrait;

    /**
     * @return string
     */
    public function getDetail()
    {
        return Yii::$app->view->render('@yii/debug/views/default/panels/assets/detail', [
            'panel' => $this,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        if (\Yii::$app->request instanceof \yii\web\Request) {
            return parent::save();
        }
        return null;
    }

}