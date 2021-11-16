<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */
/**
* @var yii\web\View $this
* @var backend\modules\userimporter\model\UserImportTask $model
*/
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'User Import Task',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/userimporter']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'User Import Task'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-import-task-create">
<div class="my-4 d-flex">
        <div class="mr-2">
            <svg class="icon">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_person_add"></use>
            </svg>
        </div>

        <h5 class="font-weight-bold ">
            Importa utenti da file esterno
        </h5>
    </div>

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
