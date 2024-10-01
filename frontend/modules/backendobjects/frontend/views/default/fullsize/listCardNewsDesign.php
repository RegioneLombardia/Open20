<?php

use yii\widgets\ListView;
use open20\amos\news\AmosNews;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use open20\design\utility\DesignUtility;
use open20\amos\core\module\BaseAmosModule;
use kartik\datecontrol\DateControl;
use luya\helpers\Url;
use app\modules\backendobjects\frontend\Module;


use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$isGuest = \Yii::$app->user->isGuest;
?>


<?php
$modelLabel = strtolower($model->getGrammar()->getModelLabel());
$hideBiPluginHeader = (strpos($cssClass, 'hide-bi-plugin-header') !== false) ? true : false;
$hideNewsCardCategory = (strpos($cssClass, 'hide-news-card-category') !== false) ? true : false;
$hideModuleBackendIfEmptyList = (strpos($cssClass, 'hide-module-if-empty-list') !== false && $dataProvider->getTotalCount() <= 0) ? true : false;

$classModuloBackend = 'modulo-backend-' . $modelLabel . ' ' . $cssClass;
if ($hideModuleBackendIfEmptyList) {
    $classModuloBackend .= ' ' . 'd-none';
}


/*$form = ActiveForm::begin([
    'action' => Url::toRoute(['/backendobjects']),
    'method' => 'get',
    'options' => [
        'id' => 'element_form_' . $model->id,
        'class' => 'form wrap-search',
        'enctype' => 'multipart/form-data',
    ]
]);
*/
?>

<!-- Filter -->
<?php if (strpos(Url::current(), 'news')) {
    if ($withSearch) {
        echo $this->render('_searchNewsDesign', ['model' => $model, 'showCategories' => true]);
    }
} ?>

<div class="<?= $classModuloBackend ?>">
    <?php if (!$hideBiPluginHeader) : ?>
        <?php
        $modelLabel = strtolower($model->getGrammar()->getModelLabel());

        //COPIA CONTENUTO DI BEFOREACTION IN CONTROLLER NEWS - TODO DA RENDERE STATIC
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosNews::t('amosnews', 'Notizie');
            $labelLinkAll = AmosNews::t('amosnews', 'Tutte le notizie');
            $urlLinkAll   = '/news/news/all-news';
            $titleLinkAll = AmosNews::t('amosnews', 'Visualizza la lista delle notizie');

            $labelSigninOrSignup = AmosNews::t('amosnews', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosNews::t(
                'amosnews',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosNews::t('amosnews', '#beforeActionCtaLogin');
            $titleSignin = AmosNews::t(
                'amosnews',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );

            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                $labelLink,
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]
            );
            $subTitleSection  = Html::tag(
                'p',
                AmosNews::t(
                    'amosnews',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );

        } else {
            $titleSection = AmosNews::t('amosnews', 'Notizie di mio interesse');
            $labelLinkAll = AmosNews::t('amosnews', 'Tutte le notizie di mio interesse');
            $urlLinkAll   = '/news/news/own-interest-news';
            $titleLinkAll = AmosNews::t('amosnews', 'Visualizza la lista delle notizie di tuo interesse');

            $subTitleSection = Html::tag('p', AmosNews::t('amosnews', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = AmosNews::t('amosnews', 'Nuova');
        $titleCreate = AmosNews::t('amosnews', 'Crea una nuova notizia');
        $labelManage = AmosNews::t('amosnews', 'Gestisci');
        $titleManage = AmosNews::t('amosnews', 'Gestisci le notizie');
        $urlCreate   = '/news/news/create';

        $manageLinks = [];
        $controller = \open20\amos\news\controllers\NewsController::class;
        if (method_exists($controller, 'getManageLinks')) {
            $manageLinks = $controller::getManageLinks();
        }
        $canCreate = \Yii::$app->user->can('NEWS_CREATE', ['model' => $model]);

        ?>
        <?=
        $this->render(
            '@vendor/open20/design/src/views/layouts/parts/bi-plugin-header',
            [
                'isGuest' => $isGuest,
                'modelLabel' => $modelLabel,
                'titleSection' => $titleSection,
                'subTitleSection' => $subTitleSection,
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'canCreate' => $canCreate,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
            ]
        );
        ?>
    <?php endif ?>
    <?php /*
    <?php if (!$withoutSearch) { ?>
        <div class="search-form mb-5 bg-100 rounded p-3">
            <div class="row">

                <?php
                foreach ($searchFields as $field) {
                    switch ($field->type) {
                        case "TEXT":
                            ?>
                            <div class="col-xs-12 col-sm-6">
                                <?= $this->render(
                                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-input-text',
                                            [
                                                'label' => $field->name,
                                                'inputId' => $field->name,
                                                'type' => 'text',
                                            
                                            ]
                                        );
                                ?>
                            </div>

                            <?php
                            break;
                        case "DATE":
                            ?>
                            <div class="col-xs-12 col-sm-6">
                                
                                    <div class="form-group">
                                        <div class="it-datepicker-wrapper">
                                            <div class="form-group mb-0">
                                            <input class="form-control it-date-datepicker it-datepicker-narrow" id="date1" type="text" required>
                                            <!--<label for="date1">< ?= Module::t('Date label')?></label>-->
                                            <div class="invalid-feedback"><?= Module::t('Per favore inserisci la data') . '.'?></div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <?php
                            break;
                    }
                }
                ?>

                <div class="col-sm-12 wrap-btn d-flex justify-content-end w-100">
                    <?= Html::a(Module::t('Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')], ['class' => 'btn btn-secondary']) ?>
                    <?= Html::submitButton(Module::t('Cerca'), ['class' => 'ml-2 btn btn-primary']) ?>
                </div>
            </div>
        </div>



    <?php }  ?>
    */ ?>
















    <div class="card-<?= $modelLabel ?>-container">
        <?php
        if ($dataProvider->getTotalCount() > 0) {

            $listViewLayout = $this->render('@vendor/open20/design/src/components/yii2/views/listViewLayout');

            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemCardNewsDesign',
                'viewParams' => [
                    'detailPage' => $detailPage,
                    'viewFields' => $viewFields,
                    'blockItemId' => $blockItemId,
                    'hideCategoryFromCMS' => $hideNewsCardCategory
                ],
                'layout' => $listViewLayout,
                'summary' => DesignUtility::getLayoutSummary($withPagination),
                'pager' => DesignUtility::listViewPagerConfig(),
                'itemOptions' => [
                    'tag' => false
                ],
                'options' => [
                    'class' => 'list-view w-100'
                ]
            ]);
        } else { ?>
            <?php if (!$isGuest) : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosNews::t('amosnews', 'Non ci sono news che corrispondono ai tuoi interessi!') ?></strong></p>
                        <?=
                        Html::a(
                            AmosNews::t('amosnews', 'Clicca qui'),
                            '/news/news/own-interest-news',
                            [
                                'title' => AmosNews::t('amosnews', 'Clicca e scopri tutte le news della piattaforma {platformName}', ['platformName' => \Yii::$app->name]),
                                'class' => 'btn btn-xs btn-primary'
                            ]
                        )
                            . ' ' .
                            AmosNews::t('amosnews', 'e scopri ora tutte le news di {platformName}', ['platformName' => \Yii::$app->name])
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosNews::t('amosnews', 'Non sono presenti news') ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>
</div>

<?php /*ActiveForm::end(); */?>
