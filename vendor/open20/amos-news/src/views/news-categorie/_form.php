<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news-categorie
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\CropInput;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\news\AmosNews;
use yii\bootstrap\Tabs;
use open20\amos\core\forms\TextEditorWidget;
use kartik\color\ColorInput;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\NewsCategorie $model
 * @var yii\widgets\ActiveForm $form
 */

$module = \Yii::$app->getModule('news');
$enableCategoriesForCommunity = $module->enableCategoriesForCommunity;
$filterCategoriesByRole = $module->filterCategoriesByRole;

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]);

$customView = Yii::$app->getViewPath() . '/imageField.php';

$hidePreviewDeleteButton = false;
$newsImageLabel = AmosNews::t('amosnews', '#image_field_required');
?>

<div class="news-categorie-form">
    <?php $this->beginBlock('dettagli'); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'sottotitolo')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <div>
                <?= $form->field($model, 'categoryIcon')->widget(CropInput::class, [
                    'hidePreviewDeleteButton' => $hidePreviewDeleteButton,
                    'jcropOptions' => ['aspectRatio' => '1.7']
                ])->label($newsImageLabel)->hint(AmosNews::t('amosnews', '#image_category_field_hint'))
                ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'color_background')->widget(ColorInput::classname(), [
                        'options' => ['placeholder' => 'Select color ...', 'readonly' => true],
                        'pluginOptions' => [
                            'showInput' => false,
                            'allowEmpty' => true,
                        ]
                    ]); ?>
                </div>
                <div class="col-md-6">
                <div class="form form-group">
                <label class="control-label m-b-15">Risultato: <small>(salva per vedere)</small></label><br>
                <span class="card-category text-uppercase" style="padding:3px; background: <?= $model->color_background ?>">
                <strong style="color:<?= $model->color_text ?>"><?= $model->color_text ?></strong>
                </span>
                
                    
                </div>
                    
                </div>


            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">

            <?= $form->field($model, 'descrizione_breve')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'descrizione')->widget(
                TextEditorWidget::className(),
                [
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'plugins' => [
                            "paste link",
                        ],
                        'toolbar' => "undo redo | link",
                    ],
                ]
            ) ?>
        </div>
    </div>


    <?php if ($filterCategoriesByRole) {

        $whiteRoles = $module->whiteListRolesCategories;
        $roles =  array_combine($whiteRoles, $whiteRoles);
    ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'newsCategoryRoles')->widget(\kartik\select2\Select2::className(), [
                    'data' => $roles,
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['multiple' => true, 'placeholder' => AmosNews::t('amosnews', 'Select...')]
                ])->label(AmosNews::t('amosnews', 'Roles')) ?>
            </div>

        </div>
    <?php  } ?>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'notify_category')->checkbox() ?>
        </div>
    </div>
    <?php if ($enableCategoriesForCommunity) { ?>
        <hr>
        <h3><?= AmosNews::t('amosnews', 'Configuration for community') ?></h3>
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'newsCategoryCommunities')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\open20\amos\community\models\Community::find()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['multiple' => true, 'placeholder' => AmosNews::t('amosnews', 'Select...')]
                ])->label(AmosNews::t('amosnews', 'Community')) ?>
            </div>
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'visibleToCommunityRole')->widget(\kartik\select2\Select2::className(), [
                    'data' => [
                        'COMMUNITY_MANAGER' => AmosNews::t('amosnews', 'Community manager'),
                        'PARTICIPANT' => AmosNews::t('amosnews', 'Participant'),
                    ],
                    'options' =>  [
                        'placeholder' => 'Select...',
                        'multiple' => true

                    ]
                ])->label(AmosNews::t('amosnews', 'Visible to Community roles')); ?>
            </div>
        </div>
        <!--        <div class="row">-->
        <!--            <div class="col-lg-12 col-sm-12">-->
        <!--                --><?php //echo $form->field($model, 'publish_only_on_community')->checkbox()->label(AmosNews::t('amosnews', 'Publish Only On Community')) 
                                ?>
        <!--            </div>-->
        <!--        </div>-->
    <?php } ?>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php
    $itemsTab[] = [
        'label' => AmosNews::t('amosnews', 'Dettagli '),
        'content' => $this->blocks['dettagli'],
    ];
    ?>

    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]);
    ?>
    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?php
    $config = [
        'model' => $model
    ];
    ?>
    <?= CloseSaveButtonWidget::widget($config); ?>
</div>
<?php ActiveForm::end(); ?>
