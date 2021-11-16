<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\editors\Select;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\admin\AmosAdmin;
use open20\amos\tag\AmosTag;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\admin\models\UserProfile;
/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\search\DocumentiSearch $model
 * @var yii\widgets\ActiveForm $form
 */

/** @var AmosTag $moduleTag */
$moduleTag = Yii::$app->getModule('tag');

/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();

/** @var DocumentiCategorie $documentiCategorieModel */
$documentiCategorieModel = $documentsModule->createModel('DocumentiCategorie');

/** @var DocumentiController $controller */
$controller = Yii::$app->controller;

$hidePubblicationDate = $controller->documentsModule->hidePubblicationDate;

$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

$documentiModule = AmosDocumenti::instance();
?>

<div class="documenti-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><p class="h3"><?= AmosDocumenti::tHtml('amosdocumenti', 'Cerca per') ?>:</p></div>

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
    ]);

    echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));

    if (!is_null(Yii::$app->request->getQueryParam('parentId'))) {
        echo Html::hiddenInput('parentId', Yii::$app->request->getQueryParam('parentId'));
    }
    ?>

    <?= $form->field($model, 'parent_id')->hiddenInput(['value' => Yii::$app->request->getQueryParam('parentId')])->label(false) ?>
    
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'titolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'sottotitolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?=$form->field($model, 'descrizione_breve')->label(AmosDocumenti::t('amosdocumenti', 'descrizione_breve'))?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?=$form->field($model, 'extended_description')->label(AmosDocumenti::t('amosdocumenti', 'extended_description'))?>
    </div>


    <?php if($documentsModule->enableAgid) : ?>

        <div class="col-sm-6 col-lg-4">
            <?=
                $form->field($model, 'agid_organizational_unit_content_type_office_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::find()->orderBy('name')
                        ->asArray()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'agid_organizational_unit_content_type_office_id',
                        'multiple' => false,
                        'placeholder' => 'Seleziona ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(AmosDocumenti::t('amosdocumenti', 'agid_organizational_unit_content_type_office_id'));
            ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <?=
                $form->field($model, 'agid_organizational_unit_content_type_area_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::find()->orderBy('name')
                        /*->andWhere(['agid_organizational_unit_content_type_id' => 
                            AgidOrganizationalUnitContentType::find()->select('id')->andWhere(['like', 'name', 'Aree amministrative'])->one()->id
                        ])->andWhere(['deleted_at' =>  null])*/->asArray()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'agid_organizational_unit_content_type_area_id',
                        'multiple' => false,
                        'placeholder' => 'Seleziona ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(AmosDocumenti::t('amosdocumenti', 'agid_organizational_unit_content_type_area_id'));
            ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <?=
                $form->field($model, 'documenti_agid_content_type_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(\open20\amos\documenti\models\base\DocumentiAgidContentType::findRedactor()->asArray()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'documenti_agid_content_type_id',
                        'multiple' => false,
                        'placeholder' => 'Seleziona ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(AmosDocumenti::t('amosdocumenti', 'documenti_agid_content_type_id'));
            ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <?=
                $form->field($model, 'documenti_agid_type_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(\open20\amos\documenti\models\base\DocumentiAgidType::find()->asArray()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'documenti_agid_type_id',
                        'multiple' => false,
                        'placeholder' => 'Seleziona ...',
                        "value" => !empty($model) ? $model->documenti_agid_type_id : null
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(AmosDocumenti::t('amosdocumenti', 'documenti_agid_type_id'));
            ?>
        </div>

    <?php endif; ?>


    <div class="col-sm-6 col-lg-4">
        <?= 
            $form->field($model, 'updated_by')->widget(Select::className(), [
                'data' => ArrayHelper::map(UserProfile::find()->andWhere(['deleted_at' => NULL])->all(), 'user_id', function($model) {
                    return $model->nome . " " . $model->cognome;
                }),
                'language' => substr(Yii::$app->language, 0, 2),
                'options' => [
                    'multiple' => false,
                    'placeholder' => 'Seleziona ...',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(AmosDocumenti::t('amosdocumenti', 'updated_by')); 
        ?>
    </div>

    <?php if($documentsModule->enableAgid) : ?>

        <div class="col-sm-6 col-lg-4">
            <?= 
                $form->field($model, 'updated_from')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'value' => $model->updated_from = \Yii::$app->request->get(end(explode("\\", $model::className())))['updated_from'],
                ])->label(AmosDocumenti::t('amosdocumenti', '#updated_from')); 
            ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <?= 
                $form->field($model, 'updated_to')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'value' => $model->updated_to = \Yii::$app->request->get(end(explode("\\", $model::className())))['updated_to'],
                ])->label(AmosDocumenti::t('amosdocumenti', '#updated_to')); 
            ?>
        </div>

    <?php endif; ?>

    <div class="col-sm-6 col-lg-4">
        <?= 
            $form->field($model, 'status')->widget(Select::className(), [
                'data' => $model->getAllWorkflowStatus(),

                'language' => substr(Yii::$app->language, 0, 2),
                'options' => [
                    'multiple' => false,
                    'placeholder' => 'Seleziona ...',
                    'value' => $model->status = \Yii::$app->request->get(end(explode("\\", $model::className())))['status']
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); 
        ?>
    </div>

    <?php if( !$documentiModule->enableAgid ) : ?>
        <div class="col-sm-6 col-lg-4">
            <?= $form->field($model, 'descrizione') ?>
        </div>
    <?php endif; ?>

    <?php if (!$hidePubblicationDate) { ?>
        <div class="col-sm-6 col-lg-4">
            <?= $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ]) ?>
        </div>
        <div class="col-sm-6 col-lg-4">
            <?= $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ]) ?>
        </div>
    <?php } ?>
    <?php if (!isset(\Yii::$app->params['hideListsContentCreatorName']) || (\Yii::$app->params['hideListsContentCreatorName'] === false)): ?>
        <div class="col-sm-6 col-lg-4">
            <?php 
                $user_profile = UserProfile::find()->andWhere(['user_id' => $model->created_by])->one();
            ?>
            <?= $form->field($model, 'created_by')->widget(Select2::className(), [
                    // 'data' => (!empty($model->created_by) ? [$model->created_by => \open20\amos\admin\models\UserProfile::findOne($model->created_by)->getNomeCognome()] : []),
                    'data' => ( (null != $user_profile) ? [$model->created_by => $user_profile->getNomeCognome()] : [] ),
                    'options' => ['placeholder' => AmosDocumenti::t('amosdocumenti', 'Cerca ...')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/'.AmosAdmin::getModuleName().'/user-profile-ajax/ajax-user-list']),
                            'dataType' => 'json',
                            'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                ]
            ); ?>
        </div>
    <?php endif; ?>
    <?php if ($controller->documentsModule->enableCategories): ?>
        <div class="col-sm-6 col-lg-4">
            <?= $form->field($model, 'documenti_categorie_id')->widget(Select::className(), [
                'data' => ArrayHelper::map($documentiCategorieModel::find()->all(), 'id', 'titolo'),
                'language' => substr(Yii::$app->language, 0, 2),
                'options' => [
                    'multiple' => true,
                    'placeholder' => AmosDocumenti::t('amosdocumenti', 'Search by document category'),
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($moduleTag) && in_array($documentsModule->model('Documenti'), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
        <div class="col-xs-12">
            <?php
            $params = \Yii::$app->request->getQueryParams();
            /*echo \open20\amos\tag\widgets\TagWidget::widget([
                'model' => $model,
                'attribute' => 'tagValues',
                'form' => $form,
                'isSearch' => true,
                'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : []
            ]);*/
            ?>
        </div>
    <?php endif; ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosDocumenti::t('amosdocumenti', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton(AmosDocumenti::tHtml('amosdocumenti', 'Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>

</div>
