<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;
use open20\amos\community\AmosCommunity;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\CommunityUserField $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="community-user-field-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'community-user-field_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-xs-12"><h2 class="subtitle-form">Settings</h2>
            <div class="col-md-8 col xs-12">
                <?php if(!empty($model->community_id)) { ?>
                        <p><strong><?= AmosCommunity::t('amoscommunity', 'Community') ?></strong>: <?= $community->name?></p>
                        <?=  $form->field($model, 'community_id')->hiddenInput()->label(false);?>
               <?php  } else { ?>
                    <?= $form->field($model, 'community_id')->widget(Select::classname(), [
                        'data' => ArrayHelper::map(\open20\amos\community\models\Community::find()->asArray()->all(), 'id', 'name'),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'placeholder' => 'Seleziona ...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'pluginEvents' => [
                        ]
                    ])->label(AmosCommunity::t('amoscommunity', 'Community'))
                    ?>
                <?php  }  ?>
                <?= $form->field($model, 'user_field_type_id')->widget(Select::classname(), [
                'data' => ArrayHelper::map(\open20\amos\community\models\CommunityUserFieldType::find()->all(),'id','description'),
                'language' => substr(Yii::$app->language, 0, 2),
                'pluginOptions' => [
                    'allowClear' => true
                ],
                ])->label(AmosCommunity::t('amoscommunity', 'Tipo di campo'))
                ?><!-- name string -->
                <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(AmosCommunity::t('amoscommunity', 'Attributo')) ?><!-- description text -->
                <?= $form->field($model, 'description')->textInput(['rows' => 5])->label(AmosCommunity::t('amoscommunity', 'Nome'));?>
                <?= $form->field($model, 'required')->checkbox();?>
                <?= $form->field($model, 'unique')->checkbox();?>
                <?= $form->field($model, 'validator_classname')
                    ->textInput(['maxlength' => true])
                    ->label(AmosCommunity::t('amoscommunity', 'Validatore')) ?><!-- description text -->

                <!--                --><?php //echo  $form->field($model, 'tooltip')->textarea(['rows' => 5]);
                ?><!-- required integer -->

                <?php
                if(!$model->isNewRecord && $model->user_field_type_id == 4){
                    $urlRedirect  = \Yii::$app->request->get('urlRedirect');
                    echo Html::a(AmosCommunity::t('amoscommunity', 'Aggiungi elemento'), [
                        '/community/community-user-field-default-val/create',
                        'id' => $model->id,
                        'urlRedirect' => $urlRedirect
                    ], [
                        'class' => 'btn btn-navigation-primary'
                    ]);

                    echo \open20\amos\core\views\AmosGridView::widget([
                        'dataProvider' => $dataProviderDefaultVals,
                        'columns' => [
                            'value',
                            [
                                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function($url, $model)use ($urlRedirect){
                                        $url = '/community/community-user-field-default-val/delete?id='.$model->id;
                                        return Html::a(AmosIcons::show('delete'), $url.'&urlRedirect='.$urlRedirect, [
                                            'class' => 'btn btn-danger-inverse'
                                        ]);
                                    },
                                ]

                            ]
                        ]
                    ]);
                }
                ?>
                <?= RequiredFieldsTipWidget::widget(); ?>

                <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>

                <?php ActiveForm::end(); ?></div>
            <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>

    </div>
</div>
