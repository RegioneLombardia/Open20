<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\record\CachedActiveQuery;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();
$search         = AmosDocumenti::instance()->createModel('DocumentiSearch');
$search->setNotifier(new NotifyWidgetDoNothing());
$listaDocumenti = $search->lastDocuments(\yii\helpers\ArrayHelper::merge($_GET, ['fromWidgetGraphic']), $documentsModule->explorerLastDocsToShow);
$query          = $listaDocumenti->query->andWhere(['is_folder' => 0]);
$query->orderBy('created_at DESC');
$query  = $query->all();

if (count($query) > 0) {
?>
<div class="icon-view">
    <div class="list-view-design list-view-card-document it-list-wrapper m-t-20">
        <h2><?= AmosDocumenti::t('amosdocumenti', 'DOCUMENTI RECENTI') ?></h2>
        <div>
            <div class="m-t-20" role="listbox" data-role="list-view">
                <?php
                foreach ($query as $model) {
                    $mainDocument       = $model->documentMainFile;
                    $relationQuery      = $model->getCreatedUserProfile();
                    $relationCreated    = CachedActiveQuery::instance($relationQuery);
                    $relationCreated->cache(60);
                    $createdUserProfile = $relationCreated->one();
                    echo \open20\amos\documenti\widgets\ItemDocumentCardWidget::widget([
                        'typeView' => 'card',
                        'model' => $model,
                        'type' => (!empty($mainDocument) ? $mainDocument->type : null),
                        'size' => (!empty($mainDocument) ? $mainDocument->formattedSize : null),
                        'actionModify' => '/documenti/documenti/update?id='.$model->id,
                        'date' => $model->data_pubblicazione,
                        'nameSurname' => $createdUserProfile->nomeCognome,
                        'fileName' => (!empty($mainDocument) ? $mainDocument->name : ''),
                        'allegatiNum' => $model->getFilesByAttributeName('documentAttachments')->count(),
                        'title' => $model->titolo,
                        'actionView' => '/documenti/documenti/view?id='.$model->id,
                        'fileUrl' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity::getLinkOptions($model),
                        'link_document' => $model->link_document,
                        'widthColumn' => 'col-md-4 col-sm-6 col-xs-12',
                    ]);
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<h2><?= AmosDocumenti::t('amosdocumenti', 'ESPLORA DOCUMENTI') ?></h2>
<?php
echo \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity::widget(['isAlwaysVisible' => true]);

