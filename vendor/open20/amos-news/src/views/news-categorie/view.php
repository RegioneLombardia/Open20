<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news-categorie
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\news\AmosNews;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\NewsCategorie $model
 */

$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Notizie'), 'url' => '/news'];
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Categorie notizie'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="news-categorie-view">
    <div class="row">
        <div class="col-xs-12">
            <div class="body">
                <section class="section-data">
                    <?= ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/news/news-categorie/update?id=" . $model->id,
                        'actionDelete' => "/news/news-categorie/delete?id=" . $model->id,
                        'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa categoria di notizie?'),
                    ]) ?>
                    <h2>
                        <?= AmosIcons::show('rss'); ?>
                        <?= AmosNews::t('amosnews', 'Dettagli'); ?>
                    </h2>
                    <dl>
                        <dt><?= $model->getAttributeLabel('categoryIcon'); ?></dt>
                        <dd><?= Html::img($model->getCategoryIconUrl(), ['class' => 'gridview-image']) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('titolo'); ?></dt>
                        <dd><?= $model->titolo; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('sottotitolo'); ?></dt>
                        <dd><?= $model->sottotitolo; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('descrizione_breve'); ?></dt>
                        <dd><?= $model->descrizione_breve; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('descrizione'); ?></dt>
                        <dd><?= $model->descrizione; ?></dd>
                    </dl>
                </section>
            </div>
        </div>
    </div>
</div>
