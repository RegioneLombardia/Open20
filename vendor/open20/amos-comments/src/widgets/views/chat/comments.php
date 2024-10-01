<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\widgets\views\comments-widget
 * @category   CategoryName
 */

use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\comments\AmosComments;
use open20\amos\comments\assets\CommentsChatAsset;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\AmosLinkPager;
use yii\web\View;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Url;

$asset = CommentsChatAsset::register($this);

function object_to_array($data)
{
    if (is_array($data) || is_object($data)) {
        $result = array();

        foreach ($data as $key => $value) {
            $result[$key] = object_to_array($value);
        }

        return $result;
    }

    return $data;
}

/**
 * @var \open20\amos\comments\widgets\CommentsWidget $widget
 * @var \open20\amos\comments\models\Comment[] $comments
 * @var \open20\amos\comments\models\CommentReply[] $commentReplies
 * @var \yii\data\Pagination $pages
 * @var boolean|null $notificationUserStatus
 */

// Variabili da dare a Javascript
$notificationChanger = Url::toRoute([
    '/comments/comment/comment-notification-user',
    'context' => $widget->model->className(),
    'contextId' => $widget->model->id,
    'enable' => ''
]);
$contextMessage = json_encode($widget->model->className());

$theModel = json_encode($widget->model->className());

$js = <<<JS
    $(document).ready(function(){
        // Attributi di Comments
        Comments.context = {$contextMessage};
        Comments.context_id = {$widget->model->id};
        Comments.model = {$theModel};
        
        // Abbasso la barra laterale
        Comments.updateScroll();
        
        // Numero di messaggi presenti
        Comments.totalComments = $('.content-message-chat').length;
        // Salvo l'ultimo messaggio che vedo in chat
        Comments.lastMessageId = (Comments.totalComments > 0) ? $('.content-message-chat').last().prop('id').split('-')[1] : 0;
        
        // Controllo tag e link nei messaggi arrivati non ajax
        $('.content-message-chat').each(function(){
            let message = $(this).find('.answer_text');
            let messageText = Comments.at2Strong(message.text());
            messageText = Comments.search4Links(messageText);
            message.html(messageText);
        });
    });    
    
    // Aggiornamento automatico
    var refreshChat = window.setInterval(function(){
        Comments.autoupdate();
    }, 5*1000); // Comments.autoupdateInterval per test
    
    // Pulsante notifiche messaggi
    $(document).on('click', '#toggleNotification', function(event){
        Comments.toggleNotification("$notificationChanger");
    });
    
    // Pulsantino cancellazione messaggio
    $(document).on('click', '.deleteComment', function(event){
        Comments.idDeleteComment = $(this).attr('id'); // Id del messaggio contenente il codice univoco
        $('#ajax-delete-comment-confirm-id').modal('show');      
    });
    
    // Pulsante modale
    $(document).on('click', '#confirmDeleteComment', function(event){
        Comments.deleteComment();  
        $('#ajax-delete-comment-confirm-id').modal('hide');
    });
    
    // Pulsantino modifica messaggio
    $(document).on('click', '.updateComment', function(event){
        // Reset evidenziazione grafica
        Comments.resetSelectedComment();
        // Id del commento
        let id = $(this).attr('id').split("-")[1];
        Comments.idUpdateComment = id;
        Comments.selectedComment();
        // Inserisci nel text-area il commento da modificare e prendi il focus
        let textComment = $('#comment-'+id+' p[class*="answer_text"]').text();
        $('#contribute-area').val(textComment).focus();
        
        // Modifica il pulsante di submit
        Comments.updateButton();
        Comments.enableSendButton();
    });
JS;
$this->registerJs($js, View::POS_READY);

$class = $widget->model->className();

/** @var AmosComments $commentsModule */
$commentsModule = Yii::$app->getModule(AmosComments::getModuleName());

ModalUtility::createConfirmModal([
    'id' => 'ajax-delete-comment-confirm-id',
    'modalDescriptionText' => AmosComments::t('amoscomments', 'Vuoi veramente cancellare il messaggio?'),
    'confirmBtnOptions' => [
        'class' => 'btn btn-navigation-primary',
        'id' => 'confirmDeleteComment'
    ]
]);

ModalUtility::createAlertModal([
    'id' => 'scan-comment-modal-id',
    'modalDescriptionText' => AmosComments::t('amoscomments', 'Il commento contiene termini non consentiti')
]);

$displayNotifyCheckBox = true;
$contextObject = null;
if (isset($commentsModule->displayNotifyCheckbox)) {
    if (is_bool($commentsModule->displayNotifyCheckbox)) {
        $displayNotifyCheckBox = $commentsModule->displayNotifyCheckbox;
    }
}

$displayNotifyCheckBox = $displayNotifyCheckBox && $commentsModule->modelCanDoIt($class, 'displayNotifyCheckbox');
?>


<div id="comments_anchor">
    <div class="chat-header">
        <?=
       Html::tag('h2', 'CHAT');
        ?>

        <?php
        if (in_array($widget->model->className(), AmosComments::instance()->bellNotificationEnabledClasses)) :
        ?>
            <div class="subtitle-text-container">
                <?php
                if ($notificationUserStatus) :
                    $checked = 'checked';
                    $icon = '<span class="am am-notifications-add m-r-5" style="font-size: 24px;"></span>';
                else :
                    $checked = '';
                    $icon = '<svg class="m-r-5" style="width:24px;height:24px" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                          d="M17.5 13A4.5 4.5 0 0 0 13 17.5A4.5 4.5 0 0 0 17.5 22A4.5 4.5 0 0 0 22 17.5A4.5 4.5 0 0 0 17.5 13M17.5 14.5A3 3 0 0 1 20.5 17.5A3 3 0 0 1 20.08 19L16 14.92A3 3 0 0 1 17.5 14.5M14.92 16L19 20.08A3 3 0 0 1 17.5 20.5A3 3 0 0 1 14.5 17.5A3 3 0 0 1 14.92 16M12 2C10.9 2 10 2.9 10 4C10 4.1 10 4.19 10 4.29C7.12 5.14 5 7.82 5 11V17L3 19V20H11.5A6.5 6.5 0 0 1 11 17.5A6.5 6.5 0 0 1 17.5 11A6.5 6.5 0 0 1 19 11.18V11C19 7.82 16.88 5.14 14 4.29C14 4.19 14 4.1 14 4C14 2.9 13.11 2 12 2M10 21C10 22.11 10.9 23 12 23C12.5 23 12.97 22.81 13.33 22.5A6.5 6.5 0 0 1 12.03 21Z"/>
                                </svg>';
                endif;
                ?>
                <div class="form-check form-switch" data-placement="left" data-toggle="tooltip" title="Ricevi notifiche email dei messaggi in arrivo da questa chat">
                    <input class="form-check-input" type="checkbox" role="switch" id="toggleNotification" <?= $checked ?>>
                    <div id="iconNotification"><?= $icon ?></div>
                    <label class="form-check-label" for="flexSwitchCheckDefault"><?= AmosComments::t('amoscomments', 'Ricevi notifiche') ?></label>
                </div>
            </div>
        <?php
        endif;
        ?>

    </div>
    <div class="comment-chat-content">
        <?php if (!empty($comments)) { ?>
            <?php
            foreach (array_reverse($comments) as $comment) :
                /*echo ContextMenuWidget::widget([
                    'model' => $comment,
                    'actionModify' => "/comments/comment/update?id=" . $comment->id,
                    'actionDelete' => "/comments/comment/delete?id=" . $comment->id,
                    'labelDeleteConfirm' => \open20\amos\core\module\BaseAmosModule::t('community', 'Sei sicuro di voler cancellare questo link?')
                ]);*/
                if (empty($contextObject)) {
                    $classContext = $comment->context;
                    $contextObject = $classContext::findOne($comment->context_id);
                }
            ?>
                <?php
                $createdUserProfile = $comment->createdUserProfile;
                /** @var \open20\amos\comments\models\Comment $comment */
                ?>
                <div class="content-message-chat" id="comment-<?= $comment->id ?>" tabindex="0">
                    <div class="row">
                        <div class="col-md-3">
                            <?php if (!empty($createdUserProfile)) { ?>
                                <div class="media-left">
                                    <!-- Immagine -->
                                    <div>
                                        <?= UserCardWidget::widget(['model' => $createdUserProfile, 'enableLink' => false, 'avatarXS' => true]) ?>
                                    </div>
                                    <strong class="name-author">
                                        <small>
                                            <?php if (!empty($createdUserProfile)) : ?>
                                                <?php if (isset(\Yii::$app->params['disableLinkContentCreator']) && (\Yii::$app->params['disableLinkContentCreator'] === true)) : ?>
                                                    <?= $createdUserProfile; ?>
                                                <?php else : ?>
                                                    <?=
                                                    (!empty($createdUserProfile) ?
                                                        Html::a(
                                                            $createdUserProfile,
                                                            [
                                                                (\Yii::$app->user->isGuest ? '#' : '/' . AmosAdmin::getModuleName() . '/user-profile/view'),
                                                                'id' => $createdUserProfile->id
                                                            ]
                                                        ) : '#### ####')
                                                    ?>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <?= '#### ####'; ?>
                                            <?php endif; ?>
                                        </small>
                                    </strong>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-7">
                            <div class="media-body">
                                <p class="answer_text"><?= Yii::$app->getFormatter()->asRaw($comment->comment_text) ?></p>


                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="content-action-date">
                                <div class="action-message-chat">
                                    <?php
                                    if (Yii::$app->getUser()->can('COMMENT_DELETE', ['model' => $comment, 'user_id' => $comment->created_by])) { ?>
                                        <button id="deleteComment-<?= $comment->id ?>" class="btn btn-xs btn-danger deleteComment bi bi-bucket">
                                            <span class="am am-delete"></span>
                                        </button>
                                    <?php }

                                    if (Yii::$app->getUser()->can('COMMENT_UPDATE', ['model' => $comment, 'user_id' => $comment->created_by])) { ?>
                                        <button id="updateComment-<?= $comment->id ?>" class="btn btn-xs btn-secondary updateComment bi bi-gea">
                                            <span class="am am-edit"></span>
                                        </button>
                                    <?php } ?>
                                </div>
                                <?php if (Yii::$app->getFormatter()->asDate($comment->created_at, 'short') === date('d/m/y')) { ?>
                                    <small class="date-message"> <?= Yii::$app->getFormatter()->asTime($comment->created_at, 'short') ?></small>
                                <?php } else { ?>
                                    <small class="date-message"> <?= Yii::$app->getFormatter()->asDateTime($comment->created_at, 'short') ?></small>
                                <?php } ?>
                            </div>

                        </div>
                    </div>


                </div>
            <?php endforeach; ?>
        <?php } ?>
    </div>
    <div id="comments-loader" class="text-center hidden">
        <?=
        Html::img($asset->baseUrl . "/img/inf-circle-loader.gif", ['alt' => AmosComments::t('amoscomments', 'Loading')])
        ?>
    </div>
</div>