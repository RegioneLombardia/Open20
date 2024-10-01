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
use open20\amos\comments\assets\CommentsChatAsset;
use open20\amos\comments\models\Comment;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use yii\web\View;
use open20\amos\news\models\News;
use yii\helpers\Url;
use open20\amos\admin\AmosAdmin;

CommentsChatAsset::register($this);
$template = '<div class="content-message-chat" id="" tabindex="0">' .
    '<div class="row">' .
    '<div class="col-md-3">' .
    '<div class="media-left">' .
    '<div class="container-round-img-xs">' .
    '<img class="square-img" src="" alt="" style="margin-left: 0%; margin-top: 0%;">' .
    '</div>' .
    '<strong class="name-author">' .
    '<small>' .
    '<a href=""></a>' .
    '</small>' .
    '</strong>' .
    '</div>' .
    '</div>' .
    '<div class="col-md-7">' .
    '<div class="media-body">' .
    '</div>' .
    '<p class="answer_text"></p>' .
    '</div>' .
    '<div class="col-md-2">' .
    '<div class="content-action-date">' .
    '<div class="action-message-chat">' .
    '<button id="" class="btn btn-xs btn-danger deleteComment bi bi-bucket" style="margin-right:3px">' .
    '<span class="am am-delete"></span>' .
    '</button>' .
    '<button id="" class="btn btn-xs btn-secondary updateComment bi bi-gea">' .
    '<span class="am am-edit"></span>' .
    '</button>' .
    '</div>' .
    '<small class="date-message"></small>' .
    '</div>' .
    '</div>' .
    '</div>' .
    '</div>';
/**
 * @var \open20\amos\comments\widgets\CommentsWidget $widget
 */

$js = "
    // Premuto pulsante per salvare il messaggio
    $('.sendSaveComment').on('click', function (event) {
        if (typeof tinymce != 'undefined') {
            tinymce.triggerSave();
        }
        Comments.saveComment(" . $widget->model->id . ", '" . addslashes($widget->model->className()) . "');
    });
    
    // Premuto pulsante per modificare il messaggio
    $('.sendUpdateComment').on('click', function (event) {
        Comments.resetSelectedComment();
        Comments.updateComment();
    });
    
    // Premuto annulla, nessuna modifica al messaggio
    $('.sendCancelUpdateComment').on('click', function (event) {
        Comments.resetSelectedComment();
        $('#contribute-area').val('');
        Comments.saveButton();
        Comments.disableSendButton();
    });
    
    // Invio messaggio quando si preme ENTER
    $(document).on('keydown', '#contribute-area', function(event){
        if(event.which === 13 && !event.shiftKey) {
            event.preventDefault();
            if(!$('#contribute-btn').is(':hidden') && !$('#contribute-btn').is(':disabled')){
                Comments.saveComment(" . $widget->model->id . ", '" . addslashes($widget->model->className()) . "');
            }
            else if(!$('#contributeUpdate-btn').is(':hidden') && !$('#contributeUpdate-btn').is(':disabled')){
                Comments.resetSelectedComment();
                Comments.updateComment();
            } 
        }
    });
    
    // Abilitazione pulsante invio solo quando c'è del testo + limite max
    if($('#contribute-area').val().length < 1) Comments.disableSendButton();
    
    $(document).on('change keyup paste', '#contribute-area', function(event){  
        let messageLength = $('#contribute-area').val().length;
        if(messageLength < 1) Comments.disableSendButton();
        else {
            if(messageLength > 220){
                let newText = $('#contribute-area').val().slice(0, 220);
                $('#contribute-area').val(newText);
            }
            Comments.enableSendButton();
        }
    });
    
    Comments.template = '" . $template . "';
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
    $notifyCheckbox = $this->render(
        '_send_notify_checkbox',
        [
            'widget' => $widget,
            'enableUserSendMailCheckbox' => $commentsModule->enableUserSendMailCheckbox,
            'displayNotifyCheckBox' => $displayNotifyCheckBox,
            'checkboxName' => 'send_notify_mail',
            'viewTypePosition' => Comment::VIEW_TYPE_POSITION
        ]
    );

    $scope = \open20\amos\cwh\AmosCwh::getInstance()->getCwhScope();
    $tags = [];
    ?>
    <div id='bk-contribute'>
        <div class="write-comment-chat">

            <div>
                <div>
                    <?php $redactorComment = Html::textArea(
                        'contribute-area',
                        '',
                        [
                            'id' => 'contribute-area',
                            'placeholder' => AmosComments::t('amoscomments', 'Digita qui il messaggio (max 220 caratteri)'),
                            'rows' => '1'
                        ]
                    );

                    $btnComment =
                        Html::button(
                            AmosComments::t('amoscomments', 'Invia'),
                            [
                                'id' => 'contribute-btn',
                                'class' => 'btn btn-primary sendSaveComment',
                                'title' => AmosComments::t('amoscomments', 'Comment content')
                            ]
                        );

                    ?>

                    <?php
                    $btnCancelUpdateComment =
                        Html::button(
                            AmosComments::t('amoscomments', 'Annulla'),
                            [
                                'id' => 'contributeCancelUpdate-btn',
                                'class' => 'btn btn-outline-secondary sendCancelUpdateComment',
                                'style' => 'display:none',
                                'title' => AmosComments::t('amoscomments', 'Annulla')
                            ]
                        );
                    $btnUpdateComment =
                        Html::button(
                            AmosComments::t('amoscomments', 'Modifica'),
                            [
                                'id' => 'contributeUpdate-btn',
                                'class' => 'btn btn-warning sendUpdateComment',
                                'style' => 'display:none',
                                'title' => AmosComments::t('amoscomments', 'Comment content')
                            ]
                        );
                    ?>

                    <div class="redactor">
                        <div class="text-area">
                            <?= $redactorComment ?>
                        </div>
                        <div class="button-send-comment">
                            <?= $btnComment ?>
                            <?= $btnCancelUpdateComment ?>
                            <?= $btnUpdateComment ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12 notify">
                            <?= $notifyCheckbox ?>
                        </div>
                       
                    </div>


                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
    <?php
    if (\Yii::$app->request->get('urlRedirect') && (strpos(
        \Yii::$app->request->get('urlRedirect'),
        \Yii::$app->params['platform']['frontendUrl']
    ) !== false || strpos(
        \Yii::$app->request->get('urlRedirect'),
        \Yii::$app->params['platform']['backendUrl']
    ) !== false)) {
        echo Html::hiddenInput('urlRedirect', \Yii::$app->request->get('urlRedirect'), ['id' => 'url-redirect']);
    }
    ?>
<?php
endif ?>