<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\AmosGridView;
use open20\amos\report\AmosReport;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\widgets\Pjax;

/** @var \open20\amos\core\record\Record $model */

$js = <<<JS
  
    var text = $("#tab-reports-bullet").text();
    var countNotRead = $('.read-confirmation').length;
    if(countNotRead > 0){
        $("#tab-reports-bullet").text(text+countNotRead);
        $("#tab-reports-bullet").removeClass('hidden');
    }else{
       $("#tab-reports-bullet").addClass('hidden'); 
    }
    
JS;

$this->registerJs($js, View::POS_READY);
?>

<div class="col-xs-12 nop">

    <?php
    Pjax::begin(['id' => 'reports-pjax']);
    echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'Photo' => [
                'headerOptions' => [
                    'id' => AmosReport::t('amosreport', 'Photo'),
                ],
                'contentOptions' => [
                    'headers' => AmosReport::t('amosreport', 'Photo'),
                ],
                'label' => AmosReport::t('amosreport', 'Photo'),
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \open20\amos\admin\models\UserProfile $userProfile */
                    $userProfile = $model->user->getProfile();
                    return \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $userProfile]);
                }
            ],
            'name' => [
                'attribute' => 'user.userProfile.nomeCognome',
                'label' => AmosReport::t('amosreport', 'Name'),
                'headerOptions' => [
                    'id' => AmosReport::t('amosreport', 'name'),
                ],
                'contentOptions' => [
                    'headers' => AmosReport::t('amosreport', 'name'),
                ]
            ],
            'reportType' => [
                'attribute' => 'reportType.name',

                'label' => AmosReport::t('amosreport', 'Report type'),
                'headerOptions' => [
                    'id' => AmosReport::t('amosreport', 'Report type'),
                ],
                'contentOptions' => [
                    'headers' => AmosReport::t('amosreport', 'Report type'),
                ]
            ],
            'content' =>[
                'attribute' => 'content',
                'format' => 'html',
                'headerOptions' => [
                    'id' => AmosReport::t('amosreport', 'Description'),
                ],
                'contentOptions'=>[
                    'style' => 'min-width: 180px; overflow:hidden; word-break: break-word',
                    'headers' => AmosReport::t('amosreport', 'Description')
                ],
            ],
            'created_at' =>[
                'attribute' => 'created_at',
                'format' => 'dateTime',
                'headerOptions' => [
                    'id' => AmosReport::t('amosreport', 'Created at'),
                ],
                'contentOptions'=>[
                    'headers' => AmosReport::t('amosreport', 'Created at')
                ],
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{readConfirmation}',
                'buttons' => [
                    'readConfirmation' => function ($url, $model) {
                        /** @var \open20\amos\report\models\Report $model */
                        $btn = '';
                        if (!$model->status) {
                            $userId = Yii::$app->user->id;
                            $notify = Yii::$app->getModule('notify');
                            $notification = null;
                            if(isset($notify)) {
                                $notification = \open20\amos\notificationmanager\models\Notification::findOne([
                                    'class_name' => \open20\amos\report\models\Report::className(),
                                    'content_id' => $model->id,
                                ]);
                                if(!empty($notification)) {
                                    $notificationRead = \open20\amos\notificationmanager\models\NotificationsRead::findOne([
                                        'notification_id' => $notification->id,
                                        'user_id' => $userId
                                    ]);
                                }
                            }
                            if(empty($notificationRead)) {
                                Modal::begin([
                                    'id' => 'read-confirmation-popup-'.$model->id ,
                                    'header' => AmosReport::t('amosreport', 'Report read confirmation')
                                ]);
                                 $jsConfirm = "
                                 
                                    $.ajax({
                                                url: '/report/report/read-confirmation',
                                                type: 'POST',
                                                async: true,
                                                data: {
                                                    report_id: '".$model->id."',
                                                    notification_id: '".$notification->id."'
                                                },
                                                success: function(response) {
                                                    if(response) {
                                                        $('#ask-confirmation-message-".$model->id."').html('".
                                                            AmosReport::t('amosreport', 'The report has been updated')."');
                                                        $('#read-confirmation-popup-".$model->id."').find('.confirm-modal-btn').addClass('hidden');
                                                        $('#read-confirmation-btn-".$model->id."').removeClass('read-confirmation').addClass('hidden');
                                                        
                                                        var countNotRead = $('.read-confirmation').length;
                                                        if(countNotRead > 0){
                                                            $('#tab-reports-bullet').text(countNotRead);
                                                            $('#tab-reports-bullet').removeClass('hidden');
                                                        }else{
                                                           $('#tab-reports-bullet').addClass('hidden'); 
                                                        }
//                                                        $.pjax.reload({container:'#reports-pjax'});
                                                    }    
                                                }
                                            });
                                            return false;
                                ";

                                echo Html::tag('div', AmosReport::t('amosreport', "Do you confirm report read?"), ['id' => 'ask-confirmation-message-'.$model->id]);
                                echo Html::tag('div',
                                    Html::a(AmosReport::t('amosreport', 'Confirm'),
                                        null,
                                        [
                                            'class' => 'btn btn-navigation-primary confirm-modal-btn',
                                            'onclick' => $jsConfirm
                                        ])
                                    . Html::a(AmosReport::t('amosreport', 'Close'), null, ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal']),
                                    ['class' => 'pull-right m-15-0']
                                );
                                Modal::end();

                                $btn = Html::a(
                                    AmosIcons::show('square-check', [], 'dash') . "&nbsp;" . AmosReport::t('amosreport',
                                        'Read confirmation'),
                                    null ,
                                    [
                                        'class' => 'btn btn-navigation-primary read-confirmation font08',
                                        'id' => 'read-confirmation-btn-'.$model->id,
                                        'title' => AmosReport::t('amosreport', 'Read confirmation'),
                                        'data-target' => '#read-confirmation-popup-'.$model->id,
                                        'data-toggle' => 'modal'
                                    ]
                                );
                            }
                        }
                        return $btn;
                    },
                ]
            ]
        ]
    ]);
    Pjax::end();

    ?>
</div>


