<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\views\comment
 * @category   CategoryName
 */

use open20\amos\comments\AmosComments;
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\comments\models\search\CommentSearch $model
 * @var \yii\widgets\ActiveForm $form
 */

?>
<div class="comment-search element-to-toggle" data-toggle-element="form-search">
    
    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'comment_text')->textInput(['placeholder' => AmosComments::t('amoscomments', 'Search by comment text')]) ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosComments::t('amoscomments', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosComments::t('amoscomments', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Advanced search<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>
</div>
