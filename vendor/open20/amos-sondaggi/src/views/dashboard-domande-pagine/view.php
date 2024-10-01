<?php

use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use kartik\detail\GridView;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomandePagine $model
 */
ModuleSondaggiAsset::register($this);

$this->title = $model->getTitle();
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Pagine dei sondaggi'), 'url' => ['sondaggi/sondaggi/manage']];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-dashboard">
    <div class="row">
        <?= GridView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
                'sondaggi_id',
                'titolo',
                'descrizione:ntext',
                'filemanager_mediafile_id',
                /*[
                    'attribute'=>'created_at',
                    'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                ],
                [
                    'attribute'=>'updated_at',
                    'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                ],
                [
                    'attribute'=>'deleted_at',
                    'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                ],
                'created_by',
                'updated_by',
                'deleted_by',
                'version',*/
            ],
        ]) ?>
    </div>
</div>
