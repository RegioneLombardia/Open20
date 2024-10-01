<?php

use kartik\file\FileInput;
use open20\amos\core\forms\ActiveForm;
use kartik\select2\Select2;
use open20\amos\utility\Module;
use open20\amos\utility\models\search\NavItemSearch;
use yii\db\Expression;
use yii\db\Query;

$this->title = Yii::t('luyaconfigs', 'Importa pagina');
$this->params['titleSection'] = $this->title;

$this->params['breadcrumbs'][] = $this->title;
//$this->params['forceBreadcrumbs'][] = ['url' => '/luyaconfigs/export-luya/export-page','label'=> 'Esportazione pagine'];
//$this->params['forceBreadcrumbs'][] = ['label'=> $this->title];

\open20\amos\layout\assets\SpinnerWaitAsset::register($this);

$js = <<<JS
    $('#import-type-id').on('change',function(){
        console.log($(this).val());
        if($(this).val() == 'new_version'){
            $('#container-nav-item').show();
            $('#container-nav-item-version').show();
            $('#container-nav-container').hide();
            $('#sub-nav-container').hide();
        }else{
             $('#container-nav-item').hide();
             $('#container-nav-item-version').hide();
             $('#container-nav-container').show();
             $('#sub-nav-container').show();

        }
    });

$('#import-button-id').click(function(e){
    $('.loading').show();
});
JS;


$this->registerJs($js);
?>
<div style="display:none" class="loading"></div>

<div class="import-luya-page-form col-xs-12 nop">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ]
    ]);
    ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'importType')->widget(Select2::className(), [
                'data' => [
                    NavItemSearch::IMPORT_TYPE_NEW_PAGE => "Nuova pagina",
                    NavItemSearch::IMPORT_TYPE_NEW_VERSION => "Nuova Versione",
                ],
                'options' => ['id' => 'import-type-id']
            ])->label(Module::t('luyaconfigs', "Tipo di importazione"));
            ?>
        </div>
    </div>

    <div class="row m-b-20">
        <div id="container-nav-container" class="col-md-6">
            <?= $form->field($model, 'nav_container_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\luya\cms\models\NavContainer::find()->all(), 'id', 'name'),
                'options' => ['id' => 'nav-container_id-id']
            ])->label(Module::t('luyaconfigs', "Menu contenitore")) ?>
        </div>
        <div id="sub-nav-container" class="col-md-6">
            <?php
            if(!empty($model->nav_container_id)){
                    $query = new Query();
                    $navItemsDepdrop = $query->select(new Expression("cms_nav_item.id as id, CONCAT(admin_lang.short_code, ' - ', cms_nav_item.title, ' | ', cms_nav_item.alias) AS text"))
                        ->from('cms_nav_item')
                        ->leftJoin('cms_nav', 'cms_nav.id=cms_nav_item.nav_id')
                        ->leftJoin('admin_lang', 'admin_lang.id=cms_nav_item.lang_id')
                        ->andWhere(['nav_container_id' => $model->nav_container_id])->all();
            }
            echo $form->field($model, 'nav_item_sub_container')->widget(\kartik\depdrop\DepDrop::classname(), [
                'data' =>!empty($model->nav_container_id) ? \yii\helpers\ArrayHelper::map($navItemsDepdrop,'id','text') : [],
                'options' => ['id' => 'sub-container-id', 'placeholder' => 'Seleziona pagina ...'],
                'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
                'pluginOptions' => [
                    'depends' => ['nav-container_id-id'],
                    'placeholder' => 'Select...',
                    'url' => \yii\helpers\Url::to(['/utility/export-luya/nav-item-from-container-ajax'])
                ]
            ])->label(Module::t('luyaconfigs', "Pagina genitore"))
            ->hint('Selezionando il campo la pagina verrà importata come sottopagina, altrimenti come pagina di primo livello');
            ?>
        </div>


        <div id="container-nav-item-version" style="display:none" class="col-md-6">
            <?= $form->field($model, 'versionName')->textInput()
                ->label(Module::t('luyaconfigs', "Nome versione")); ?>
        </div>
        <div id="container-nav-item" style="display:none" class="col-md-6">
            <?= $form->field($model, 'nav_item_id')->widget(Select2::className(), [
                    'data' => [],
                    'options' => ['placeholder' => Module::t('luyaconfigs', 'Cerca pagina...')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/utility/export-luya/nav-items-ajax']),
                            'dataType' => 'json',
                            'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                ]
            )->label(Module::t('luyaconfigs', "Pagina")) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <label><?= Yii::t('luyaconfigs', 'Importa') ?></label>
            <?php echo FileInput::widget([
                'name' => 'import-file',
                'pluginOptions' => [
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                ]
            ]);
            ?>
            <p>
                <i><?= Module::t('luyaconfigs', 'Caricare il file .zip generato tramite la funzionalità di esporta pagina') ?></i>
            </p>
        </div>
    </div>
    <br>
    <div class="d-flex flex-wrap">
        <?= \yii\helpers\Html::a(Yii::t('luyaconfigs', 'Annulla'), '/luyaconfigs/export-luya/export-page', [
            'class' => 'btn btn-secondary',
            'title' => Yii::t('luyaconfigs', 'Annulla')
        ]) ?>
        <?=
        \yii\helpers\Html::submitButton(Yii::t('luyaconfigs', 'Importa'), [
            'class' => 'btn btn-primary pull-right',
            'name' => 'import',
            'id' => 'import-button-id',
            'value' => 1,
            'title' => Yii::t('luyaconfigs', 'Importa')
        ])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="clearfix"></div>

