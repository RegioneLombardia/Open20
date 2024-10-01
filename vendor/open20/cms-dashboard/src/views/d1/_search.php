<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\cms\dashboard\Module;
use open20\amos\core\helpers\Html;
use kartik\widgets\Select2;
use open20\amos\core\forms\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\search\CommunitySearch $model
 * @var yii\widgets\ActiveForm $form
 */
$enableAutoOpenSearchPanel = false;
?>
<div class="pagine-search element-to-toggle" data-toggle-element="form-search">

    <?php
    $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['pagine']),
        'method' => 'get',
        'id' => 'search-id_page',
    ]);

    echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
    ?>

    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'search')->textInput()->label(Module::txt('Titolo della pagina'))->hint(Module::txt('Inserisci il titolo della pagina da ricercare')); ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'status')->widget(Select2::className(), [
                'data' => [
                    1 => Module::txt('Bozza'),
                    2 => Module::txt('In richiesta di pubblicazione'),
                    3 => Module::txt('Pubblicato'),
                ],
                'options' => ['placeholder' => Module::txt('Seleziona...')]
            ])->label(Module::txt('Stato')) ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'type')->widget(Select2::className(), [
                'data' => \open20\cms\dashboard\utilities\Utility::getTipologie(),
                'options' => ['placeholder' => Module::txt('Seleziona...')]
            ])->label(Module::txt('Tipologia')) ?>
        </div>
        <div class="col-xs-4">
        <?php
        $creator = '';
        $updated_by = $model->updated_by;
        if (!empty($updated_by)) {
            $user = \luya\admin\models\User::findOne($updated_by);
            if($user){
                $creator =  $user->firstname. ' '.$user->lastname;
            }
        }
        echo $form->field($model, 'updated_by')->widget(Select2::className(), [
                'data' => (!empty($model->updated_by) ? [$model->updated_by => $creator] : []),
                'options' => ['placeholder' => \Yii::t('amosnews', 'Cerca ...')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['/dashboards/d1/ajax-user-list']),
                        'dataType' => 'json',
                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
            ]
        )->label(Module::txt('Aggiornato da'));

        echo \yii\helpers\Html::hiddenInput('container', \Yii::$app->request->get('container'));
        ?>

        </div>
        <div class="col-xs-12">
            <div class="pull-right">
                <?=
                Html::a(
                    Module::t('amospages', 'Annulla'),
                    [isset($cancelAction) ? $cancelAction : Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                    ['class' => 'btn btn-secondary', 'title' => Module::t('amospages', '#cancel_search')]
                )
                ?>
                <?= Html::submitButton(Module::t('amospages', 'Cerca'), ['class' => 'btn btn-navigation-primary', 'title' => Module::t('amospages', 'Cerca tra le pagine')]) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();
    ?>
</div>