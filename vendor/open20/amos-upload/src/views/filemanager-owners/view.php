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
* @var open20\amos\upload\models\FilemanagerOwners $model
*/

$this->title = $model->mediafile_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('amosupload', 'Filemanager Owners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filemanager-owners-view">

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
                'mediafile_id',
            'owner_id',
            'owner',
            'owner_attribute',
    ],
    'deleteOptions'=>[
    'url'=>['delete', 'id' => $model->mediafile_id],
    'data'=>[
    'confirm'=>Yii::t('amosupload', 'Are you sure you want to delete this item?'),
    'method'=>'post',
    ],
    ],
    'enableEditMode'=>true,
    ]) ?>

</div>
