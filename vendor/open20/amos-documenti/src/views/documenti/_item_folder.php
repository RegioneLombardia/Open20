<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

$modelViewUrl = [Yii::$app->controller->action->id, 'parentId' => $model->id];

?>
<div class="listview-container folder">
    <div class="post-horizontal">
        <div class="post-content col-xs-12 nop">
            <div class="post-title col-xs-12">

            <?php
            if($model->drive_file_id) {
                 echo AmosIcons::show('folder-open', [], 'dash') . AmosIcons::show('google-drive', ['class' => 'google-sync'], 'am');
            } else {
                echo AmosIcons::show('folder-open', [], 'dash');
            }
            ?>
                <?= Html::a(Html::tag('h2', htmlspecialchars($model->titolo)), $modelViewUrl, ['title' => $model->titolo]) ?>
            </div>
            <?= NewsWidget::widget([
                'model' => $model,
            ]); ?>
        </div>
        <?= ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => $model->getFullUpdateUrl(),
            'actionDelete' => $model->getFullDeleteUrl(),
            'modelValidatePermission' => 'DocumentValidate',
            'labelDeleteConfirm' => AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder')
//                    'mainDivClasses' => 'col-xs-1 nop'
        ]) ?>
    </div>
</div>
