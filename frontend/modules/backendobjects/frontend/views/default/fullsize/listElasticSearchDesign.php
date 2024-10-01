<?php

use yii\widgets\ListView;
use open20\design\assets\BootstrapItaliaDesignAsset;
use luya\helpers\Url;
use open20\design\components\bootstrapitalia\ActiveForm;
use open20\amos\core\module\BaseAmosModule;
use open20\design\utility\DesignUtility;
use app\modules\cms\helpers\CmsHelper;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
?>

<style>
    /* clears the 'X' from Internet Explorer */
    input[type="search"]::-ms-clear {
        display: none;
        width: 0;
        height: 0;
    }

    input[type="search"]::-ms-reveal {
        display: none;
        width: 0;
        height: 0;
    }

    /* clears the 'X' from Chrome */
    input[type="search"]::-webkit-search-decoration,
    input[type="search"]::-webkit-search-cancel-button,
    input[type="search"]::-webkit-search-results-button,
    input[type="search"]::-webkit-search-results-decoration {
        display: none;
        -webkit-appearance: none;
    }
</style>

<?php
$form = ActiveForm::begin([
    'action' => Url::toRoute(['/backendobjects']),
    'method' => 'get',
    'options' => [
        'id' => 'element_form_search',
        'class' => 'form wrap-search',
        'enctype' => 'multipart/form-data',
    ]
]);
?>
<div class="container py-4">

    <?=
    $this->render('@vendor/open20/design/src/components/bootstrapitalia/views/bi-back-button');
    ?>

    <h1><?= BaseAmosModule::t('amosapp', 'Cerca') ?></h1>

    <div class="mt-4 d-flex flex-column">
        <div class="full-text-src">
            <?=
            $this->render(
                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-input-search',
                [
                    'name' => 'searchtext',
                    'inputId' => 'SearchDesign',
                    'type' => 'search',
                    'value' => \app\modules\cms\helpers\CmsHelper::itemQueryStringValue('searchtext')
                ]
            );
            ?>
        </div>
    </div>

    <!--dataprovider-->
    <div class="modulo-backend-search py-4">
        <div class="list-search-container d-flex flex-wrap">
            <?php
            if (!is_null($dataProvider)) {
                if ($dataProvider->getTotalCount() > 0) {

                    $listViewLayout = $this->render(
                        '@vendor/open20/design/src/components/yii2/views/listViewLayoutBiList',
                        [
                            'customListClass' => 'list-search'
                        ]
                    );
                    echo ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_itemElasticSearchDesign',
                        'viewParams' => [
                            'detailPage' => $detailPage,
                            'viewFields' => $viewFields,
                            'blockItemId' => $blockItemId,
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
                    <div class="no-elastic-search-alert">
                        <div class="alert alert-warning" role="alert">
                            <p class="mb-0"><strong><?= BaseAmosModule::t('amosapp', 'Non ci sono risultati che corrispondono alla ricerca effettuata') ?></strong></p>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="no-search">
                    <p>
                        <?= BaseAmosModule::t('amosapp', 'Per effettuare una ricerca inserisci nella riga in alto parole o frasi quali tag, argomenti, titoli, community,... e premi INVIO sulla tastiera oppure fai clic sull\'icona della lente.') ?>
                    </p>
                    <div class="vh-50">
                        <?php
                        $url = '/img/cerca.png';

                        $contentImage = CmsHelper::img($url, [
                            'class' => 'el-image',
                        ]);
                        ?>
                        <?= $contentImage; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</div>


<?php ActiveForm::end();
