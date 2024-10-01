<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/porfesr/views
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\comments\AmosComments;
use open20\amos\core\forms\ActiveForm;
use \kartik\widgets\FileInput;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\modules\porfesr\models\PorfesrBando $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = AmosComments::t('amoscomments', 'Importa termini');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/comments']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Netiquette'), 'url' => ['/comments/comment-dashboard/netiquette']];
$this->params['forceBreadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Importa termini')];
?>

<style>
    .file-caption .file-caption-name {
        border: 1px solid;
        padding: 10px;
    }
</style>
<div class="comment-blacklist-form col-xs-12 nop">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ]
    ]);
    ?>

    <div class="row">
        <div class="col-md-12">
            <?= Html::label('<small>' . AmosComments::t('amoscomments', 'File') . '</small>', 'import-file', [
                        'class' => 'control-label text-uppercase'
            ]); ?>
            <?= FileInput::widget([
                'name' => 'import-file',
                'id' => 'import-file',
                'pluginOptions' => [
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
//                    'allowedFileExtensions' => ['xlsx', 'xls'],
                ],
            ]);
            echo Html::tag('span', AmosComments::t('amoscomments', '#blacklist_import_allowed_extensions', ['extensions' => 'xls, xlsx']), [
                    'class' => 'help-block'
            ]);
            ?>
        </div>
    </div>

    <div class="row m-t-30">
        <div class="col-md-6">
            <?= \yii\helpers\Html::a(Yii::t('amoscomments', 'Annulla'), \yii\helpers\Url::previous(), [
                'class' => 'btn btn-secondary',
                'title' => Yii::t('amoscomments', 'Annulla')
            ]); ?>
        </div>
        <div class="col-md-6 text-right">
            <?= \yii\helpers\Html::submitButton(Yii::t('amoscomments', 'Importa'), [
                'class' => 'btn btn-primary',
                'name' => 'publish',
                'value' => 1,
                'title' => Yii::t('amoscomments', 'Importa')
            ]); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<div class="clearfix"></div>