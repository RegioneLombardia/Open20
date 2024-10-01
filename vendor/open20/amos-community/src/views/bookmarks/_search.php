<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\search\BookmarksSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$community_id = Yii::$app->request->getQueryParam('id');
?>
<div class="bookmarks-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index', 'id' => $community_id],
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- titolo -->
    <div class="col-md-4"> <?=
        $form->field($model, 'titolo')->textInput(['placeholder' => 'ricerca per titolo']) ?>
    </div>
    <!-- PArametri di ordinamento-->
    <?php
    echo Html::hiddenInput("BookmarksSearch[orderAttribute]", Yii::$app->request->getQueryParam('BookmarksSearch')['orderAttribute']);
    echo Html::hiddenInput("BookmarksSearch[orderType]", Yii::$app->request->getQueryParam('BookmarksSearch')['orderType']);
    ?>
<div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Yii::t('amoscore', 'Reset'), ['index', 'id' => $community_id], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
