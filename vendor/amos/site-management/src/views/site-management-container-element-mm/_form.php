<?php

use amos\sitemanagement\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\Tabs;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementContainerElementMm $model
 * @var yii\widgets\ActiveForm $form
 */
\amos\sitemanagement\assets\ModuleSiteManagementAsset::register($this);


$module = \Yii::$app->getModule('sitemanagement');
$whiteListClasses = $module->whiteListClasses;
$blackListClasses = $module->blackListClasses;


$pubblication_type_id = $modelPubblication->pubblication_type_id;
$js = <<<JS
    function hideShowPubblications(value){
         if(value == 1){
            $('#pubbl-type-selected-user').hide();
            $('#pubbl-type-class').hide();
        } 
        else if(value == 2){
            $('#pubbl-type-selected-user').show();
            $('#pubbl-type-class').hide();
        } 
        else if(value == 3){
            $('#pubbl-type-selected-user').hide();
            $('#pubbl-type-class').show();
        }
    }
    
    hideShowPubblications($pubblication_type_id);
    
    
    $('#pubblication-type-id').on('select2:select', function(e){
        hideShowPubblications($(this).val());
    });

    $('#grid-elements input[type="radio"]').on('click', function(){
        var elem = $(this).val();
        $('#element-id').val(elem);
    });
    
    
    
    
    $(document).on('click', '.showModalButton', function(e){
        e.preventDefault();
        var idElem = $(this).attr('data-key');
        var url = "/sitemanagement/site-management-element/show-preview?id="+idElem;
         //check if the modal is open. if it's open just reload content not whole modal
        //also this allows you to nest buttons inside of modals to reload the content it is in
        //the if else are intentionally separated instead of put into a function to get the 
        //button since it is using a class not an #id so there are many of them and we need
        //to ensure we get the right button and content. 
        if ($('#modal').data('bs.modal').isShown) {
            $('#modal').find('#modalContent')
                    .load(url);
            //dynamiclly set the header for the modal
            document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        } else {
            //if modal isn't open; open it and load content
            $('#modal').modal('show')
                    .find('#modalContent')
                    .load(url);
             //dynamiclly set the header for the modal
            document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        }
    });
JS;
$this->registerJs($js);

?>

<?php if ($model->isNewRecord) { ?>
    <div class="container-change-view ">
        <div class="btn-tools-container">
            <?= \yii\helpers\Html::a(Module::t('amossitemanagement', 'Create new element'), ['/sitemanagement/site-management-element/create', 'idContainer' => $model->container_id], ['class' => 'btn btn-navigation-primary']) ?>
            <div class="tools-right">
                <?= \open20\amos\core\icons\AmosIcons::show('search', [
                    'class' => 'btn btn-tools-primary show-hide-element',
                    'data-toggle-element' => 'form-search'
                ]); ?>
            </div>

        </div>
    </div>
<?php } ?>

<div class="site-management-container-element-mm-form col-xs-12 nop">

    <?php echo $this->render('_search_element', ['model' => $elementSearch]); ?>
    <?php $form = ActiveForm::begin(); ?>

    <div class="container-choose-element">
        <div class="col-xs-12">
            <h3><?= Module::t('amossitemanagement', 'Choose the element') ?></h3>
        </div>
        <div class="col-xs-12">
            <?php
            $element_id = $model->element_id;
            echo \open20\amos\core\views\AmosGridView::widget([
                'dataProvider' => $dataProviderElements,
                'id' => 'grid-elements',
                'columns' => [
                    [
                        'class' => \yii\grid\RadioButtonColumn::className(),
                        'radioOptions' => function ($model) use ($element_id) {
                            return [
                                'value' => $model->id,
                                'checked' => $model->id == $element_id
                            ];
                        }
                    ],
                    'siteManagementTemplate.name',
                    'title',
                    'description',
                    [
                        'attribute' => 'created_at',
                        'format' => 'date'
                    ],
                    [
                        'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                        'template' => '{preview}',
                        'buttons' => [
                            'preview' => function ($url, $model) {
                                return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('widgets'), '', [
                                    'class' => 'btn btn-tools-secondary showModalButton',
                                    'data-key' => $model->id,
                                    'title' => Module::t('amossitemanagement', '#preview')
                                ]);
                            }
                        ]
                    ]
                ]
            ]);
            ?>
            <?= $form->field($model, 'element_id')->hiddenInput(['id' => 'element-id'])->label(false) ?>
        </div>
    </div>
    <div class="col-xs-12">
        <h3><?= Module::t('amossitemanagement', 'Choose pubblication') ?></h3>
    </div>

    <div class="col-xs-6">
        <?= $form->field($modelPubblication, 'pubblication_type_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(\amos\sitemanagement\models\SiteManagePubblicationType::find()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Select...', 'id' => 'pubblication-type-id']
        ]) ?>
    </div>


    <div id="pubbl-type-selected-user" class="col-xs-6" style="display:none">
        <?= $form->field($modelPubblication, 'pubblicationUsers')->widget(Select2::className(), [
            'data' => ArrayHelper::map(\open20\amos\admin\models\UserProfile::find()->all(), 'user_id', 'nomeCognome'),
            'options' => ['placeholder' => 'Select...', 'id' => 'pubblication-users-id'],
            'pluginOptions' => [
                'multiple' => true,
                'allowClear' => true,
                'ajax' => [
                    'url' => '/admin/user-profile-ajax/ajax-user-list',
                    'dataType' => 'json',
                    'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                ],
            ]
        ])->label(Module::t('amossitemanagement', 'Pubblication Users')) ?>
    </div>

    <div id="pubbl-type-class" class="col-xs-6" style="display:none">
        <?php
        $roles = [];
        /** @var  $role \yii\rbac\Role */
        foreach (\Yii::$app->authManager->getRoles() as $role) {
            if (in_array($role->name, $whiteListClasses) && !in_array($role->name, $blackListClasses)) {
                $roles [$role->name] = $role->name;
            }
        } ?>
        <?= $form->field($modelPubblication, 'pubblicationClasses')->widget(Select2::className(), [
            'data' => $roles,
            'options' => ['placeholder' => 'Select...', 'id' => 'pubblication-classes-id'],
            'pluginOptions' => [
                'multiple' => true,
                'allowClear' => true,
            ]
        ])->label(Module::t('amossitemanagement', 'Pubblication Classes')) ?>
    </div>

    <div class="col-xs-12">
        <label><?= Module::t('amossitemanagement', 'Language')?></label>
        <div class="col-xs-12 receiver-section">
            <div class="tag-section">
                <?php
                $moduleTag = \Yii::$app->getModule('tag');
                if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
                    echo \open20\amos\tag\widgets\TagWidget::widget([
                        'model' => $model,
                        'attribute' => 'tagValues',
                        'form' => \yii\base\Widget::$stack[0],
                        'hideHeader' => true,
                    ]);
                }

                ?>

            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <h4><?= Module::t('amossitemanagement', 'Pubblication date') ?></h4>
    </div>
    <div class="col-xs-6">
        <?= $form->field($modelPubblication, 'start_date')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATETIME,
        ]) ?>
    </div>
    <div class="col-xs-6">
        <?= $form->field($modelPubblication, 'end_date')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATETIME,
        ]) ?>
    </div>

    <div class="col-xs-12">
        <h4><?= Module::t('amossitemanagement', 'Daily intervall of pubblication') ?></h4>
    </div>
    <div class="col-xs-6">
        <?php
        $modelPubblication->start_time = empty($modelPubblication->start_time) ? date('00:00:00') : $modelPubblication->start_time;
        ?>
        <?php echo $form->field($modelPubblication, 'start_time')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_TIME,
        ]) ?>
    </div>
    <div class="col-xs-6">
        <?php
        $modelPubblication->end_time = empty($modelPubblication->end_time) ? date('23:59:00') : $modelPubblication->end_time;
        ?>
        <?php echo $form->field($modelPubblication, 'end_time')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_TIME,
        ]) ?>
    </div>




    <div class="clearfix"></div>

    <?php
    yii\bootstrap\Modal::begin([
        'headerOptions' => ['id' => 'modalHeader'],
        'id' => 'modal',
        //keeps from closing modal with esc key or by clicking out of the modal.
        // user must click cancel or X to close
//        'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
    ]);
    echo "<div id='modalContent'></div>";
    yii\bootstrap\Modal::end();
    ?>
    <?= CloseSaveButtonWidget::widget([
        'model' => $model,
        'urlClose' => ((\Yii::$app->request->referrer && strpos(\Yii::$app->request->referrer, 'create') === false) ? \Yii::$app->request->referrer : \yii\helpers\Url::previous()),
    ]); ?>
    <?php ActiveForm::end(); ?>
</div>
