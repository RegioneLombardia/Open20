<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use open20\amos\admin\AmosAdmin;
use kartik\select2\Select2;
use open20\amos\core\forms\editors\Select;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$moduleTag = Yii::$app->getModule('tag');


$newsModule = AmosNews::instance();

// enable open search section
$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

?>

<div class="news-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosNews::t('amosnews', 'Cerca per') ?>:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'id' => 'news_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]);

    echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));


    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'titolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'sottotitolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'descrizione') ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?php
        $creator = '';
        $userProfileCreator = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $model->created_by])->one();
        if(!empty($userProfileCreator)) {
            $creator = $userProfileCreator->getNomeCognome();
        }
        echo $form->field($model, 'created_by')->widget(Select2::className(), [
                'data' => (!empty($model->created_by) ? [$model->created_by => $creator] : []),
                'options' => ['placeholder' => AmosNews::t('amosnews', 'Cerca ...')],
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
        );
        ?>
    </div>

    <?php  if ($newsModule->enableAgid) : ?>

        <div class="col-sm-6 col-lg-4">
            <?= 
                $form->field($model, 'updated_by')->widget(Select::className(), [
                'data' => ArrayHelper::map(\open20\amos\admin\models\UserProfile::find()->andWhere(['deleted_at' => NULL])->all(), 'user_id', function($model) {
                    return $model->nome . " " . $model->cognome;
                }),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => AmosNews::t('amosnews', '#select_choose') . '...'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(AmosNews::t('amosnews', '#updated_by')); 
            ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <?= 
                $form->field($model, 'status')->widget(Select::className(), [
                    'data' => $model->getAllWorkflowStatus(),

                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => AmosNews::t('amosnews', '#select_choose') . '...',
                        'value' => $model->status = \Yii::$app->request->get(end(explode("\\", $model::className())))['status']
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); 
            ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <?= 
                $form->field($model, 'news_categorie_id')->widget(Select::className(), [
                    'data' => ArrayHelper::map(\open20\amos\news\models\NewsCategorie::find()
                        ->andWhere(['deleted_at' => NULL])->all(), 'id', 'titolo') ,

                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => AmosNews::t('amosnews', '#select_choose') . '...',
                        'value' => $model->status = \Yii::$app->request->get(end(explode("\\", $model::className())))['status']
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); 
            ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <!-- Aggiornato il (range da -> a) -->
        </div>
        
    <?php endif; ?>

    

    <!--div class="col-sm-6 col-lg-4">
        < ?= $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ]) ?>
    </div-->
    <?php if (isset($moduleTag) && in_array(News::className(), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
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
            <?= Html::a(AmosNews::t('amosnews', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton(AmosNews::tHtml('amosnews', 'Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->

    <?php ActiveForm::end(); ?>

</div>
