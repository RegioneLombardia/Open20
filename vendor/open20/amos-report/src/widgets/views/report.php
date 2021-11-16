<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-report
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\report\AmosReport;
use open20\amos\report\utilities\ReportUtil;
use open20\amos\report\models\ReportType;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\web\View;

/*
 * @var string $content
 * @var \open20\amos\report\widgets\ReportWidget $widget
 * @var \open20\amos\report\models\Report $Report
 * @var integer $context_id
 * @var Yii\Web\View $this
 */

$js = <<<JS
  
    $("#load_form-$context_id").on("click",function(e) {
        e.preventDefault();
        $("#modal_report-$context_id").modal('show');
        $.ajax({
            url: '/report/report/create',
            type: 'POST',
            async: true,
            data: {
                context_id: "$context_id",
                classname: "$className"
            },
            success: function(response) {
                if(response) {
                    $("#report-form_$context_id").find(".form-content").removeClass("hidden");
                    $("#report-form_$context_id").find(".success-message").addClass("hidden");
                }    
            }
        });
        return false;
    });          

    $(".modal-body").on("submit","#report-form_$context_id",function(e) {    
        e.preventDefault();       
        var form = $("#report-form_$context_id");
            $.ajax({
            url: '/report/report/create',
            async: true,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if(response) {        
                    $("#report-form_$context_id").find(".form-content").addClass("hidden");
                    $("#report-form_$context_id").find(".success-message").removeClass("hidden");
                    $("#report-form_$context_id")[0].reset();
                }    
            }
        });
        return false;
    });
   
    
JS;

$this->registerJs($js, View::POS_LOAD);

?>
<? /*= $content*/ ?>

<?php
/*Modal::begin([
    'header' => AmosReport::t('amosreport', 'You are sending a report for'),
    'id' => 'modal_report-'.$context_id,
    'size' => 'modal-lg'
]);*/
$Report = new \open20\amos\report\models\Report();
?>

<?php
$form = ActiveForm::begin([
    'id' => 'report-form_'.$context_id,
    'enableClientValidation' => true,
    'options' => [
        'class' => 'form default-form col-xs-12 nop',
    ],
    'action' => '/report/report/create'
]) ?>
<div class="form-content">
    <?php if(!empty($title)){
        echo "<h3>".$title."</h3>";
    }?>
    <p><?= AmosReport::t('amosreport',
            'Specify below the type and the description of your report.<br/>You can report errors or contents that you consider inappropriate and, if necessary, ask for correction.') ?></p>
    <div class="hidden">
        <?= $form->field($Report, 'context_id')->hiddenInput(['value' => $context_id])->label(false); ?>
        <?= $form->field($Report, 'classname')->hiddenInput(['value' => $className])->label(false); ?>
        <?= $form->field($Report, 'created_by')->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>
    </div>

    <?= $form->field($Report, 'type')->widget(Select2::className(), [
        'data' => ReportUtil::translateArrayValues(ArrayHelper::map(ReportType::find()->orderBy('name')->all(),'id', 'name')),
        'options' => [
            'multiple' => false,
            'placeholder' => AmosReport::t('amosreport', 'Enter name of the report type'),
            'id' => 'type-id_'.$context_id,
            'class' => 'dynamicCreation',
            'disabled' => false
        ],
    ]) ?>

    <?=
    $form->field($Report, 'content')->textarea([
        'maxlength' => 300,
        'rows' => 5 ,
        'id' => 'content-'.$context_id
    ])->hint(AmosReport::t('amosreport','Max. 300 characters'))
    ?>

    <div class="col-xs-12 note_asterisk">
        <?= Html::tag('span', AmosReport::t('amosreport', '#required_field')) ?>
    </div>
    <!--<div class="form-group">-->
        <div class="bk-btnFormContainer">
            <?= Html::submitButton(AmosReport::t('amosreport', 'Send'),
                ['class' => 'btn btn-navigation-primary', 'id' => 'submitReportForm']) ?>
            <?= Html::button(AmosReport::t('amosreport', 'Cancel'),
                ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal']) ?>
        </div>
    <!--</div>-->
</div>
<div class="success-message hidden">
    <p><?= AmosReport::t("amosreport", "Your report has been correctly sent.") ?></p>
    <div class="form-group"><div class="bk-btnFormContainer">
            <?= Html::button(AmosReport::t('amosreport', 'Close'), ['class' => 'btn btn-secondary pull-right', 'data-dismiss' => 'modal'])?><br/>
            </div>
    </div>
</div>
<?php
ActiveForm::end();
?>


<?php /*Modal::end();*/ ?>


