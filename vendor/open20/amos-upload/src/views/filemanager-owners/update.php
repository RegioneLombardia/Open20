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
* @var open20\amos\upload\models\FilemanagerOwners $model
*/

$this->title = Yii::t('amosupload', 'Update {modelClass}: ', [
    'modelClass' => 'Filemanager Owners',
]) . ' ' . $model->mediafile_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('amosupload', 'Filemanager Owners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mediafile_id, 'url' => ['view', 'mediafile_id' => $model->mediafile_id, 'owner_id' => $model->owner_id, 'owner' => $model->owner, 'owner_attribute' => $model->owner_attribute]];
$this->params['breadcrumbs'][] = Yii::t('amosupload', 'Update');
?>
<div class="filemanager-owners-update">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
