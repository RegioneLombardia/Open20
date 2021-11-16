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

/**
* @var yii\web\View $this
* @var open20\amos\upload\models\FilemanagerMediafile $model
*/

$this->title = Yii::t('amosupload', 'Update {modelClass}: ', [
    'modelClass' => 'Filemanager Mediafile',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('amosupload', 'Filemanager Mediafiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amosupload', 'Update');
?>
<div class="filemanager-mediafile-update">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
