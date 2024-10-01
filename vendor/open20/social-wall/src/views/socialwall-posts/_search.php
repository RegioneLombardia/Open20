<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views 
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var open20\socialwall\models\SocialwallPostsSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="socialwall-posts-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

<div class="col-md-8">
    <?=
    $form->field($model, 'searchPostTags')->widget(\xj\tagit\Tagit::classname(), [
        'clientOptions' => [
            'singleField' => true,
        ],
    ])
    ?>
</div>

 <!-- type -->
<div class="col-md-8">
<!--    --><?php //=
//$form->field($model, 'type')->textInput(['placeholder' => 'ricerca per type' ]) ?>
    <?=
    $form->field($model, 'type')->checkboxList(\open20\socialwall\utility\Social::getSocialsEnabled())->label(\open20\socialwall\Module::t('socialwall', "Social configurati"));
    ?>

 </div>

<!-- publication_date -->
<div class="col-md-8"> <?=

$form->field($model, 'fromPublicationDate')->widget(\kartik\widgets\DatePicker::class,
    [
        'pluginOptions' => [
            "endDate" => "0d",
            'format' => 'dd-mm-yyyy',
        ],
    ]
); ?>

 </div>

<!-- created_at -->  <?php // echo $form->field($model, 'created_at') ?>

 <!-- updated_at -->  <?php // echo $form->field($model, 'updated_at') ?>

 <!-- deleted_at -->  <?php // echo $form->field($model, 'deleted_at') ?>

 <!-- created_by -->  <?php // echo $form->field($model, 'created_by') ?>

 <!-- updated_by -->  <?php // echo $form->field($model, 'updated_by') ?>

 <!-- deleted_by -->  <?php // echo $form->field($model, 'deleted_by') ?>

     <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
