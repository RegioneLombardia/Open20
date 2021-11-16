<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-newsletter/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;

/**
 * @var yii\web\View $this
 * @var amos\newsletter\models\Newsletter $model
 * @var yii\widgets\ActiveForm $form
 */

$js = <<<JS
$('#container-pjax-groups').on('"select2:select', function(){
    var idList = $('#list-id').val();
    $.pjax.reload({container: "#container-pjax-groups", url: window.location.href+"&idList="+idList , timeout: 20000});
});
 
JS;

$module = \Yii::$app->getModule('newsletter');
$enableCreateTemplate = $module->enableCreateTemplateNewsletter;
$enebleGroups = $module->enableServiceMailGroups

?>
<div class="newsletter-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'newsletter_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12">
                <h3><?=
                    \amos\newsletter\Module::t('amosnewsletter', 'Mail service: ') .
                    \amos\newsletter\utility\NewsletterUtility::getCurrentMailServiceName()?></h3>
            </div>
            <div class="col-md-12 col xs-12">                        <?php
                if (\Yii::$app->getUser()->can('NEWSLETTERTEMPLATE_CREATE')) {
                    $append = ' canInsert';
                } else {
                    $append = NULL;
                }
                ?>
<!--                --><?php //echo $form->field($model, 'newsletter_template_id')->widget(Select::classname(), [
//                    'data' => ArrayHelper::map(\amos\newsletter\models\NewsletterTemplate::find()->asArray()->all(), 'id', 'id'),
//                    'language' => substr(Yii::$app->language, 0, 2),
//                    'options' => [
//                        'id' => 'NewsletterTemplate0' . $fid,
//                        'multiple' => false,
//                        'placeholder' => 'Seleziona ...',
//                        'class' => 'dynamicCreation' . $append,
//                        'data-model' => 'newsletter_template',
//                        'data-field' => 'id',
//                        'data-module' => 'newsletter',
//                        'data-entity' => 'newsletter-template',
//                        'data-toggle' => 'tooltip'
//                    ],
//                    'pluginOptions' => [
//                        'allowClear' => true
//                    ],
//                    'pluginEvents' => [
//                        "select2:open" => "dynamicInsertOpening"
//                    ]
//                ])->label('')
                ?><!-- subject string -->

                <div class="col-xs-12">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- text text -->
                </div>

                <div class="col-xs-6">
                    <?= $form->field($model, 'service_email_list_id')->widget(\kartik\select2\Select2::className(),[
                            'data' => ArrayHelper::map($lists, 'IdList', 'Name'),
                            'options' => ['id' => 'list-id', 'placeholder' => 'Seleziona...']
                    ])->label(  \amos\newsletter\Module::t('amosnewsletter', 'Lists')) ?>
                </div>

                <?php if($enebleGroups){?>
<!--                    --><?php //\yii\widgets\Pjax::begin(['id' => 'container-pjax-groups', 'timeout' => 20000])?>
                    <div class="col-xs-6">
                        <?php echo $form->field($model, 'service_email_group_id')->widget(\kartik\depdrop\DepDrop::className(),[
                            'data' => [!empty($model->service_email_group_id) ? ArrayHelper::map(\amos\newsletter\utility\NewsletterUtility::actionGetGroupsByList($model->service_email_list_id),'idGroup', 'Name'): []],
                            'options'=> ['id'=>'group-id', 'placeholder'=>'Select ...'],
                            'pluginOptions'=>[
                                'depends'=> ['list-id'],
                                'url'=> Url::to(['/newsletter/newsletter/get-groups-by-list-ajax']),
                            ]
                        ])->label(  \amos\newsletter\Module::t('amosnewsletter', 'Groups')) ?>
                    </div>
<!--                    --><?php //\yii\widgets\Pjax::end()?>

                <?php } ?>

                <div class="col-xs-6">
                    <?= $form->field($model, 'serviceMailFields')->widget(\kartik\select2\Select2::className(),[
                        'data' => ArrayHelper::map($fields, 'Id', 'Description'),
                        'options' => ['placeholder' => 'Seleziona...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => true,
                        ],
                    ])->label(  \amos\newsletter\Module::t('amosnewsletter', 'Subscriber fields')) ?>
                </div>

                <?php if($enableCreateTemplate){ ?>
                    <div class="col-xs-12">
                        <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?><!-- text text -->
                    </div>
                    <div class="col-xs-12">
                        <?= $form->field($model, 'text')->widget(yii\redactor\widgets\Redactor::className(), [
                            'options' => [
                                'id' => 'text' . $fid,
                            ],
                            'clientOptions' => [
                                'language' => substr(Yii::$app->language, 0, 2),
                                'plugins' => ['clips', 'fontcolor', 'imagemanager'],
                                'buttons' => ['format', 'bold', 'italic', 'deleted', 'lists', 'image', 'file', 'link', 'horizontalrule'],
                            ],
                        ]);
                        ?><!-- welcome_type integer -->
                    </div>
                    <div class="col-xs-12">
                        <?= $form->field($model, 'welcome_type')->textInput() ?>
                    </div>
                <?php } ?>
                <div class="col-xs-12">
                    <?= RequiredFieldsTipWidget::widget(); ?>
                    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
                    <?php ActiveForm::end(); ?></div>
                </div>
        <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>

    </div>
</div>
