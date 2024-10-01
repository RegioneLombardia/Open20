<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\videoconference\assets\VideoconferenceAsset;

/**
* @var yii\web\View $this
* @var open20\amos\videoconference\models\Videoconf $model
*/


VideoconferenceAsset::register($this);

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Videoconferenza'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="videoconf-view col-xs-12">
    <span id="videoconf-id" style="display:none"><?=$model->id?></span>
  <div id="meet" style="height: 600px;"></div>

    <div class="btnViewContainer">
        <?= Html::a(Yii::t('amoscore', 'Esci'), ['/videoconference/videoconf/index'], ['class' => 'btn btn-primary','id' => "meeting-end"]); ?>    </div>

</div>
