<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\structures\assets
 * @category   CategoryName
 */

namespace open20\agid\person\assets;

use open20\amos\core\widget\WidgetAbstract;

use yii\web\AssetBundle;

/**
 * 
 */
class PersonModuleAsset extends AssetBundle
{
    /**
     * @var type
     */
    public $sourcePath = '@vendor/open20/agid-person/src/assets/web';

    /**
     * @var type
     */
    public $css = [
        'less/person.less',
    ];

    /**
     * @var type
     */
    public $js = [
    ];
    
    /**
     * 
     * @var type
     */
    public $depends = [
    ];

    /**
     * 
     */
    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if (!empty($moduleL)) {
            $this->depends[] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends[] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        
        parent::init();
    }
}