<?php

/**
 * Aria S.p.A.
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

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\invitations\models\search\InvitationSearch $model
 * @var string $moduleName
 * @var string $contextModelId
 * @var string $registerAction
 */

/** @var Module $invitationsModule */
$invitationsModule = Module::instance();

$this->title = Module::t('amosinvitations', 'All invitations');
if(\Yii::$app->request->get('category')){
    if(!empty($invitationsModule->labelCategories[\Yii::$app->request->get('category')])){
        $category = $invitationsModule->labelCategories[\Yii::$app->request->get('category')];
        $this->params['titleSection'].= ' - '. \Yii::t('app', $category);
    }
    else{
        $this->params['titleSection'].= ' - '.\Yii::$app->request->get('category');
    }
}
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
    <div class="row">
        <div class="col-xs-12">
            <?= $this->render('_search', ['model' => $model,]); ?>

        </div>
        <div class="col-xs-12">
            <?php
            $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ]);
            ?>

            <?= $this->render('_modal', [
                'form' => $form, 'model' => $model, 'moduleName' => $moduleName,
                'contextModelId' => $contextModelId
            ]) ?>

            <?= DataProviderView::widget([
                'dataProvider' => $dataProvider,
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
                                $retArray = \open20\amos\invitations\utility\InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                                if ($retArray['present']) {
                                    return [
                                        'disabled' => true,
                                        'title' => $retArray['message'],
                                    ];
                                }
                            }
                        ],
                        'name',
                        'surname',
                        'invitationUser.email',
                        [
                            'attribute' => 'invite_accepted',
                            'format' => 'boolean',
                            'label' => Module::t('amosinvitations', 'Invite accepted')
                        ],
                        [
                            'value' => function ($model) {
                                $retArray = \open20\amos\invitations\utility\InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                                if (!empty($retArray['present'])) {
                                    return true;
                                }
                                return false;
                            },
                            'format' => 'boolean',
                            'label' => Module::t('amosinvitations', 'Email already present in platform')
                        ],

                        'send_time:datetime',
                        [
                            'class' => 'open20\amos\core\views\grid\ActionColumn',
                            'template' => '{view}{update}{re-send}{delete}',
                            'buttons' => [
                                'update' => function ($url, $model) use ($moduleName, $contextModelId) {
                                    /** @var \open20\amos\invitations\models\Invitation $model */
                                    if (\Yii::$app->user->id == $model->created_by) {
                                        $retArray = \open20\amos\invitations\utility\InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);

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
                                            AmosIcons::show('edit'),
                                            !$retArray['present'] ?
                                                [
                                                    'update',
                                                    'id' => $model->id,
                                                    'moduleName' => $moduleName,
                                                    'contextModelId' => $contextModelId
                                                ] : 'javascript:void(0)',
                                            $options
                                        );
                                        return $btn;
                                    }
                                    //                                } else {
                                    //                                    return '';
                                    //                                }
                                },
                                're-send' => function ($url, $model) use ($moduleName, $contextModelId) {
                                    /** @var \open20\amos\invitations\models\Invitation $model */
                                    if ($model->send
                                        /**&& \Yii::$app->user->can('INVITATIONS_ADMINISTRATOR')**/
                                    ) {
                                        $retArray = \open20\amos\invitations\utility\InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                                        $options = [
                                            'model' => $model,
                                            'value' => \Yii::$app->urlManager->createUrl([
                                                '/invitations/invitation/invitations-sent', 'id' => $model->id,
                                                'ajax' => true,
                                                'moduleName' => $moduleName,
                                                'contextModelId' => $contextModelId,
                                                'category' => \Yii::$app->request->get('category')
                                            ]),
                                            'class' => 'btn btn-tools-secondary re-send',
                                            'title' => Module::t('amosinvitations', 'Re-send invitation'),
                                        ];
                                        if ($retArray['present']) {
                                            $options['disabled'] = true;
                                            $options['title'] = $retArray['message'];
                                            $options['class'] = 'btn btn-tools-secondary';
                                        }
                                        $btn = Html::a(AmosIcons::show('mail-send'), 'javascript:void(0)', $options);
                                        return $btn;
                                    }
                                    return '';
                                },
                                'delete' => function ($url, $model) {
                                    $btn = '';
                                    $can = \Yii::$app->user->can('INVITATION_DELETE', ['model' => $model]);
                                    if ($can && empty($model->send_time)) {
                                        $btn = Html::a(AmosIcons::show('delete'), ['/invitations/invitation/delete', 'id' => $model->id], [
                                            'class' => 'btn btn-danger-inverse',
                                            'title' =>  Module::t('amosinvitations', 'Delete'),
                                            'data-confirm' =>  Module::t('amosinvitations', "Sei sicuro di eliminare l'invito?"),
                                        ]);
                                    }
                                    return $btn;
                                }
                            ]
                        ],
                    ],
                ],
            ]); ?>

            <div class="row">
                <div class="col-xs-12 m-t-15">
                    <p>
                        <?= Html::button(
                            AmosIcons::show('mail-send', ['class' => 'm-r-5']) . Module::t('amosinvitations', 'Send all selected'),
                            [
                                'class' => 'btn btn-outline-primary',
                                'value' => 'send-invitation',
                                'type' => 'submit',
                                'name' => 'submit-invitation',
                                'data-confirm' => Module::t('amosinvitations', '#are-you-sure-send-all')
                            ]
                        ) . ' ';
                        ?>
                        <?= Module::t('amosinvitations', 'Invia massivamente le mail di invito dei <strong>record selezionati</strong>') . ' ' ?>
                    </p>
                    <p>
                        <?= Html::button(
                            AmosIcons::show('delete', ['class' => 'm-r-5']) . Module::t('amosinvitations', 'Delete all selected'),
                            [
                                'class' => 'btn btn-danger-inverse',
                                'value' => 'delete-invitation',
                                'type' => 'submit',
                                'name' => 'delete-invitation',
                                'data-confirm' => Module::t('amosinvitations', '#are-you-sure-delete-all')
                            ]
                        ) . ' ';
                        ?>
                        <?= Module::t('amosinvitations', 'Cancella i <strong>record selezionati</strong> degli inviti <strong>importati</strong> da file e <strong>mai inviati</strong>') ?>
                    </p>

                </div>
            </div>




            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
<?php
\yii\bootstrap\Modal::begin(['id' => 'modal_sent', 'size' => 'modal-lg', 'header' => Module::t('amosinvitations', 'Re-send invitation')]);
echo "<div id='modal_content'></div>";
\yii\bootstrap\Modal::end();

?>