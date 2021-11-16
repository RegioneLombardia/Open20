<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsTableWithPreview;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\forms\ShowUserTagsWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\news\AmosNews;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\InteractionMenuWidget;
use open20\amos\news\assets\ModuleNewsAsset;
use \open20\amos\news\models\News;
ModuleNewsAsset::register($this);

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 */

$this->title = $model->titolo;


/** @var \open20\amos\news\controllers\NewsController $controller */
$url = '/img/img_default.jpg';
if (!is_null($model->newsImage)) {
    $url = $model->newsImage->getWebUrl('square_large', false, true);
}

?>

<div class="news-view col-xs-12 nop">
    <div class="clearfix"></div>
    <div class="col-xs-12">
        <div class="header col-xs-12 nop">
            <img class="img-responsive" src="<?= $url ?>" alt="<?= $model->titolo ?>">
            <div class="title col-xs-12">
                <h2 class="title-text"><?= $model->titolo ?></h2>
                <h3 class="subtitle-text"><?= $model->sottotitolo ?></h3>
            </div>
        </div>
        <div class="text-content col-xs-12 nop">
            <?= $model->descrizione; ?>
        </div>
    </div>
    <div class="col-xs-12 text-center">
        <hr>
        <?= Html::a(AmosNews::t('amosnews', '#enter_into_platform'), ['/news/news/view', 'id' => $model->id], [
            'class' => 'btn btn-navigation-primary'
        ])?>
    </div>
</div>

