<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\views\invitation
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\invitations\models\Invitation;
use open20\amos\invitations\Module;
use open20\amos\invitations\utility\InvitationsUtility;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\invitations\models\search\InvitationSearch $model
 */

$this->title = Module::t('amosinvitations', 'All invitations');
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
 //--- SHOW the modal
    $(document).on('click','.re-send', function() {
        $('#modal_sent').modal('show')
            .find('#modal_content')
            .load($(this).attr('value'));
    });

JS;

$this->registerJs($js);
?>

<div class="invitation-index">
    <h4><?= Module::t('amosinvitations', '#introduction_invitation', ['platformName' => Yii::$app->name]) ?></h4>
    <br>
    <?php
    echo $this->render('_search', [
        'model' => $model,
    ]); ?>
    
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>
    <?= $this->render('_modal', ['form' => $form, 'model' => $model, 'moduleName' => $moduleName,
        'contextModelId' => $contextModelId]) ?>
    
    <?php
    try {
        echo DataProviderView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $model,
            'currentView' => $currentView,
            'gridView' => [
                'columns' => [
                    [
                        'class' => 'open20\amos\core\views\grid\CheckboxColumn',
                        'name' => 'Invitation[selection]',
                        'header' => Module::t('amosinvitations', 'Selection'),
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            /** @var Invitation $model */
//                            if ($model->send) {
                            $retArray = InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                            if ($retArray['present']) {
                                return [
                                    'disabled' => true,
                                    'title' => $retArray['message'],
                                ];
                            } else {
//                                    return [
//                                        'disabled' => true,
//                                        'title' => Module::t('amosinvitations', 'Invitation already sended'),
//                                    ];
                            }
//                            } else {
//                                $retArray = InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
//                                if ($retArray['present']) {
//                                    return [
//                                        'disabled' => true,
//                                        'title' => $retArray['message'],
//                                    ];
//                                }
//                            }
                        }
                    ],
                    'name',
                    'surname',
                    'invitationUser.email',
                    'send_time:datetime',
                    [
                        'label' => Module::t('amosinvitations', 'Inviato da'),
                        'attribute' => 'createdUserProfile.nomeCognome',
                    ],
                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                        'template' => '{view}{update}{re-send}{delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                if (\Yii::$app->user->id == $model->created_by || \Yii::$app->user->can('INVITATIONS_ADMINISTRATOR')) {
                                    
                                    /** @var \open20\amos\invitations\models\Invitation $model */
                                    $retArray = InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                                    
                                    if (!$model->send) {
                                        $label = Module::t('amosinvitations', 'Update and send invitation...');
                                    } else {
                                        $label = Module::t('amosinvitations', 'Update and re-send invitation...');
                                    }
                                    $options = [
                                        'model' => $model,
                                        'class' => 'btn btn-tools-secondary',
                                        'title' => $label,
                                    ];
                                    
                                    //
                                    if ($retArray['present']) {
                                        $options['disabled'] = true;
                                        $options['title'] = $retArray['message'];
                                        $options['class'] = 'btn btn-tools-secondary';
                                    }
                                    
                                    $btn = Html::a(
                                        AmosIcons::show('email'),
                                        !$retArray['present'] ? ['update', 'id' => $model->id] : 'javascript:void(0)',
                                        $options
                                    );
                                    return $btn;
//                                } else {
//                                    return '';
//                                }
                                }
                            },
                            're-send' => function ($url, $model) {
                                /** @var \open20\amos\invitations\models\Invitation $model */
                                if ($model->send && \Yii::$app->user->can('INVITATIONS_ADMINISTRATOR')) {
                                    $retArray = InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                                    $options = [
                                        'model' => $model,
                                        'value' => \Yii::$app->urlManager->createUrl(['/invitations/invitation/invitations-sent', 'id' => $model->id, 'ajax' => true]),
                                        'class' => 'btn btn-tools-secondary re-send',
                                        'title' => Module::t('amosinvitations', 'Re-send invitation'),
                                    ];
                                    if ($retArray['present']) {
                                        $options['disabled'] = true;
                                        $options['title'] = $retArray['message'];
                                        $options['class'] = 'btn btn-tools-secondary';
                                    }
                                    $btn = Html::a(AmosIcons::show('refresh-sync'), 'javascript:void(0)', $options);
                                    return $btn;
                                }
                                return '';
                            }
                        ]
                    ],
                ],
            ],
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
    Modal::begin(['id' => 'modal_sent', 'size' => 'modal-lg', 'header' => Module::t('amosinvitations', 'Re-send invitation')]);
    echo "<div id='modal_content'></div>";
    Modal::end();
    
    ?>
    
    <?= Html::button(Module::t('amosinvitations', 'Send all selected'), [
        'class' => 'btn btn-primary pull-right',
        'value' => 'send-invitation',
        'type' => 'submit',
        'name' => 'submit-invitation',
        'data-confirm' => Module::t('amosinvitations', '#are-you-sure-send-all')
    ]);
    ?>
    
    
    <?php ActiveForm::end(); ?>
</div>
