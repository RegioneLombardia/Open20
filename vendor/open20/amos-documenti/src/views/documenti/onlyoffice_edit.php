<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\documenti\AmosDocumenti;
use open20\onlyoffice\widgets\EditorWidget;
use open20\amos\admin\models\UserProfile;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */
/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$onlyofficeModule = \Yii::$app->getModule('onlyoffice');


$this->title = \yii\helpers\Html::decode($model->titolo);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = AmosDocumenti::t('amosdocumenti', 'Aggiorna');

$this->params['forceBreadcrumbs'][] = [
    'label' => AmosDocumenti::t('amosdocumenti', 'Documenti'),
    'url' => Yii::$app->session->get('previousUrl'),
];

$this->params['forceBreadcrumbs'][] = [
    'label' => AmosDocumenti::t('amosdocumenti', 'Aggiorna'),
    'url' => ['/documenti/documenti/update','id'=>$model->id],
];

$this->params['forceBreadcrumbs'][] = [
    'label' => $this->title,	
];

$modelUserProfile = UserProfile::find()
->andWhere(['user_id' => Yii::$app->user->id])
->one();
?>

<div class="open-office m-t-20">
    <?= EditorWidget::widget([
        'urlFile' => $model->documentMainFile->getWebUrl('original', true, false),
        'keyFile' => $model->documentMainFile->hash,
        'nameFile' => $model->documentMainFile->name . '.' . strtolower($model->documentMainFile->type),
        'options' => [
            'iframeHeight' => '700px',
            'fileTypeAuto' => strtolower($model->documentMainFile->type),
            'documentTypeAuto' => true,
        ],
        'configForJs' => [
            'documentType' => $onlyofficeModule->getDocumentTypeByExtension($model->documentMainFile->type,true),
            'editorConfig' => [
                'mode' => 'edit',
                'callbackUrl' => Url::to(['/documenti/onlyoffice/callback-api'], true),
                'customization' => [
                    'forcesave' => true, 
                ],
                'coEditing'=> [
                    'mode' => 'fast',
                    'change' => true,
                ],
                'user' => [
                    'id' => Yii::$app->user->id,
                    'name' => ((!empty($modelUserProfile)) ? $modelUserProfile->nomeCognome : ''),
                ],
            ],
        ]
    ]) ?>
</div>
