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

$module = \Yii::$app->getModule('partnershipprofiles');
$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]);

$customView = Yii::$app->getViewPath() . '/imageField.php';
?>

<div class="partnershipprofiles-category-form">
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <div>
                <?= $form->field($model, 'categoryIcon')->widget(AttachmentsInput::classname(), [
                    'options' => [ // Options of the Kartik's FileInput widget
                        'multiple' => false, // If you want to allow multiple upload, default to false
                        'accept' => "image/*"
                    ],
                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                        'maxFileCount' => 1,
                        'showRemove' => false, // Client max files,
                        'indicatorNew' => false,
                        'allowedPreviewTypes' => ['image'],
                        'previewFileIconSettings' => false,
                        'overwriteInitial' => false,
                        'layoutTemplates' => false
                    ]
                ]) ?>
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
                        <label class="control-label m-b-15">Risultato:
                            <small>(salva per vedere)</small>
                        </label><br>
                        <span class="card-category text-uppercase"
                              style="padding:3px; background: <?= $model->color_background ?>">
                <strong style="color:<?= $model->color_text ?>"><?= $model->color_text ?></strong>
                </span>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">

            <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'description')->widget(
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

    <?php
    $whiteRoles = $module->whiteListRolesCategories;
    $roles =  array_combine($whiteRoles, $whiteRoles);
    ?>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'profilesCategoryRoles')->widget(\kartik\select2\Select2::className(), [
                'data' => $roles,
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['multiple' => true, 'placeholder' => AmosNews::t('amosnews', 'Select...')]
            ])->label(AmosNews::t('amosnews', 'Roles')) ?>
        </div>

    </div>


    <div class="clearfix"></div>


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
