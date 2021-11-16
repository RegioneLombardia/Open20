<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni\widgets\helpers\views
 * @category   CategoryName
 */

use open20\amos\comuni\AmosComuni;
use open20\amos\comuni\models\IstatComuni;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * @var \open20\amos\comuni\widgets\helpers\AmosComuniWidget $widget
 * @var \open20\amos\core\forms\ActiveForm $form
 * @var \open20\amos\core\record\Record $model
 * @var array $nazioneConfig
 * @var array $provinciaConfig
 * @var array $comuneConfig
 * @var array $capConfig
 * @var string $colMdRow
 */

//id del campo: se specificato nelle option uso quello, altrimenti sarà nel formato 'campo_db-id'
$comuneAttribute = $comuneConfig['attribute'];
$id = isset($comuneConfig['options']['id']) ? $comuneConfig['options']['id'] : $widget->generateFieldId($model, $comuneAttribute);
$provinciaAttribute = $provinciaConfig['attribute'];
$id_provincia = isset($provinciaConfig['options']['id']) ? $provinciaConfig['options']['id'] : $widget->generateFieldId($model, $provinciaAttribute);

?>

<div class="<?= isset($comuneConfig['class']) ? $comuneConfig['class'] : 'col-md-' . $colMdRow; ?>">
    <?= $form->field($model, $comuneAttribute)->widget(DepDrop::classname(), [
        'type' => DepDrop::TYPE_SELECT2,
        'data' => ArrayHelper::map(IstatComuni::find()->andWhere(['istat_province_id' => $model->$provinciaAttribute])->orderBy('nome')->asArray()->all(), 'id', 'nome'),
        'options' => array_merge(
            [
                'id' => $id,
            ], !empty($comuneConfig['options']) ? $comuneConfig['options'] : []
        ),
        'select2Options' => array_merge(
            [
                'pluginOptions' => ['allowClear' => true]
            ], !empty($comuneConfig['select2Options']) ? $comuneConfig['select2Options'] : []
        ),
        'pluginOptions' => array_merge(
            [
                'depends' => [$id_provincia],
                'placeholder' => AmosComuni::t('amoscomuni', '#select_commune_placeholder'),
                'url' => Url::to(['/comuni/default/comuni-by-provincia?soppresso=0']),
                'params' => [$id],
            ], !empty($comuneConfig['pluginOptions']) ? $comuneConfig['pluginOptions'] : []
        ),
        'pluginEvents' => [
            //il change viene chiamato al cambio del padre: provincia
            "depdrop:afterChange" => "function(event, id, value, count) { 
                clearValueIfParentEmpty($(this));
             }",
        ],
    ]); ?>
</div>
