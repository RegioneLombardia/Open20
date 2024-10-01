<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\utility\DocumentsUtility;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

$this->title = $model->titolo;
$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
$documentMainFile = $model->documentMainFile;


/** @var \open20\amos\documenti\controllers\DocumentiController $controller */


?>

<div class="documents-view col-xs-12 nop">
    <div class="clearfix"></div>
    <div class="col-md-8 col-xs-12">
        <div class="header col-xs-12 nop">
            <div class="title col-xs-12">
                <h2 class="title-text"><?= $model->titolo ?></h2>
                <h3 class="subtitle-text"><?= $model->sottotitolo ?></h3>
            </div>
        </div>
        <div class="col-xs-12 download-file nop">
            <div class="col-xs-12 action-document">
                <div>
                    <div>
                        <?=
                        DocumentsUtility::getDocumentIcon($model);
                        ?>
                    </div>
                    <div>
                        <?=
                        Html::tag('p', ((strlen($documentMainFile->name) > 80) ? substr($documentMainFile->name, 0, 75) . '[...]' : $documentMainFile->name) . '.' . $documentMainFile->type, ['class' => 'filename']);
                        ?>
                    </div>
                </div>
                <div>
                    <?= Html::a(/*AmosDocumenti::tHtml('amosdocumenti', 'Scarica file') . */
                        AmosIcons::show('download'), [$model->getDocumentMainFile()->getWebUrl()], [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                        'class' => 'bk-btnImport pull-right btn btn-icon',
                    ]); ?>

                </div>
            </div>

        </div>
        <div class="text-content col-xs-12 nop">
            <?= $model->descrizione; ?>
        </div>
    </div>


</div>
<?= Html::a(AmosDocumenti::t('amosnews', '#enter_into_platform'), ['/documenti/documenti/view', 'id' => $model->id], [
    'class' => 'btn btn-secondary pull-left m-b-10'
])?>