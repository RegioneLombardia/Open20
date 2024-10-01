<?php

use open20\amos\chat\AmosChat;
use open20\amos\chat\widgets\ConversationWidget;
use open20\amos\chat\widgets\MessageWidget;
use open20\amos\chat\widgets\UserContactsWidget;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ListView;

//use open20\amos\core\forms\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $conversationDataProvider
 * @var \yii\data\ActiveDataProvider $userContactDataProvider
 */

/*$this->params = ArrayHelper::merge($this->params, [
    'user' => $user,
    'users' => $users,
]);*/

$this->title = AmosChat::t('amoschat', 'Messaggi privati');
$this->params['breadcrumbs'][] = $this->title;
$countConversations = $conversationDataProvider->getTotalCount();
$moduleChat = \Yii::$app->getModule(AmosChat::getModuleName());
$showVideoConference  = !empty(\Yii::$app->getModule('videoconference')) && $moduleChat->enableVideoconference;

if($showVideoConference) {
    $loggedUserProfileId = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one()->id;
    $receiverUserProfileId = null;
    if(!is_null($contact)){
        $receiverUserProfile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $contact->id])->one();
        if(!is_null($receiverUserProfile)){
             $receiverUserProfileId = $receiverUserProfile->id;
        }
    }
    $errorMessage = AmosChat::t('amoschat', 'Error! Cannot open videoconference.');
    $linkToVideoconf = \yii\helpers\Url::to("/videoconference/videoconf/meet");
    $here =  AmosChat::t('amoschat',"qui");
    $text = AmosChat::t('amoschat',"Sei stato invitato ad una videoconferenza, clicca ");
    $text2 = AmosChat::t('amoschat'," per accedere");
    $js = <<<JS
    $('.videoconference_btn').click(function(){
  
        $.ajax({
           url: '/videoconference/videoconf/create-video-conference-ajax',
           type: 'get',
           data: {user_profile_id_sender: $loggedUserProfileId , user_profile_id_receiver: $receiverUserProfileId},
           success: function (data) {
               var room_id = data;
               if(data !== null || data !== undefined) {
                   var url = "/videoconference/videoconf/meet?id_room=" + room_id;
                    var linkto = "$linkToVideoconf" + "?id_room=" + room_id;
                    var messageVideoconf = "$text" + "<a href='"+ linkto +"'>$here</a>" + "$text2";
                    $("#chat-message").val(messageVideoconf);
                    $('#msg-send').trigger('click');
                   window.open(url);
               }
               else {
                   alert('$errorMessage');
               }
           }
      });
    });
     
JS;
$this->registerJs($js);
}
?>

<div id="amos-chat" class="chat-index">
    <div class="loading" style="display: none"><?= AmosChat::tHtml('amoschat', 'Caricamento') ?>&#8230;</div>

    <div class=" col-sm-4 col-xs-12 left-column">
        <div>
            <div class="text-to-change" <?= ($countConversations == 0) ? 'style="display: none"' : '' ?>>
                <p class="chat-title pull-left nop"><?= AmosChat::tHtml('amoschat', 'CONVERSAZIONI') ?></p>
                <div class="text-right pull-right nop">
                    <div class="btn btn-navigation-primary show-hide-contact"><?= AmosChat::tHtml('amoschat', 'CONTATTI') ?></div>
                    <!--                        < ?//= AmosIcons::show('comment', [
                    //                        'class' => 'btn show-hide-contact'
                    //                    ]); ?-->
                    <!--            <div class="manage btn">-->
                    <!--                <div class="dropdown">-->
                    <!--                    <a class="manage-menu" data-toggle="dropdown" href="" aria-expanded="true"><span class="am am-more-vert"></span></a>-->
                    <!--                    <ul class="dropdown-menu pull-right">-->
                    <!--                        <li>-->
                    <!-- TODO - DO FUNCTIONS FOR THE MENU    -->
                    <!--                            <a href=""><span class="am am-rotate-left"> </span>Refresh</a>-->
                    <!--                        </li>-->
                    <!--                        <li>-->
                    <!--                            <a href="#">-->
                    <!--                                <span class="am am-help-outline"> </span>Faq</a>-->
                    <!--                        </li>-->
                    <!--                    </ul>-->
                    <!--                </div>-->
                    <!--            </div>-->
                </div>
                <p class="col-xs-12 nop pull-left"><?= AmosChat::tHtml('amoschat', 'Conversazioni aperte con') ?></p>
            </div>
            <div class="text-to-change" <?= ($countConversations != 0) ? 'style="display: none"' : '' ?>>
                <p class="chat-title  pull-left nop"><?= AmosChat::tHtml('amoschat', 'CONTATTI') ?></p>
                <div class="text-right pull-right nop">
                    <div class="btn btn-navigation-primary show-hide-contact"><?= AmosChat::tHtml('amoschat', 'CONVERSAZIONI') ?></div>
                    <!--?= AmosIcons::show('comment', [
                        'class' => 'btn show-hide-contact'
                    ]); ?-->
                    <!--            <div class="manage btn">-->
                    <!--                <div class="dropdown">-->
                    <!--                    <a class="manage-menu" data-toggle="dropdown" href="" aria-expanded="true"><span class="am am-more-vert"></span></a>-->
                    <!--                    <ul class="dropdown-menu pull-right">-->
                    <!--                        <li>-->
                    <!-- TODO - DO FUNCTIONS FOR THE MENU    -->
                    <!--                            <a href=""><span class="am am-rotate-left"> </span>Refresh</a>-->
                    <!--                        </li>-->
                    <!--                        <li>-->
                    <!--                            <a href="#">-->
                    <!--                                <span class="am am-help-outline"> </span>Faq</a>-->
                    <!--                        </li>-->
                    <!--                    </ul>-->
                    <!--                </div>-->
                    <!--            </div>-->
                </div>
                <p class="col-xs-12 nop pull-left"><?= AmosChat::tHtml('amoschat', 'Scegli un contatto con cui iniziare una conversazione') ?></p>
            </div>
        </div>

        <!-- START TOOLS -->

        <div class="col-xs-12 nop">
            <?php
            /*
            $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'class' => 'default-form col-xs-12 nop'
                ], // important
            ]);
            */
            ?>
            <?= AmosIcons::show('search', [
                'class' => 'icon-search',
                'id' => 'ricerca-icon',
                'style' => 'display:none'
            ]); ?>
            <form>
                <label class="hidden" for="ricerca"><?= AmosChat::tHtml('amoschat', 'Cerca') ?></label>
                <input style="display:none;" type="text" id="ricerca" class="form-control " placeholder="<?= AmosChat::t('amoschat', 'Ricerca...') ?>"/>
            </form>
            <?php
            //ActiveForm::end();
            ?>
        </div>
        <!-- END TOOLS-->

        <?php
        $conversation = ConversationWidget::begin(
            [
                'dataProvider' => $conversationDataProvider,
                'itemView' => ConversationWidget::$CONVERSATION_TEMPLATE,
                'options' => [
                    'class' => 'conversation-wrap',
                    'id' => 'conversations',
                    'style' => ($countConversations == 0) ? 'display: none' : ''
                ],
                'user' => $user,
                'current' => $current,
                'clientOptions' => [
                    'loadUrl' => Yii::$app->getUrlManager()->createUrl(['chat/get/conversations']),
                    'baseUrl' => $asset->baseUrl,
                    'templateUrl' => ConversationWidget::$CONVERSATION_TEMPLATE,
                    'itemCssClass' => 'conversation',
                    'currentCssClass' => 'current',
                    'unreadCssClass' => 'unread',
                ],
                'pager' => [
                    'class' => \kop\y2sp\ScrollPager::className(),
                    'container' => '#conversations',
                    'item' => '.conversation',
                    'paginationSelector' => '#conversations .pagination',
                    'noneLeftText' => '',
                    'negativeMargin' => 100,
                    'triggerOffset' => 0
                ]
            ]);
        ?>

        <?php Pjax::begin([
            'id' => 'conversations-container-pjax',
            'timeout' => 40000,
        ]) ?>
        <?= $conversation->renderItems() ?>
        <?php Pjax::end() ?>
        <?php ConversationWidget::end() ?>


        <?php  echo UserContactsWidget::widget(
            [
                'dataProvider' => $userContactDataProvider,
                'itemView' => UserContactsWidget::$CONTACT_TEMPLATE,
                'options' => [
                    'class' => 'conversation-wrap',
                    'id' => 'user-contacts',
                    'style' => ($countConversations != 0) ? 'display: none' : ''
                ],
                'user' => $user,
                'current' => $current,
                'usersEnableByCommunityScope' => $idsCommunity,
                'clientOptions' => [
                    'loadUrl' => Yii::$app->getUrlManager()->createUrl(['chat/get/userContacts']),
                    'baseUrl' => $asset->baseUrl,
                    'templateUrl' => UserContactsWidget::$CONTACT_TEMPLATE,
                    'itemCssClass' => 'user-contact',
                    'currentCssClass' => 'current',
                    'unreadCssClass' => 'unread',
                ],
                'itemOptions' => ['class' => 'item-chat'],
                /*'pager' => [
                    'class' => \kop\y2sp\ScrollPager::className(),
                    'container' => '#user-contacts',
                    'item' => '.user-contacts-item',
                    'paginationSelector' => '#user-contacts .pagination',
                    'noneLeftText' => '',
                    'negativeMargin' => 250,
                    'triggerOffset' => 1,
                    'triggerText' => 'Next',
                ]*/
            ]);
        ?>


    </div>

    <?php
    if ($current):
        $message = MessageWidget::begin([
            'dataProvider' => $messageDataProvider,
            'itemView' => MessageWidget::$MESSAGE_TEMPLATE,
            'formView' => $this->context->viewPath . '/form.php',
            'user' => $user,
            'contact' => $contact,
            'options' => [
                'class' => 'conversation message-wrap col-sm-8 col-xs-12 right-column',
                'id' => 'messenger',
                'data-deleteurl' => $current['deleteUrl']
            ],
            'clientOptions' => [
                'loadUrl' => $current['loadUrl'],
                'sendUrl' => $current['sendUrl'],
                'sendMethod' => 'post',
                'templateUrl' => MessageWidget::$MESSAGE_TEMPLATE,
                'baseUrl' => $asset->baseUrl,
                'container' => '#msg-container',
                'form' => '#msg-form',
                'itemCssClass' => 'msg',
            ]
        ]);
        ?>

        <!-- START USER DETAILS -->
        <div class="col-xs-12 nop user-details ">
            <div class="media pull-left">
                <div class="media-left">
                    <div class="container-round-img-sm">
                        <?= $contact->getAvatar() ?>
                    </div>
                </div>
                <div class="media-body">
                    <h4 class="media-heading pull-left">
                        <strong><?= $contact['name'] ?></strong>
                        <br />
                        <small><!-- < ?= $contact['username'] ?> --></small>
                    </h4>
                    <div class="icon-tools text-right">
                        <a href="javascript:void(0)" title="refresh message">
                            <?= AmosIcons::show('refresh', [
                                'id' => 'refresh_btn',
                                'class' => 'am-2'
                            ]) ?>
                            <span class="sr-only"><?= AmosChat::t('amoschat', 'ricarica messaggio') ?></span>
                        </a>
                        <?php
                        if($showVideoConference) { ?>
                            <a href="javascript:void(0)" title="start videoconference">
                                <?= AmosIcons::show('videocam', [
                                    'class' => 'videoconference_btn am-2'
                                ]) ?>
                                <span class="sr-only"><?= AmosChat::t('amoschat', 'elimina messaggio') ?></span>
                            </a>
                        <?php }?>
                        <a href="javascript:void(0)" title="<?= AmosChat::t('amoschat', 'Archivia')?>">
                            <?= AmosIcons::show('delete', [
                                'class' => 'delete_btn am-2'
                            ]) ?>
                            <span class="sr-only"><?= AmosChat::t('amoschat', 'elimina messaggio') ?></span>
                        </a>
                        <!--                <div class="manage btn">-->
                        <!--                    <div class="dropdown">-->
                        <!--                        <a class="manage-menu" data-toggle="dropdown" href="" aria-expanded="true"><span-->
                        <!--                                class="am am-more-vert"></span></a>-->
                        <!--                        <ul class="dropdown-menu pull-right">-->
                        <!--                            <li>-->
                        <!--                                <a href=""><span class="am am-rotate-left"> </span> Refresh</a>-->
                        <!--                            </li>-->
                        <!--                            <li>-->
                        <!--                                <a href="#">-->
                        <!--                                    <span class="am am-help-outline"> </span> Faq </a>-->
                        <!--                            </li>-->
                        <!--                        </ul>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                    </div>
                </div>
            </div>


        </div>
        <!-- END USER DETAILS -->
        <div class="clearfix"></div>
        <div id="msg-container" class="container-chats row nom">
            <div id="messages-loader" style="display: none" class="text-center">
                <img alt="<?= AmosChat::t('amoschat', 'Caricamento') ?>..." src="<?= $asset->baseUrl ?>/img/inf-circle-loader.gif"/>
            </div>

            <?php Pjax::begin([
                'id' => 'msg-container-pjax',
                'timeout' => 40000,
            ]) ?>

            <?php
            $models = array_reverse($message->dataProvider->getModels());
            $keys = $message->dataProvider->getKeys();
            $rows = [];
            $when = false;
            foreach ($models as $index => $model) :
                if ($when != $model['when']) :
                    $when = $model['when'];
                    $rows = ArrayHelper::merge($rows, [
                        Html::tag('div', '<strong>' . $when . '</strong>', [
                            'class' => 'alert msg-date'
                        ])
                    ]);
                endif;
                $rows = ArrayHelper::merge($rows, [
                    $message->renderItem($model, $model['messageId'], $index)
                ]);
                ?>
            <?php endforeach; ?>
            <?= implode("\n", $rows) ?>
            <?php Pjax::end() ?>
        </div>

        <div class="col-sm-11 send-wrap ">
            <?= $message->renderForm() ?>
        </div>

        <div class="col-sm-1 btn-panel">
            <a id="msg-send" href="" class="send-message-btn pull-right" role="button" title="<?= AmosChat::t('amoschat', 'Invia messaggio') ?>">
                <?= AmosIcons::show('mail-send', [
                    'class' => 'am-2'
                ]); ?>
                <span class="sr-only"><?= AmosChat::t('amoschat', 'invia messaggio') ?></span>
            </a>
        </div>
        <?php
        MessageWidget::end();
    else:
        ?>
        <div class="col-xs-8 nop media user-details ">
            <div class="media-left">
                <h4><?= AmosChat::tHtml('amoschat', 'Non hai ancora aperto conversazioni con i tuoi contatti.<br>Per iniziare una nuova conversazione, clicca su un contatto.') ?></h4>
            </div>
        </div>
        <?php
    endif;
    ?>
</div>