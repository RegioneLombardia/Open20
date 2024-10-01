<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="news-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <p class="h3"><?= AmosNews::t('amosnews', 'Ordina per') ?>:</p>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
//    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    echo Html::hiddenInput("currentView", $queryParamCurrentView);
    ?>
    
    <div class="col-sm-6 col-lg-4">
        <?php 
            $orderAttributes = $model->getOrderAttributesLabels();
            
            $modulo = AmosNews::getModuleName();           
            $moduloObj = Yii::$app->getModule($modulo); 
            
            if($moduloObj->hasProperty('enableCustomOrderFields') && $moduloObj->enableCustomOrderFields){
            
                $defaultOrderField = $moduloObj->params['orderParams'][$modulo]['default_field'];
                $defaultOrderFieldLabel = isset($orderAttributes[$defaultOrderField]) ? $orderAttributes[$defaultOrderField] : Yii::t('amoscore','default-mancante');
                /*
                 * prendo tutti i campi del modulo a prescindere dal visibile perche
                 * inizialmente se nel db non è presenti nessuna riga mostro i campi di default.     
                 * 
                 */
                $aviableFields = \open20\amos\core\models\base\CustomOrderFields::find()->select(['colonna','visibile'])->where(['modulo'=>$modulo])->asArray()->all();

                if(!empty($aviableFields)){
                    /*
                    * se presenti i campi a db che sono sia visibili a 1 che a 0 
                    * considero solo quelli effettivamente a 1 per farli visualizzare
                    */
                    foreach($aviableFields as $row=>$col){
                        if($col['visibile'] == 0)
                            unset($aviableFields[$row]);
                    }                
                    $aviableFields = yii\helpers\ArrayHelper::map($aviableFields, 'colonna', 'visibile');             
                    $orderAttributes = array_intersect_key($orderAttributes,$aviableFields);

                    /**
                     * Se vengono tolti tutti viene preso quello di default dai params
                     */                
                    if(empty($orderAttributes)){
                        $orderAttributes = [$defaultOrderField => $defaultOrderFieldLabel];
                    }
                }
            }
            
        ?>
        <?= $form->field($model, 'orderAttribute')->dropDownList($orderAttributes) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderType')->dropDownList(
            [
                SORT_ASC => AmosNews::t('amosnews', 'Crescente'),
                SORT_DESC => AmosNews::t('amosnews', 'Decrescente'),
            ]
        )
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosNews::t('amosnews', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosNews::t('amosnews', 'Ordina'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>