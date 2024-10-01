<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\widgets\views
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\notificationmanager\AmosNotify;

/**
 * @var \open20\amos\notificationmanager\widgets\NotifyFrequencyAdvancedWidget $widget
 * @var string $htmlFrequencySelector
 * @var string $htmlContattiSuggeritiEmailFrequencySelector
 * @var string $htmlContenutiSuccessoEmailFrequencySelector
 * @var string $htmlProfiloSuccessoEmailFrequencySelector
 * @var \open20\amos\notificationmanager\models\NotificationConf $notificationConf
 * @var array $notificationNetworkValues
 * @var \yii\data\ActiveDataProvider $dataProviderNetwork
 * @var array $widgetConfData
 * @var array $notificationLanguagePreferences
 * @var \open20\amos\notificationmanager\AmosNotify $module
 */

/** @var UserProfile $widgetModel */
$widgetModel = $widget->getModel();

?>
<?php
$js = <<<JS
        if($("input:radio[name='notifications_enabled']:checked").val() === "1"){
            $('#container-nofification-enabled').show();
        }

        $('#notifications-enabled').click(function(){
                var valueNotifyEnabled = $("input:radio[name='notifications_enabled']:checked").val();
                if(valueNotifyEnabled == 1){
                    $('#container-nofification-enabled').show();
                }
                else {
                    $('#container-nofification-enabled').hide();
                    $('#notify-content-pubblication input[value="1"]').removeAttr('checked');
                    $('#notify-content-pubblication input[value="0"]').attr('checked', true);

                    $('#notify-comments input[value="1"]').removeAttr('checked');
                    $('#notify-comments input[value="0"]').attr('checked', true);
                }
        });
JS;
$this->registerJs($js);

?>
<div class="col-xs-12">
    <div class="form-group">
        <label class="control-label"><?= AmosNotify::t('amosnotify', 'Vuoi ricevere notifiche dalla piattaforma {NomePiattaforma}?', ['NomePiattaforma' => \Yii::$app->name]) ?></label>
        <?= Html::radioList('notifications_enabled', $notificationConf->notifications_enabled, [1 => AmosNotify::t('amosnotify', 'Si'), 0 => AmosNotify::t('amosnotify', 'No')], [
                'id' => 'notifications-enabled'
            ]
        ) ?>
    </div>
</div>

<div id="container-nofification-enabled" style="display:none">
    <div class="form-group col-xs-12">
        <?= $htmlFrequencySelector ?>
    </div>
    <?php if ($module && $module->enableNotificationContentLanguage) { ?>
        <div class="form-group col-xs-12">
            <div class="col-xs-6 nop">
                <label class="control-label"> <?= AmosNotify::t('amosnotify', 'In quale lingua desideri ricevere le notifiche di riepilogo della piattaforma?') ?></label>
                <?= \kartik\select2\Select2::widget([
                    'name' => 'notify_preference_language',
                    'data' => \yii\helpers\ArrayHelper::map(\lajax\translatemanager\models\Language::find()->andWhere(['status' => 1])->all(), 'language_id', 'language'),
                    'options' => ['placeholder' => AmosNotify::t('amosnotify', 'Select...')],
                    'pluginOptions' => [
                        'multiple' => true,
                    ],
                    'value' => $notificationLanguagePreferences
                ]) ?>
            </div>
        </div>
    <?php } ?>
    <div class="form-group col-xs-12">
        <div class="checkbox">
            <?php echo \open20\amos\core\helpers\Html::activeCheckbox($widgetModel, 'notify_from_editorial_staff', [
                'name' => 'notify_from_editorial_staff',
                'id' => 'notify_from_editorial_staff-1',
                'onchange' => "if(!$(this).is(':checked')){ $('#notify-uncheck').modal('show'); }"
            ]) ?>
        </div>
    </div>
    <?= $this->render('_content_notification') ?>
    <?php if (Yii::$app->authManager->checkAccess($widgetModel->user_id, 'MANAGE_REFEREE_CATEGORIES_TICKET_NOTIFICATIONS')): ?>
        <div class="form-group col-xs-12">
            <label class="control-label"><?= AmosNotify::t('amosnotify', 'Vuoi ricevere notifiche relative ai nuovi ticket nel plugin assistenza?') ?></label>
            <?= Html::radioList('notify_ticket_faq_referee', $notificationConf->notify_ticket_faq_referee, [1 => AmosNotify::t('amosnotify', 'Si'), 0 => AmosNotify::t('amosnotify', 'No')], [
                    'id' => 'notify-ticket-faq-referee',
                ]
            ) ?>
        </div>
    <?php endif; ?>
    <?php if ($module->enableNewsletter): ?>
    <div class="form-group col-xs-12">
        <label class="control-label"><?= AmosNotify::t('amosnotify', 'Vuoi ricevere le newsletter?') ?></label>
        <?= Html::radioList('notify_newsletter', $notificationConf->notify_newsletter, [1 => AmosNotify::t('amosnotify', 'Si'), 0 => AmosNotify::t('amosnotify', 'No')], [
                'id' => 'notify-newsletter',
            ]
        ) ?>
    </div>
    <?php endif; ?>
    <div class="form-group col-xs-12">
        <label class="control-label"><?= AmosNotify::t('amosnotify', 'Vuoi ricevere aggiornamenti di avvenuta pubblicazione contenuto?') ?></label>
        <?= Html::radioList('notify_content_pubblication', $notificationConf->notify_content_pubblication, [1 => AmosNotify::t('amosnotify', 'Si'), 0 => AmosNotify::t('amosnotify', 'No')], [
                'id' => 'notify-content-pubblication',
            ]
        ) ?>
        <!--        --><?php //Html::checkbox('notify_content_pubblication', $notificationConf->notify_content_pubblication,
        //            [
        //                'label' => AmosNotify::t('amosnotify', ''),
        //                'id' => 'notify-content-pubblication'
        //            ]
        //        ); ?>
    </div>
    <div class="form-group col-xs-12">
        <label class="control-label"><?= AmosNotify::t('amosnotify', 'Vuoi ricevere aggiornamenti di pubblicazione di un contributo per un contenuto di tuo interesse?') ?></label>
        <?= Html::radioList('notify_comments', $notificationConf->notify_comments, [1 => AmosNotify::t('amosnotify', 'Si'), 0 => AmosNotify::t('amosnotify', 'No')], [
                'id' => 'notify-comments'
            ]
        ) ?>
        <!--        --><?php //Html::checkbox('notify_comments', $notificationConf->notify_comments,
        //            [
        //                'label' => AmosNotify::t('amosnotify', 'Vuoi ricevere aggiornamenti di pubblicazione di un contributo per un contenuto di tuo interesse?', ['NomePiattaforma'=> \Yii::$app->name]),
        //                 'id' => 'notify-comments',
        //            ]
        //        ); ?>
    </div>

    <?php if ($module->enableSuggestions): ?>
        <div class="form-group col-xs-12">
            <label><?= AmosNotify::t('amosnotify', 'Vuoi ricevere aggiornamenti relativi all\'accettazione della richiesta di contatto da parte di un utente?') ?></label>
            <?= Html::radioList('contatto_accettato_flag', $notificationConf->contatto_accettato_flag, [1 => AmosNotify::t('amosnotify', 'Si'), 0 => AmosNotify::t('amosnotify', 'No')], [
                    'id' => 'contatto-accettato-flag'
                ]
            ) ?>
            <!--        --><?php //Html::checkbox('contatto_accettato_flag', $notificationConf->contatto_accettato_flag,
            //            [
            //                'label' => AmosNotify::t('amosnotify', 'Vuoi ricevere aggiornamenti relativi all\'accettazione della richiesta di contatto da parte di un utente?', ['NomePiattaforma'=> \Yii::$app->name]),
            //                 'id' => 'contatto-accettato-flag',
            //            ]
            //        ); ?>
        </div>

        <div class="form-group col-xs-12">
            <label class="control-label"><?= AmosNotify::t('amosnotify', 'Con quale frequenza desideri ricevere aggiornamenti relativi ai contatti suggeriti?') ?></label>
            <?= $htmlContattiSuggeritiEmailFrequencySelector ?>
        </div>

        <div class="form-group col-xs-12">
            <label class="control-label"><?= AmosNotify::t('amosnotify', 'Vuoi ricevere aggiornamenti riepilogativi in caso di un periodo di inattività del tuo profilo?') ?></label>
            <?= Html::radioList('periodo_inattivita_flag', $notificationConf->periodo_inattivita_flag, [1 => AmosNotify::t('amosnotify', 'Si'), 0 => AmosNotify::t('amosnotify', 'No')], [
                    'id' => 'periodo-inattivita-flag'
                ]
            ) ?>
            <!--        -->
            <?php
            //Html::checkbox('periodo_inattivita_flag', $notificationConf->periodo_inattivita_flag,
            //            [
            //                'label' => AmosNotify::t('amosnotify', 'Vuoi ricevere aggiornamenti riepilogativi in caso di un periodo di inattività del tuo profilo?', ['NomePiattaforma'=> \Yii::$app->name]),
            //                 'id' => 'contatto-accettato-flag',
            //            ]
            //        );
            ?>
        </div>


        <div class="form-group col-xs-12">
            <label class="control-label"><?= AmosNotify::t('amosnotify', 'Con quale frequenza desideri ricevere aggiornamenti relativi ai tuoi contenuti di successo?') ?></label>
            <?= $htmlContenutiSuccessoEmailFrequencySelector ?>
        </div>


        <div class="form-group col-xs-12">
            <label class="control-label"><?= AmosNotify::t('amosnotify', 'Con quale frequenza desideri ricevere aggiornamenti relativi alle visualizzazioni del tuo profilo?') ?></label>
            <?= $htmlProfiloSuccessoEmailFrequencySelector ?>
        </div>
    <?php endif; ?>

    <?php
    if (!empty($dataProviderNetwork)) {
        echo "<p>" . AmosNotify::t('amosnotify',
                'Con quale frequenza desideri ricevere aggiornamenti dalla piattaforma per la pubblicazione di un nuovo contenuto di tuo interesse all’interno delle community?') . "</p>";
        echo \open20\amos\core\views\AmosGridView::widget([
            'dataProvider' => $dataProviderNetwork,
            'columns' => [
                'logo_id' => [
                    'headerOptions' => [
                        'id' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Logo'),
                        'width' => '100'
                    ],
                    'contentOptions' => [
                        'headers' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Logo'),
                    ],
                    'label' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Logo'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        return \open20\amos\community\widgets\CommunityCardWidget::widget(['model' => $model]);
                    }
                ],
                [
                    'attribute' => 'name',
                    'format' => 'html',
                    'value' => function ($model) {
                        /** @var \open20\amos\community\models\Community $model */
                        return Html::a($model->name, ['/community/community/view', 'id' => $model->id], [
                            'title' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Apri il profilo della community {community_name}', ['community_name' => $model->name])
                        ]);
                    }
                ],
                'communityType' => [
                    'attribute' => 'communityType',
                    'format' => 'html',
                    'value' => function ($model) {
                        /** @var \open20\amos\community\models\Community $model */
                        if (!is_null($model->community_type_id)) {
                            return \open20\amos\community\AmosCommunity::t('amoscommunity', $model->communityType->name);
                        } else {
                            return '-';
                        }
                    }
                ],
                [
                    'label' => AmosNotify::t('amosnotify', 'Frequency'),
                    'value' => function ($model) use ($widgetConfData, $notificationNetworkValues) {
                        return \kartik\select2\Select2::widget([
                            'data' => $widgetConfData,
                            'name' => 'notifyCommunity[' . $model->id . ']',
                            'value' => !empty($notificationNetworkValues[$model->id]) ? $notificationNetworkValues[$model->id] : null,
                            'options' => [
                                'lang' => substr(\Yii::$app->language, 0, 2),
                                'multiple' => false,
                                'placeholder' => AmosNotify::t('amosnotify', 'Select/Choose') . '...',
                            ]
                        ]);
                    },
                    'format' => 'raw'
                ],
//                'created_by' => [
//                    'attribute' => 'created_by',
//                    'format' => 'html',
//                    'value' => function($model){
//                        /** @var \open20\amos\community\models\Community $model */
//                        $name = '-';
//                        if(!is_null($model->created_by)) {
//                            $creator = \open20\amos\core\user\User::findOne($model->created_by);
//                            if(!empty($creator)) {
//                                return $creator->getProfile()->getNomeCognome();
//                            }
//                        }
//                        return $name;
//                    }
//                ],
//                'status' => [
//                    'attribute' => 'status',
//                    'label' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Status'),
//                    'headerOptions' => [
//                        'id' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Status'),
//                    ],
//                    'contentOptions' => [
//                        'headers' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Status'),
//                    ],
//                    'value' => function($model)use ($widget){
//                        /** @var \open20\amos\community\models\Community $model */
//                        $mmrow = \open20\amos\community\models\CommunityUserMm::findOne(['user_id' => $widgetModel->user_id, 'community_id' => $model->id]);
//                        return  $mmrow->status;
//                    }
//                ],
//                'role' => [
//                    'attribute' => 'role',
//                    'label' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Role'),
//                    'headerOptions' => [
//                        'id' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Role'),
//                    ],
//                    'contentOptions' => [
//                        'headers' => \open20\amos\community\AmosCommunity::t('amoscommunity', 'Role'),
//                    ],
//                    'value' => function($model) use ($widget){
//                        /** @var \open20\amos\community\models\Community $model */
//                        $mmrow = \open20\amos\community\models\CommunityUserMm::findOne(['user_id' =>  $widgetModel->user_id, 'community_id' => $model->id]);
//                        return  $mmrow->role;
//                    }
//                ],
            ]
        ]);
    }
    ?>
</div>
