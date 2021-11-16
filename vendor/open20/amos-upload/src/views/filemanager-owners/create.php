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

$this->title = Yii::t('amosupload', 'Create {modelClass}', [
    'modelClass' => 'Filemanager Owners',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('amosupload', 'Filemanager Owners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filemanager-owners-create">
    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
