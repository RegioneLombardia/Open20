<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\invitations\assets;

use yii\web\AssetBundle;

/**
 * Class InvitationsAsset
 * @package open20\amos\invitations\assets
 */
class InvitationsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-invitations/src/assets/web';

    public $css = [
        'less/invitations.less',
    ];

    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }

}