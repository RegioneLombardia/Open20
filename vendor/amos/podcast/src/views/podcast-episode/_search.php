<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/amos/podcast/src/views 
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var amos\podcast\models\search\PodcastEpisodeSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="podcast-episode-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

 <!-- podcast_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'podcast_id')->textInput(['placeholder' => 'ricerca per podcast id' ]) ?>

 </div> 


                <div class="col-md-4">
                    <?= 
                    $form->field($model, 'podcast')->textInput(['placeholder' => 'ricerca per '])->label('');
                     ?> 
                </div>
                <!-- status -->
<div class="col-md-4"> <?= 
$form->field($model, 'status')->textInput(['placeholder' => 'ricerca per status' ]) ?>

 </div> 

<!-- title -->
<div class="col-md-4"> <?= 
$form->field($model, 'title')->textInput(['placeholder' => 'ricerca per title' ]) ?>

 </div> 

<!-- image_description -->
<div class="col-md-4"> <?= 
$form->field($model, 'image_description')->textInput(['placeholder' => 'ricerca per image description' ]) ?>

 </div> 

<!-- abstract -->
<div class="col-md-4"> <?= 
$form->field($model, 'abstract')->textInput(['placeholder' => 'ricerca per abstract' ]) ?>

 </div> 

<!-- description -->
<div class="col-md-4"> <?= 
$form->field($model, 'description')->textInput(['placeholder' => 'ricerca per description' ]) ?>

 </div> 

<!-- main_player_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'main_player_id')->textInput(['placeholder' => 'ricerca per main player id' ]) ?>

 </div> 

<!-- url_spreaker -->
<div class="col-md-4"> <?= 
$form->field($model, 'url_spreaker')->textInput(['placeholder' => 'ricerca per url spreaker' ]) ?>

 </div> 

<!-- url_soundhound -->
<div class="col-md-4"> <?= 
$form->field($model, 'url_soundhound')->textInput(['placeholder' => 'ricerca per url soundhound' ]) ?>

 </div> 

<!-- url_apple_podcast -->
<div class="col-md-4"> <?= 
$form->field($model, 'url_apple_podcast')->textInput(['placeholder' => 'ricerca per url apple podcast' ]) ?>

 </div> 

<!-- url_spotify -->
<div class="col-md-4"> <?= 
$form->field($model, 'url_spotify')->textInput(['placeholder' => 'ricerca per url spotify' ]) ?>

 </div> 

<!-- url_google_podcast -->
<div class="col-md-4"> <?= 
$form->field($model, 'url_google_podcast')->textInput(['placeholder' => 'ricerca per url google podcast' ]) ?>

 </div> 

<!-- published_at -->
<div class="col-md-4"> <?= 
$form->field($model, 'published_at')->textInput(['placeholder' => 'ricerca per published at' ]) ?>

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
