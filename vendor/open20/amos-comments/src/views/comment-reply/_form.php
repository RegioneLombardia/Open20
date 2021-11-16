<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\views\comment-reply
 * @category   CategoryName
 */

use open20\amos\comments\AmosComments;
use open20\amos\comments\assets\CommentsAsset;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\AttachmentsWidget;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;

CommentsAsset::register($this);

/**
 * @var \yii\web\View $this
 * @var \open20\amos\comments\models\CommentReply $model
 * @var \yii\widgets\ActiveForm $form
 * @var string $fid
 */

/** @var AmosComments $commentsModule */
$commentsModule = Yii::$app->getModule(AmosComments::getModuleName());

?>

<div class="comment-reply-form col-xs-12 nop">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'comment_reply_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : ''),
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-lg-8 col-xs-12">
            <?= $form->field($model, 'comment_reply_text')->widget(TextEditorWidget::className(), [
                'clientOptions' => [
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
        <div class="col-lg-4 col-xs-12">
            <?= AttachmentsWidget::widget([
                'form' => $form,
                'model' => $model,
                'modelField' => 'commentReplyAttachments',
                'attachInputOptions' => [
                    'multiple' => true
                ],
                'attachInputPluginOptions' => [
                    'maxFileCount' => $commentsModule->maxCommentAttachments,
                    'showPreview' => false
                ],
            ]) ?>
        </div>
    </div>

    <?php if ($commentsModule->enableUserSendMailCheckbox && (Yii::$app->controller->action->id == 'create')): ?>
        <div class="row">
            <div class="col-xs-12">
                <?= Html::checkbox('send-reply-notify-mail', true, ['label' => ' ' . AmosComments::t('amoscomments', '#checkbox_send_notify')]) ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="clearfix"></div>

    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
