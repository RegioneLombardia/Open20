<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\editors\maps\PlaceWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\events\AmosEvents;
use open20\amos\news\utility\NewsUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\news\AmosNews;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\news\assets\ModuleNewsAsset;
use open20\amos\news\models\News;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\report\AmosReport;


ModuleNewsAsset::register($this);

// ENABLE AGID FIELDS
$enableAgid = AmosNews::instance()->enableAgid;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 * @var \open20\amos\events\models\Event $events
 * @var yii\data\ActiveDataProvider $dataProviderEvents
 * @var string $currentView
 * @var AmosNews $moduleNews
 */
$this->title = $model->titolo;
$moduleNews = AmosNews::instance();

$showSignature = $moduleNews->showSignature;

$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
if (isset($ruolo['ADMIN'])) {
    $url = ['index'];
}

/** @var \open20\amos\news\controllers\NewsController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

/** @var AmosEvents $eventsModule */
$eventsModule = Yii::$app->getModule('events');

/** @var AmosReport $reportModule */
$reportModule = \Yii::$app->getModule('report');
$showReportWidget = isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled);

if (\Yii::$app->request->get('redactional')) {
    $this->params['forceBreadcrumbs'][] = ['label' => AmosNews::t('amosnews', "News"), 'url' => ['/news/news/redaction-all-news']];
    $this->params['forceBreadcrumbs'][] = ['label' => $this->title];
}

$hidePubblicationDate = Yii::$app->controller->newsModule->hidePubblicationDate;
$numberListTag = Yii::$app->controller->newsModule->numberListTag;
$enableGalleryAttachment = Yii::$app->controller->newsModule->enableGalleryAttachment;
$enableRelateEvents = Yii::$app->controller->newsModule->enableRelateEvents;
$enableLikeWidget = Yii::$app->controller->newsModule->enableLikeWidget;
$enableCustomStatusLabel = Yii::$app->controller->newsModule->enableCustomStatusLabel;

$url = '/img/img_default.jpg';
if (!is_null($model->newsImage)) {
    $url = $model->newsImage->getWebUrl('square_large', false, true);
}

if (!\Yii::$app->user->isGuest && $model->status != News::NEWS_WORKFLOW_STATUS_VALIDATO) {

    $customStatusLabel = [];

    if ($enableCustomStatusLabel && (Yii::$app->user->can('NewsValidate', ['model' => $model]) || Yii::$app->user->can('ADMIN'))) {
        $customStatusLabel[News::NEWS_WORKFLOW_STATUS_DAVALIDARE] = Yii::t('amosnews', 'La notizia è in fase di approvazione per la pubblicazione');
    }

    echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => News::NEWS_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true,
        'forceStatusLabel' => $customStatusLabel
    ]);
}

$hideCategory = false;
$newsCategories = NewsUtility::getAllNewsCategories();
if ($newsCategories->count() == 1) {
    $hideCategory = true;
} else {
    $category = $model->newsCategorie->titolo;
    $customCategoryClass = 'mb-1 px-1 ' . 'custom-category-bg-' . str_replace(' ', '-', strtolower($category));
    $colorBgCategory = $model->newsCategorie->color_background;
    $colorTextCategory = $model->newsCategorie->color_text;
}

if (is_null($model->alt)) {
    $alt = '';
} else {
    $alt = $model->alt;
}
?>

<div class="detail-news-hero-wrapper it-hero-wrapper it-dark it-overlay">
    <div class="img-responsive-wrapper">
        <div class="img-responsive">
            <div class="img-wrapper">
                <img src="<?= $url ?>" alt="<?= AmosNews::t('amosnews', $alt) ?>">
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-hero-text-wrapper bg-dark">
                    <span class="it-category  <?= $customCategoryClass ?>" <?php if ((!empty($colorBgCategory))) : ?> style="background-color: <?= $colorBgCategory ?> !important; padding: 0 4px; " <?php endif; ?>><strong <?php if ((!empty($colorTextCategory))) : ?> style="color: <?= $colorTextCategory ?>" <?php endif; ?>><?= $category ?></strong></span>
<?php
/** @var  $otherCat \open20\amos\news\models\NewsCategorie */
foreach ($model->otherNewsCategories as $otherCat) {
    $customOtherCategoryClass = 'mb-1 px-1 '
            . 'custom-category-bg-'
            . str_replace(' ', '-', strtolower($category));
    ?>
                        <span class="it-category  <?= $customOtherCategoryClass ?>" <?php if ((!empty($otherCat->color_background))) : ?> style="background-color: <?= $otherCat->color_background ?> !important; padding: 0 4px; " <?php endif; ?>><strong <?php if ((!empty($otherCat->color_text))) : ?> style="color: <?= $otherCat->color_text ?>" <?php endif; ?>><?= $otherCat->titolo ?></strong></span>
                    <?php } ?>

                    <p class="date"><?= Yii::$app->getFormatter()->asDate($model->data_pubblicazione) ?></p>
                    <h1 class="no_toc"><?= $model->titolo ?></h1>
                    <p class="d-none d-lg-block"><?= $model->sottotitolo ?></p>

<?php if (!empty(\open20\amos\core\utilities\CwhUtility::getTargetsString($model))) : ?>
                        <p><span class="mdi mdi-account-supervisor-circle mdi-24px"></span>
                            <em><?= \open20\amos\core\utilities\CwhUtility::getTargetsString($model) ?></em>
                        </p>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="news-view">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 header-widget">
<?php if ($showSignature): ?>
                    <?=
                    ItemAndCardHeaderWidget::widget(
                            [
                                'model' => $model,
                                'publicationDateNotPresent' => true,
                                'showPrevalentPartnership' => true,
                                'enableLink' => (AmosNews::instance()->enableLinkProfile ? !(CurrentUser::isPlatformGuest()) : false),
                                'absoluteUrlAvatar' => true,
                            ]
                    )
                    ?>
                <?php endif; ?>

                <div class="more-info-content">
                    <?php
                    if ($model->canShowCommentsNotificationBellWidget()) {
                        // Comments notification bell widget
                        echo \open20\amos\comments\widgets\CommentsBellNotificationWidget::widget([
                            'modelClassname' => $model->modelClassName,
                            'modelId' => $model->id,
                            'buttonClass' => $showReportWidget ? 'btn btn-outline-tertiary m-r-10' : ''
                        ]);
                    }
                    ?>

                    <?php
                    if ($showReportWidget) {
                        // Report widget
                        echo \open20\amos\report\widgets\ReportDropdownWidget::widget([
                            'model' => $model,
                        ]);
                    }
                    ?>

                    <div class="m-l-10">
                        <?=
                        ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/news/news/update?id=" . $model->id,
                            'actionDelete' => "/news/news/delete?id=" . $model->id,
                            'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa notizia?'),
                            'modelValidatePermission' => 'NewsValidate'
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="text-content">
                <?= $model->descrizione; ?>
                </div>

                <?php
                $attachmentsWidget = '';
                $attachmentsWidget = AttachmentsList::widget([
                            'model' => $model,
                            'attribute' => 'attachments',
                            'viewDeleteBtn' => false,
                            'viewDownloadBtn' => true,
                            'viewFilesCounter' => true,
                ]);
                ?>

                <?php
                if (!empty($eventsModule) && ($enableRelateEvents) && ($dataProviderEvents->getTotalCount() > 0)) {
                    open20\amos\events\assets\EventsAsset::register($this);
                    $urlIcon = '@vendor/open20/amos-events/src/views/event/_icon';
                    ?>

                    <div class="event-index">
                        <?=
                        DataProviderView::widget([
                            'dataProvider' => $dataProviderEvents,
                            'currentView' => $currentView,
                            'iconView' => [
                                'itemView' => $urlIcon
                            ],
                        ]);
                        ?>
                    </div>
                <?php } ?>

                <?php
                $tagsWidget = '';

                $tagsWidget = \open20\amos\core\forms\ListTagsWidget::widget([
                            'userProfile' => $model->id,
                            'className' => $model->className(),
                            'viewFilesCounter' => true,
                ]);
                ?>

<?= $attachmentsWidget ?>

<?php $arrayImg = $model->getGalleriaUrl();
if ($enableGalleryAttachment && $arrayImg != NULL) {
    ?>
                    <div class="gallery-section m-t-30">
                        <div class="container">
                            <strong class="text-uppercase"><?= AmosNews::t('amosnews', 'Gallery') ?></strong>
                            <div class="row m-t-20">
                                <?php foreach ($arrayImg as $image) { ?>
                                    <div class="col-md-4 col-xs-6 m-b-30">
                                        <img alt="<?= AmosNews::t('amosnews', 'Immagine galleria') ?>" src="<?= $image ?>" class="img-responsive">
                                    </div>
                    <?php } ?>
                            </div>

                        </div>
                    </div>
    <?php
}
?>

                <div class="clearfix"></div>

                <div class="tag-container">
                    <?= $tagsWidget ?>

                     <?php 
                    if ($moduleNews->addTags && !strpos($tagsWidget,'aree')) {
                        echo $this->render('parts/_AddUserTag', [
                            'model' => $model,
                            'array_diff' => $array_diff,
                        ]);
                    }
                    ?>
                </div>

                <!-- Point of interest -->
<?php if ($moduleNews->enableMap && !empty(Yii::$app->params['google_places_api_key']) && !empty($model->place_id)): ?>
                    <div class="clearfix"></div>
                    <div class="poi poi-map-view m-t-20">
                        <label class="text-uppercase m-b-10"><?= AmosNews::t('amosnews', 'Punto di interesse') ?></label>
                        <?=
                        PlaceWidget::widget([
                            'options' => [
                                'name' => 'poi_place_id',
                                'hideOtherLocations' => true,
                            ],
                            'model' => $model,
                            'attribute' => 'place_id',
                            'showInput' => false,
                        ]);
                        ?>
                    </div>
<?php endif; ?>

                <div class="clearfix"></div>
                <div class="footer-content">
                    <div class="social-share-wrapper">
                        <?php
                        $url = !empty(\Yii::$app->params['platform']['frontendUrl']) ? \Yii::$app->params['platform']['frontendUrl'] : "";
                        echo \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                            'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_NORMAL,
                            'configuratorId' => 'socialShare',
                            'model' => $model,
                            'url' => $url . $model->getFullViewUrl(),
                            'title' => $model->title,
                            'description' => $model->descrizione_breve,
                            'imageUrl' => !empty($model->getNewsImage()) ? $model->getNewsImage()->getWebUrl('square_small') : '',
                            'isRedationalContent' => $model->primo_piano,
                        ]);
                        ?>
                    </div>

                        <?php if ($enableLikeWidget && $model->status == News::NEWS_WORKFLOW_STATUS_VALIDATO) : ?>
                        <div class="widget-body-content">
                            <?=
                            \open20\amos\core\forms\editors\likeWidget\LikeWidget::widget([
                                'model' => $model,
                            ]);
                            ?>
                        </div>
                <?php endif; ?>
                </div>
                <?php if (\Yii::$app->getModule('correlations')) { ?>
                    <?=
                    open2\amos\correlations\widget\ListCorrelationsWidget::widget([
                        'model' => $model
                    ]);
                    ?>
<?php } ?>
                <div class="clearfix"></div>
            </div>
           

        </div>
    </div>


    <?php if (!is_null(\Yii::$app->getModule('sitemanagement')) && ($enableAgid)) : ?>
        <?php if (!empty($model->image_site_management_slider_id)) : ?>
            <?= \amos\sitemanagement\widgets\SMSliderWidget::widget(['sliderId' => $model->image_site_management_slider_id]); ?>
        <?php endif; ?>
        <?php if (!empty($model->video_site_management_slider_id)) : ?>
        <?= \amos\sitemanagement\widgets\SMSliderWidget::widget(['sliderId' => $model->video_site_management_slider_id]); ?>
    <?php endif; ?>

<?php endif; ?>

</div>
