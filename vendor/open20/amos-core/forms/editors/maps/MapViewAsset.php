<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\core\forms\editors\maps;

use Yii;
use yii\web\AssetBundle;

/**
 * Class MapViewAsset
 */
class MapViewAsset extends AssetBundle
{
    /**
     * @var string $sourcePath
     */
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';

    /**
     * @var string[] $css
     */
    public $css = [
        'less/map.less',
    ];

    /**
     * @var string[] $js
     */
    public $js = [
        'js/markers.js',
        'js/markerclusterer.js',
    ];

    /**
     * @var array $depends
     */
    public $depends = [];

    /**
     * @return void
     */
    public function init()
    {
        $moduleLayout = \Yii::$app->getModule('layout');

        if (!empty($moduleLayout)) {
            $this->depends[] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends[] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }

        $gpJsLink = 'https://maps.google.com/maps/api/js?' . http_build_query(array(
                'key' => Yii::$app->params['google-maps']['key'],
                'language' => Yii::$app->language,
            ));

        $this->js[] = $gpJsLink;

        parent::init();
    }
}