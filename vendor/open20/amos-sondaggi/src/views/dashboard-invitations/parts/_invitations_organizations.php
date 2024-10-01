<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use open20\amos\sondaggi\models\SondaggiInvitations;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


$moduleSondaggi = \Yii::$app->getModule('sondaggi');
$showHideOrganizationsContainer = '';
if ($moduleSondaggi->enableInvitationsForPlatformUsers && $moduleSondaggi->enableInvitationsForOrganizations) {
    $showHideOrganizationsContainer = 'display: none;';
}
if ($model->target == SondaggiInvitations::TARGET_ORGANIZATIONS) {
    $showHideOrganizationsContainer = '';
}
$targetOrganizations = SondaggiInvitations::TARGET_ORGANIZATIONS;


$js = <<<JS
    function realoadSearch(container, plus){
           $.pjax.reload({
                container: container,
                timeout: 20000,
               replace: false,
                url: '/sondaggi/dashboard-invitations/render-search-ajax-organizations',
                data: {
                    'data': $('#form-search').serialize(),
                    'plus': plus,
                    'target': '{$targetOrganizations}'
                    },
                method: 'post'
            }).done(function() {
                $('#filter-value').change();
            });
    }

    $(document).on('click','#btn-add-rule-organizations',function(e){
        e.preventDefault();
        realoadSearch('#search-container-organizations', 1);
    });

    $(document).on('click', '#btn-remove-rule-organizations',function(e){
        e.preventDefault();
        var value = $(this).attr('data');
        $('#row-search-'+value+'-organizations').remove();
        realoadSearch('#search-container-organizations', 0);
    });

    // function search
     $('#btn-search-organizations').click(function(e){
         e.preventDefault();
         $('#form-results').hide();
         $('#loader-organizations').show();
           $.ajax({
               url: '/sondaggi/dashboard-invitations/search-invited',
               type: 'post',
               data: {
                    'data': $('#form-search').serialize(),
                    'target': '{$targetOrganizations}'
               },
               success: function (data) {
                   $('#loader-organizations').hide();
                   $('#result-search-container').html(data);
                   $('#form-results').show();
               }
           });
     });

     $('#type-organizations input[name="SondaggiInvitations[type]"]').click(function(){
          var value = $("#type-organizations input[name='SondaggiInvitations[type]']:checked").val();
          if(value == 1){
              $('#search-tags-organizations').show();
          }
          else {
              $('#search-tags-organizations').hide();
          }
     });

     $('#filter-type-organizations input[name="SondaggiInvitations[filter_type]"]').click(function(){
           var value = $("#filter-type-organizations input[name='SondaggiInvitations[filter_type]']:checked").val();
           if(value == 0){
               $('#filter-groups-organizations').show();
               $('#filter-tags-organizations').hide();
           }
           else {
               $('#filter-groups-organizations').hide();
               $('#filter-tags-organizations').show();
           }
      });
JS;

$this->registerJs($js);

$jsResultsTagsOrganizations = <<<JS
    function(params) {
        return {
            q:params.term, 
            id_sondaggio: '{$model->sondaggi->id}'
        };
    }
JS;


?>
<div class="invitations-organizations-container" style="<?= $showHideOrganizationsContainer ?>">

    <div class="p-t-20 p-b-20 d-flex title-substeps affix-top">
<!--        < ?= Amosicons::show('building', ['class' => 'am-lg m-t-5 m-r-10'], 'dash') ?>-->
        <h5 class="font-weight-bold ">
            <?= AmosSondaggi::t('amossondaggi', "#search_organizations_invitation") ?>
        </h5>
    </div>


    <div class="row variable-gutters">
        <div class="col-md-12">
            <?=$form->field($model, 'type', ['options' => ['id' => 'type-organizations']])->radioList([
                SondaggiInvitationsSearch::SEARCH_ALL => AmosSondaggi::t('amossondaggi', '#all_organizations'),
                SondaggiInvitationsSearch::SEARCH_FILTER => AmosSondaggi::t('amossondaggi', '#by_filter')
            ])->label(false);
            ?>

            <?php
            $displayNone = 'display:none';
            if ($model->type == SondaggiInvitationsSearch::SEARCH_FILTER) {
                $displayNone = '';
            }
            ?>
            <div id="search-tags-organizations" style="<?= $displayNone ?>">
                <div>
                    <?php
                    $filterTypes = [];
                    if (!empty($filters)) {
                        if ($filters['groups']) {
                            $filterTypes = ArrayHelper::merge($filterTypes, [
                                SondaggiInvitationsSearch::FILTER_GROUPS => AmosSondaggi::t('amossondaggi', '#organization_groups')
                            ]);
                        }
                        if ($filters['invited_tag']) {
                            $filterTypes = ArrayHelper::merge($filterTypes, [
                                SondaggiInvitationsSearch::FILTER_INVITED_TAG => AmosSondaggi::t('amossondaggi', '#polls_invited_to_tags')
                            ]);
                        }
                        if ($filters['compiled_tag']) {
                            $filterTypes = ArrayHelper::merge($filterTypes, [
                                SondaggiInvitationsSearch::FILTER_COMPILED_TAG => AmosSondaggi::t('amossondaggi', '#polls_compiled_to_tags')
                            ]);
                        }
                    }
                    ?>

                    <?= $form->field($model, 'filter_type', ['options' => ['id' => 'filter-type-organizations']])->radioList($filterTypes)->label(false) ?>

                    <?php
                    // Rendering group search...
                    $displayNone = 'display:none';
                    if ($model->filter_type == SondaggiInvitationsSearch::FILTER_GROUPS) {
                        $displayNone = '';
                    }
                    $groupsList = $model->getGroups()->all();
                    $groups = ArrayHelper::map($groupsList, 'id', 'name');
                    ?>
                    <span id="filter-groups-organizations" style="<?=$displayNone?>">
                                <?= $form->field($model, 'search_groups')->widget(
                                    Select2::className(),
                                    [
                                        'data' => $groups,
                                        'options' => [
                                            'multiple' => true,
                                            'placeholder' => AmosSondaggi::t('amossondaggi', '#search_groups'),
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'minimumInputLength' => 1,
                                            'ajax' => [
                                                'url' => \yii\helpers\Url::to(['/sondaggi/dashboard-invitations/group-list', 'id' => $model->id]),
                                                'dataType' => 'json',
                                                'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                                            ],
                                        ],
                                    ]
                                )->label(AmosSondaggi::t('amossondaggi', "#poll_group")); ?>
                            </span>
                    <?php
                    // Rendering tag search...
                    $displayNone = 'display:none';
                    if ($model->filter_type == SondaggiInvitationsSearch::FILTER_INVITED_TAG || $model->filter_type == SondaggiInvitationsSearch::FILTER_COMPILED_TAG) {
                        $displayNone = '';
                    }
                    $search_tags = [];
                    foreach (SondaggiInvitationsSearch::getPollTags()->andWhere(['id' => $model->search_tags])->all() as $tag){
                        $search_tags[$tag->id] =  $tag->nome;
                    }
                    ?>
                    <span id="filter-tags-organizations" style="<?=$displayNone?>">
                                <?= $form->field($model, 'search_tags')->widget(
                                    Select2::className(),
                                    [
                                        'data' => (!empty($model->search_tags) ? $search_tags : []),
                                        'options' => [
                                            'multiple' => true,
                                            'placeholder' => AmosSondaggi::t('amossondaggi', '#search_tags'),
                                        ],
                                        'pluginOptions' => [
                                            'closeOnSelect' => false,
                                            'multiple' => true,
                                            'allowClear' => true,
                                            'minimumInputLength' => 1,
                                            'ajax' => [
                                                'url' => \yii\helpers\Url::to(['/sondaggi/dashboard-invitations/tag-list', 'id' => $model->id]),
                                                'dataType' => 'json',
                                                'data' => new \yii\web\JsExpression($jsResultsTagsOrganizations)
                                            ],
                                        ],
                                    ]
                                )->label(AmosSondaggi::t('amossondaggi', "#poll_tag")); ?>
                            </span>
                </div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id' => 'search-container-organizations', 'timeout' => 2000]);

            $count = 1;
            if (!empty($model['field']) && count($model['field']) > 0) {
                $count = count($model['field']);
            }
            ?>
            <?= $this->render('@vendor/open20/amos-sondaggi/src/views/dashboard-invitations/parts/_search_params_organizations', ['model' => $model, 'form' => $form, 'count' => $count]) ?>
            <?php \yii\widgets\Pjax::end(); ?>

            <div class="m-b-30">
                <?= Html::a(AmosSondaggi::t('amossondaggi', "#add_rule"), [], ['id' => 'btn-add-rule-organizations']) ?>
            </div>

            <div class="row d-flex m-b-30" style="align-items: flex-end">
                <div class="col-xs-4">
                    <?= Html::a(AmosSondaggi::t('amossondaggi', "#search_organizations"), [], ['id' => 'btn-search-organizations', 'class' => 'btn btn-sm btn-primary']) ?>
                </div>
                <div class="col-xs-9">
                    <span class="loading-spinner" id="loader-organizations" style="display: none; width: 30px; height: 30px; border: 4px solid darkgrey; border-bottom-color: transparent"></span>
                </div>
            </div>
        </div>
    </div>
</div>