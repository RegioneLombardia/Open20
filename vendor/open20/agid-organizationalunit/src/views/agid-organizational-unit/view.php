<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\agid\organizationalunit\Module;


/**
* @var yii\web\View $this
* @var app\models\AgidOrganizationalUnit $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/app']];
$this->params['breadcrumbs'][] = ['label' => Module::t('amosorganizationalunit', 'Organizational Unit'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="agid-organizational-unit-view">
    <?= 
        DetailView::widget([
            'model' => $model,    
            'attributes' => [
                'agid_organizational_unit_content_type_id' => [
                    'attribute' => 'agid_organizational_unit_content_type_id',
                    'value' => $model->agidOrganizationalUnitContentType->name
                ],
                'agid_organizational_unit_type_id' => [
                    'attribute' => 'agid_organizational_unit_type_id',
                    'value' => $model->agidOrganizationalUnitType->name
                ],

                // 'agid_organizational_unit_content_type_id',
                // 'agid_organizational_unit_type_id',
                'name',
                'short_description:html',
                'skills:html',
                'headquarters_name:html',
                'address:html',
                'public_hours:html',
                'cap',
                'telephone_reference:html',
                'mail_reference:html',
                'pec_reference:html',
                'further_information:html',
                'help_box:html',
                'status',
            ],    
        ]) 
    ?>
</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-primary']); ?>
</div>
