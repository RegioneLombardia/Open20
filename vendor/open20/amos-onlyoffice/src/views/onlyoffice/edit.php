<?php
use open20\onlyoffice\widgets\EditorWidget;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var open20\onlyoffice\models\OnlyofficeFiles $model */
/** @var open20\amos\attachments\models\File $modelFile */

$this->title = 'OnlyOffice';
if (!empty($modelFile)) {
    $this->title .= ' - ' . $modelFile->name . '.' . $modelFile->type
        . ' (edit)';
}
$this->params['breadcrumbs'][] = ['label' => 'Elenco file', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if ((!empty($model)) && (!empty($modelFile))): ?>
<?= EditorWidget::widget([
    'urlFile' => $modelFile->getWebUrl('original', true, false),
    'keyFile' => $model->getKeyFile(),
    'nameFile' => $modelFile->name . '.' . strtolower($modelFile->type),
    'editorMode' => 'edit',
    'options' => [
//        'fileTypeAuto' => true,
        'fileTypeAuto' => strtolower($modelFile->type),
        'documentTypeAuto' => true,
        'iframeHeight' => '700',
        'language' => 'it',
        'autore' => ArrayHelper::getValue($model, 'autore.nomeCognome'),
        'dataCreazione' => $model->getDataCreazione(),
    ]
]) ?>
<?php endif; ?>