<?php

use kartik\select2\Select2;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\sondaggi\models\SondaggiInvitations $model
 * @var $count
 */

$fieldData = [
    'age_group' => AmosSondaggi::t('amossondaggi', 'Fascia d\'età'),
    'gender' => AmosSondaggi::t('amossondaggi', 'Sesso')
];
if (class_exists('open20\amos\admin\models\UserProfileClasses')) {
    $profileClass = ['profile_class' => AmosSondaggi::t('amossondaggi', 'Profilo')];
    $fieldData = array_merge($fieldData, $profileClass);
}

//todo
//if ($count == 0) {
//    $model->include_exclude = null;
//    echo $form->field($model, 'include_exclude')->hiddenInput()->label(false);
//    $model->field = null;
//    echo $form->field($model, 'field')->hiddenInput()->label(false);
//    $model->value = null;
//    echo $form->field($model, 'value')->hiddenInput()->label(false);
//}
?>

<?php for ($i = 1; $i <= $count; $i++) {
    if ($model->isNewRecord) {
        $ruleValue = SondaggiInvitationsSearch::FILTER_INCLUDE;
    }
    if (isset($ajaxAttributes['include_exclude'][$i])) {
        $ruleValue = $ajaxAttributes['include_exclude'][$i];
    }
    ?>
    <div class="m-t-20">

        <div id="row-search-<?= $i ?>-users" class="row d-flex flex-wrap align-item-center form-group">

            <div class="col-xs-12 col-sm-12 col-md-3">
                <?= Html::label(
                    AmosSondaggi::t('amossondaggi', '#rule'),
                    'SondaggiUsersInvitations[include_exclude][' . $i . ']',
                    ['class' => 'control-label']
                ); ?>
                <?= Select2::widget([
                    'name' => 'SondaggiUsersInvitations[include_exclude][' . $i . ']',
                    'hideSearch' => true,
                    'value' => $ruleValue,
                    'data' => [
                        SondaggiInvitationsSearch::FILTER_INCLUDE => AmosSondaggi::t('amossondaggi', '#invite'),
                        SondaggiInvitationsSearch::FILTER_EXCLUDE => AmosSondaggi::t('amossondaggi', "#no_invite")
                    ],
                    'options' => [
                        'placeholder' => AmosSondaggi::t('AmosSondaggi', "Select..."),
                        'id' => 'rule-' . $i . '-users'
                    ]
                ]); ?>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4">
                <?php
                if ($model->isNewRecord) {
                    $fieldValue[$i] = null;
                }
                if (isset($ajaxAttributes['field'][$i])) {
                    $fieldValue[$i] = $ajaxAttributes['field'][$i];
                }
                ?>
                <?= Html::label(
                    AmosSondaggi::t('amossondaggi', '#field'),
                    'SondaggiUsersInvitations[field][' . $i . ']',
                    ['class' => 'control-label']
                ); ?>
                <?= Select2::widget([
                    'name' => 'SondaggiUsersInvitations[field][' . $i . ']',
                    'hideSearch' => true,
                    'data' => $fieldData,
                    'value' => $fieldValue[$i],
                    'options' => [
                        'class' => 'filter-field-users',
                        'id' => 'field-' . $i . '-users',
                        'placeholder' => AmosSondaggi::t('amossondaggi', "#select")
                    ]
                ]); ?>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4">
                <?php
                $arrayData = !empty($fieldValue[$i]) ? SondaggiInvitationsSearch::getAttributesValues('users', $fieldValue[$i])  : null;
                if (!is_null($arrayData)) {
                    foreach ($arrayData as $item) {
                        $data[$item['id']] = $item['name'];
                    }
                } else {
                    $data = null;
                }
                if ($model->isNewRecord) {
                    $valueValue = null;
                }
                if (isset($ajaxAttributes['value'][$i][$i])) {
                    $valueValue = $ajaxAttributes['value'][$i][$i];
                }
                ?>
                <?= Html::label(
                    AmosSondaggi::t('amossondaggi', '#value'),
                    'SondaggiUsersInvitations[value][' . $i . ']',
                    ['class' => 'control-label']
                ); ?>
                <?= \kartik\depdrop\DepDrop::widget([
                    'name' => 'SondaggiUsersInvitations[value][' . $i . ']',
                    'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
                    'data' => $data,
                    'value' => $valueValue,
                    'options' => [
                        'class' => 'filter-value-users',
                        'id' => 'value-' . $i . '-users',
                        //'multiple' => true
                    ],
                    'pluginOptions' => [
                        'url' => \yii\helpers\Url::to(['/sondaggi/dashboard-invitations/get-values', 'target' => 'users']),
                        'depends' => ['field-' . $i . '-users'],
                        'placeholder' => AmosSondaggi::t('amossondaggi', "#select"),
                    ],
                    'select2Options' => [
                        'pluginOptions' => [
                            'allowClear' => false,
                        ],
                    ]
                ]); ?>
            </div>

            <div class="col-xs-2 col-sm-2 col-md-1 text-right m-t-15">
                <?= \yii\helpers\Html::a(Amosicons::show('delete'), '', [
                    'class' => 'btn btn-danger-inverse m-t-15',
                    'id' => 'btn-remove-rule-users',
                    'data' => $i
                ]) ?>
            </div>

        </div>

    </div>
<?php } ?>