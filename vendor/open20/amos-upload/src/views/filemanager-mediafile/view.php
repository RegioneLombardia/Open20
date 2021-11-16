<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\upload
 * @category   CategoryName
 */

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var open20\amos\upload\models\FilemanagerMediafile $model
*/

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('amosupload', 'Filemanager Mediafiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filemanager-mediafile-view">

    <?= DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
    'panel'=>[
    'heading'=>$this->title,
    'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
                'id',
            'filename',
            'type',
            'url:ntext',
            'alt:ntext',
            'size',
            'description:ntext',
            'thumbs:ntext',
            'created_at',
            'updated_at',
    ],
    'deleteOptions'=>[
    'url'=>['delete', 'id' => $model->id],
    'data'=>[
    'confirm'=>Yii::t('amosupload', 'Are you sure you want to delete this item?'),
    'method'=>'post',
    ],
    ],
    'enableEditMode'=>true,
    ]) ?>

</div>
