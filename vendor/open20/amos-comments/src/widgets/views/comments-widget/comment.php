<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\widgets\views\comments-widget
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\comments\AmosComments;
use open20\amos\comments\assets\CommentsAsset;
use open20\amos\comments\models\Comment;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ModalUtility;
use yii\web\View;
use yii\helpers\Url;
use open20\amos\admin\AmosAdmin;



if (
    method_exists($widget->model, 'getValidatedStatus')
    &&  $widget->model->status != $widget->model->getValidatedStatus()
) {
    return '';
}

CommentsAsset::register($this);

/**
 * @var \open20\amos\comments\widgets\CommentsWidget $widget
 */
$js = "
$('#contribute-btn').on('click', function (event) {
    if (typeof tinymce != 'undefined') {
        tinymce.triggerSave();
    }
    Comments.saveComment(" . $widget->model->id . ", '" . addslashes($widget->model->className()) . "')
});
";
$this->registerJs($js, View::POS_READY);

$class = $widget->model->className();

/** @var AmosComments $commentsModule */
$commentsModule = Yii::$app->getModule(AmosComments::getModuleName());

ModalUtility::createAlertModal([
    'id' => 'ajax-error-comment-modal-id',
    'modalDescriptionText' => AmosComments::t('amoscomments', '#AJAX_ERROR_COMMENT')
]);
ModalUtility::createAlertModal([
    'id' => 'empty-comment-modal-id',
    'modalDescriptionText' => AmosComments::t('amoscomments', '#EMPTY_COMMENT')
]);
ModalUtility::createAlertModal([
    'id' => 'scan-comment-modal-id',
    'modalDescriptionText' => AmosComments::t('amoscomments', 'Il commento contiene termini non consentiti')
]);
?>

<?php if (Yii::$app->getUser()->can('COMMENT_CREATE', ['model' => $widget->model])) : ?>
    <?php
    $displayNotifyCheckBox = true;

    if (isset($commentsModule->displayNotifyCheckbox)) {
        if (is_bool($commentsModule->displayNotifyCheckbox)) {
            $displayNotifyCheckBox = $commentsModule->displayNotifyCheckbox;
        }
    }

    $displayNotifyCheckBox = $displayNotifyCheckBox && $commentsModule->modelCanDoIt(
        $class,
        'enableUserSendMailCheckbox'
    );

    $openAccordion = false;

    if (isset($commentsModule->accordionOpenedByDefault)) {
        if (is_bool($commentsModule->accordionOpenedByDefault)) {
            if ($commentsModule->accordionOpenedByDefault) {
                $openAccordion = 0;
            }
        }
    }
    
    $notifyCheckbox = $this->render('_send_notify_checkbox', [
        'widget' => $widget,
        'enableUserSendMailCheckbox' => $commentsModule->enableUserSendMailCheckbox,
        'displayNotifyCheckBox' => $displayNotifyCheckBox,
        'checkboxName' => 'send_notify_mail',
        'viewTypePosition' => Comment::VIEW_TYPE_POSITION
    ] );

    $scope = \open20\amos\cwh\AmosCwh::getInstance()->getCwhScope();
    $tags = [];
    ?>
    <div id='bk-contribute'>
        <?php $redactorComment = Html::tag(
            'div',
            Html::tag(
                'div',
                Html::label(
                    $widget->options['commentTitle'],
                    'contribute-area', ['class' => 'sr-only']
                )
                . TextEditorWidget::widget([
                    'name' => 'contribute-area',
                    'value' => null,
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'contribute-area',
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'placeholder' => $widget->options['commentPlaceholder'],
                        'plugins' => $widget->plugins,
                        'toolbar' => $widget->toolbar,
                        'mobile' => $widget->rteMobile,
                        'mentions' => [
                            'url' => Url::to([
                                '/' . AmosAdmin::getModuleName() . '/user-profile/find-name-user-by-cwh',
                                'className' => $class,
                                'model_id' => $widget->model->id,
                            ])
                        ]
                    ],
                ]),
                ['class' => '']
            ),
            [
                'id' => '',
                'class' => 'contribute-container',
            ]
        );

        if (
            $commentsModule->modelCanDoIt($class, 'enableUserSendAttachment')
            && $commentsModule->enableAttachmentInComment
        ) {
            $attachmComment = Html::tag(
                'div',
                AttachmentsInput::widget(
                    [
                        'id' => 'commentAttachments',
                        'name' => 'commentAttachments',
                        'model' => $widget->model,
                        'options' => [ // Options of the Kartik's FileInput widget
                            'multiple' => true, // If you want to allow multiple upload, default to false
                        ],
                        'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                            'maxFileCount' => $commentsModule->maxCommentAttachments, // Client max files
                            'showPreview' => false,
                            'allowedFileExtensions' => $commentsModule->hasProperty(whiteListFilesExtensions) ? $commentsModule->whiteListFilesExtensions : '',
                        ]
                    ]
                ),
                ['class' => '']
            );
        } else {
            $attachmComment = '';
        }

        $btnComment = Html::tag(
            'div',
            Html::button(
                AmosComments::t('amoscomments', '#COMMENT_BUTTON'),
                [
                    'id' => 'contribute-btn',
                    'class' => 'btn btn-primary',
                    'title' => AmosComments::t('amoscomments', 'Comment content')
                ]
            ),
            ['class' => 'text-right']
        );
        ?>

        <div id="comments_contribute" class="contribute m-t-35">
            <div class="row">
                <div class="col-xs-12">
                    <strong class="text-uppercase">
                        <?= $widget->options['commentTitle'] ?>
                    </strong>
                </div>
                <div class="col-xs-12 m-b-15 m-t-15">
                    <?= $redactorComment ?>
                </div>
                <div class="col-xs-6 box-upload-file">
                    <?= $attachmComment ?>
                </div>
                <div class="col-xs-12 cta-comment">
                    <div class="row flexbox">
                        <div class="col-xs-8"><?= $notifyCheckbox ?></div>
                        <div class="col-xs-4">
                            <?= $btnComment ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php
    $urlRedirect = \Yii::$app->request->get('urlRedirect');
    if (
        $urlRedirect
        && (
            strpos($urlRedirect, \Yii::$app->params['platform']['frontendUrl']) !== false
            || strpos($urlRedirect, \Yii::$app->params['platform']['backendUrl']) !== false)
        ) {
        
        echo Html::hiddenInput(
            'urlRedirect',
            $urlRedirect,
            ['id' => 'url-redirect']
        );
    }
    ?>

<?php endif; ?>