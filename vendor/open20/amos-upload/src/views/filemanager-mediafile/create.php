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

$this->title = Yii::t('amosupload', 'Create {modelClass}', [
    'modelClass' => 'Filemanager Mediafile',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('amosupload', 'Filemanager Mediafiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filemanager-mediafile-create">
    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
