<?php

use backend\modules\campains\models\base\PreferenceCampainSection;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\utility\CampainsChannelUtility;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\VarDumper;
use kartik\grid\GridView;
use yii\helpers\Url;
use open20\amos\layout\utility\AmosIconsBi;

// $this->title                   = Yii::t('campains', 'Nuova Campagna: destinatari e canali');
// $this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
// $this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

?>




<?= 
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'title' => [
                "label" => "Titolo",
                "value" => 'title',	
            ],
            'created_at' => [
                "label" => "Data Creazione",
                "value" => 'created_at',	
                'format' => ['date', 'php:d M Y']
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}',
                'header' => '',
                'width' => '120px',
                'dropdownOptions' => ['class' => 'float-middle'],
                'buttons' => [
                    'update' => function ($url, $model, $key)  {

                        return Html::a('Modifica', ['/landings/preference-landing/update?id='.$model->id], ['class'=>'btn btn-primary btn-sm']);
                    },
                ],
            ],
        ],
    ]) 
?>