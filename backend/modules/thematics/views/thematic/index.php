<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/thematics/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ViewUtility;
use open20\amos\core\views\DataProviderView;
use backend\modules\thematics\Module;
use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\CropInput;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\PasswordInput;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\icons\AmosIcons;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use open20\amos\core\models\AttributesChangeLog;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\modules\thematics\models\search\OperatorSearch $model
 */

$this->title = Yii::t('amoscore', 'Tematiche');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/thematics']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registereduser-index">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'operator_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?= $this->render('_targetETematiche',
        [
            'model' => $model,
            'form' => $form,
//                    'tag' => $tag,
//                    'profili' => $profili,
//                    'wizardAsset' => $wizardAsset,
//                    'spriteAsset' => $spriteAsset,
        ])
    ?>


    <?= RequiredFieldsTipWidget::widget(); ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model,
        'buttonSaveLabel' => 'Salva',
        'buttonNewSaveLabel' => 'Salva',
        'buttonClassSave' => 'btn btn-primary float-right',
        'buttonCloseVisibility' => false
    ]); ?>
    <?php ActiveForm::end(); ?>

</div>
