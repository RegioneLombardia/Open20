<?php

use open20\amos\admin\models\UserProfile;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use open20\amos\sondaggi\models\SondaggiInvitations;
use open20\amos\sondaggi\utility\SondaggiUtility;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiInvitations $model
 * @var $isCommunitySurvey bool
 */

/** @var $moduleTag */
$moduleTag = \Yii::$app->getModule('tag');

$showHideFilters = 'display: none;';
$moduleSondaggi = \Yii::$app->getModule('sondaggi');
$showHideUsersContainer = '';
if ($moduleSondaggi->enableInvitationsForPlatformUsers && $moduleSondaggi->enableInvitationsForOrganizations) {
    if (!$isCommunitySurvey) {
        $showHideUsersContainer = 'display: none;';
    }
}
if ($model->target == SondaggiInvitations::TARGET_USERS) {
    $showHideUsersContainer = '';
}
$targetUsers = SondaggiInvitations::TARGET_USERS;
$communityId = $model->sondaggi->community_id;


$js = <<<JS

    var targetUsers = '{$targetUsers}';
    $('input[name="SondaggiUsersInvitations[type]"]').on('change', function(){
        if($(this).val() == targetUsers) {
            $('#invitations-users-filter').show();
        } else {
            $('#invitations-users-filter').hide();
        }
    });

    // function reloadSearchUsers(container, plus){
    //        $.pjax.reload({
    //             container: container,
    //             timeout: 20000,
    //            replace: false,
    //             url: '/sondaggi/dashboard-invitations/render-search-ajax-users',
    //             data: {
    //                 'data': $('#form-search').serialize(),
    //                 'plus': plus,
    //                 'target': 'users'
    //                 },
    //             method: 'post'
    //         }).done(function() {
    //             $('#filter-value-users').change();
    //         });
    // }

    // $(document).on('click','#btn-add-rule-users',function(e){
    //     e.preventDefault();
    //     reloadSearchUsers('#search-container-users', 1);
    // });

    // $(document).on('click', '#btn-remove-rule-users',function(e){
    //     e.preventDefault();
    //     var value = $(this).attr('data');
    //     $('#row-search-'+value+'-users').remove();
    //     // reloadSearchUsers('#search-container-users', 0);
    // });

    // function search
     $('#btn-search-users').click(function(e){
         e.preventDefault();
         $('#form-results').hide();
         $('#loader-users').show();
           $.ajax({
               url: '/sondaggi/dashboard-invitations/search-invited',
               type: 'post',
               data: {
                    'data': $('#form-search').serialize(),
                    'target': '{$targetUsers}',
                    'community_id': '{$communityId}'
               },
               success: function (data) {
                   $('#loader-users').hide();
                   $('#result-search-container').html(data);
                   $('#form-results').show();
               }
           });
     });
JS;

$this->registerJs($js);


$jsResultsUsers = <<<JS
    function(params) {
        return {
            q:params.term, 
            community_id: '{$communityId}'
        };
    }
JS;

?>

<div class="invitations-users-container" style="<?= $showHideUsersContainer ?>">

    <div class="p-t-20 p-b-20 d-flex title-substeps affix-top">
<!--        < ?= Amosicons::show('group', ['class' => 'am-lg m-r-10 m-t-5'], 'dash') ?>-->
        <h5 class="font-weight-bold ">
            <?= !$isCommunitySurvey ? AmosSondaggi::t('amossondaggi', "Cerca gli utenti da invitare") : AmosSondaggi::t('amossondaggi', 'Cerca i partecipanti della community da invitare'); ?>
        </h5>
    </div>

    <div class="row variable-gutters">
        <div class="col-md-12">
            <div class="invitations-users-type">
                <?php
                if ($model->isNewRecord) {
                    $typeSelected = SondaggiInvitationsSearch::SEARCH_ALL;
                } else {
                    $typeSelected = $model->type;
                }
                ?>
                <?= Html::radioList(
                     'SondaggiUsersInvitations[type]',
                    $typeSelected,
                        [
                            SondaggiInvitationsSearch::SEARCH_ALL => !$isCommunitySurvey ? AmosSondaggi::t('amossondaggi', 'Tutti gli utenti') : AmosSondaggi::t('amossondaggi', 'Tutti i partecipanti della community'),
                            SondaggiInvitationsSearch::SEARCH_FILTER => AmosSondaggi::t('amossondaggi', 'Da filtro'),
                        ],
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $return = '<div class="radio">';
                                $return .= '<label>';
                                $return .= Html::radio($name, $checked, ['value' => $value]);
                                $return .= $label;
                                $return .= '</label>';
                                $return .= '</div>';
    
                                return $return;
                            }
                        ]
                ); ?>
            </div>

            <?php
            if ($typeSelected == SondaggiInvitationsSearch::SEARCH_FILTER) {
                $showHideFilters = 'display: block;';
            }
            ?>
            <div class="invitations-users-filter form-group m-t-20" id="invitations-users-filter" style="<?= $showHideFilters ?>">
                <?= Html::label(
                    !$isCommunitySurvey ? AmosSondaggi::t('amossondaggi', 'Cerca utenti') : AmosSondaggi::t('amossondaggi', 'Cerca partecipanti della commmunity'),
                    'SondaggiUsersInvitations[users]',
                    ['class' => 'control-label']
                ); ?>
                <?php
                $dataUsers = [];
                $selectedUsers = [];
                if (!$model->isNewRecord) {
                    foreach ($model->search_users as $userId) {
                        $userProfile = UserProfile::find()->andWhere(['user_id' => $userId])->one();
                        $dataUsers[$userId] = $userProfile->nomeCognome;
                    }
                    $selectedUsers = $model->search_users;
                }
                ?>
                <?= Select2::widget([
                    'name' => 'SondaggiUsersInvitations[users]',
                    'data' => $dataUsers,
                    'value' => $selectedUsers,
                    'showToggleAll' => false,
                    'options' => [
                        'placeholder' => AmosSondaggi::t('amossondaggi', 'Ricerca per nome, cognome, email o codice fiscale...'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                        'minimumInputLength' => 3,
                        'closeOnSelect' => false,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/sondaggi/dashboard-invitations/search-users', 'id' => $model->id]),
                            'dataType' => 'json',
                            'data' => new \yii\web\JsExpression($jsResultsUsers),
                        ],
                    ],
                ]); ?>

                <?php
                if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
                    echo Html::label(!$isCommunitySurvey ? AmosSondaggi::t('amossondaggi', 'Utenti con i seguenti tag di interesse') : AmosSondaggi::t('amossondaggi', 'Partecipanti della community con i seguenti tag di interesse'),
                        'SondaggiInvitations[tagValues]',
                        ['class' => 'control-label m-t-30']
                    );
                    echo \open20\amos\tag\widgets\TagWidget::widget([
                        'model' => $model,
                        'attribute' => 'tagValues',
                        'form' => $form,
                        'isSearch' => true,
                        'hideHeader' => true,
                        'form_values' => $model->search_tags,
                        'enableAjax' => true
                    ]);
                }
                ?>
            </div>

            <div class="row d-flex m-b-30 m-t-30" style="align-items: flex-end">
                <?php
                $searchButtonCol = !$isCommunitySurvey ? 'col-xs-3' : 'col-xs-5';
                $loaderCol = !$isCommunitySurvey ? 'col-xs-9' : 'col-xs-7';
                ?>
                <div class="<?= $searchButtonCol ?>">
                    <?= Html::a(
                        !$isCommunitySurvey ? AmosSondaggi::t('amossondaggi', "Cerca fra gli utenti") : AmosSondaggi::t('amossondaggi', 'Cerca fra i partecipanti della community'),
                        [],
                        [
                            'id' => 'btn-search-users',
                            'class' => 'btn btn-sm btn-primary'
                        ]
                    ); ?>
                </div>
                <div class="<?= $loaderCol ?>">
                    <span class="loading-spinner" id="loader-users" style="display: none; width: 30px; height: 30px; border: 4px solid darkgrey; border-bottom-color: transparent"></span>
                </div>
            </div>

        </div>
    </div>


</div>
