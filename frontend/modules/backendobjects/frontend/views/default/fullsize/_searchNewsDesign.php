<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */
use Yii;
use open20\amos\news\AmosNews;
use open20\amos\news\utility\NewsUtility;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
$classKeyworkDiv = 'col-lg-10';
if ($showCategories) {
    $queryCategorie = NewsUtility::getNewsCategories()->orderBy('titolo')->all();
    $categorie = ArrayHelper::merge(['' => Yii::t('app', 'Tutte le categorie')], ArrayHelper::map($queryCategorie, 'id', 'titolo'));
    $classKeyworkDiv = 'col-lg-5';
}

$currentUrl = str_replace('/default/index', '', strtok(Url::current(), '?'));
?>

<?php
if ((strpos(\Yii::$app->request->getPathInfo(), 'cms-page-preview') !== false) && !empty(\Yii::$app->request->get('itemId')) && !empty(\Yii::$app->request->get('version'))) {
    $urlForm = '/cms-page-preview?itemId=' . \Yii::$app->request->get('itemId') . '&version=' . \Yii::$app->request->get('version');
} else {
    $urlForm = (strpos(\Yii::$app->request->getPathInfo(), '/') !== 0 ? '/' : '') . \Yii::$app->request->getPathInfo();
}
$form = ActiveForm::begin([
            'method' => 'get',
            'action' => $urlForm,
            'options' => [
                'id' => 'news_form_' . $model->id,
                'class' => 'form',
                'enctype' => 'multipart/form-data',
            ]
        ]);
?>
<div class="search-news bg-100 rounded my-5 px-3 pt-5 pb-3">
    <div class="row">

        <div class="<?= $classKeyworkDiv ?>">
            <?=
            $form->field($model, 'keyword')->textInput([
                'placeholder' => Yii::t('app', 'Argomenti, parole chiave, testo...')
            ])->label(Yii::t('app', 'Cerca'))
            ?>
        </div>
        <?php if ($showCategories) : ?>
            <div class="col-lg-5 form-group">
                <?=
                \open20\design\components\bootstrapitalia\Select::widget([
                    'model' => $model,
                    'attribute' => 'news_categorie_id',
                    'label' => Yii::t('app', 'Seleziona una categoria') . ':',
                    'items' => $categorie,
                    'options' => [
                        'id' => 'categorie-filter',
                        'placeholder' => Yii::t('app', 'Seleziona...'),
                        'multiple' => false,
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        <?php endif; ?>
        <div class="col-lg-2 form-group text-md-right">
            <?= Html::a('<span class="mdi mdi-close"></span> <span class="d-lg-none">Cancella filtri</span>', $urlForm, ['class' => 'btn btn-sm px-2 btn-outline-tertiary', 'title' => 'Cancella filtri']) ?>
            <?= Html::submitButton('<span class="mdi mdi-filter mr-1"></span>' . AmosNews::tHtml('amosnews', 'Filtra'), ['class' => 'btn btn-sm btn-tertiary', 'title' => 'Applica filtri']) ?>
        </div>

    </div>
</div>

<?php
ActiveForm::end();
