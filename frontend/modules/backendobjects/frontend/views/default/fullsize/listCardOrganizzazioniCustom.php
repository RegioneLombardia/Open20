<?php

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use yii\widgets\ListView;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignUtility;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\Pjax;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$js = <<<JS
    // Show loader on page change
    $('.card-organizzazioni-container').on('click', '.pagination', function () {
        $('#user-mia-organizzazione .list-view.w-100 .list-view-design.row.it-list-wrapper').hide();
        $('#loader').show();
    });
JS;
$this->registerJs($js);

$isGuest = \Yii::$app->user->isGuest;

if (!$isGuest) {

    $isUserEmployee = false;
    $userOrganizzazione = ProfiloUserMm::find()
            ->innerJoin('profilo', 'profilo.id = profilo_user_mm.profilo_id')
            ->andWhere(['profilo.deleted_at' => null])
            ->andWhere(['profilo_user_mm.user_id' => Yii::$app->user->id])
            ->andWhere(['profilo_user_mm.status' => ProfiloUserMm::STATUS_ACTIVE])
            ->one();
    if ($userOrganizzazione && $userOrganizzazione->profilo_id) {
        $isUserEmployee = true;
        $query = \open20\amos\admin\models\UserProfile::find()
                ->innerJoin('profilo_user_mm', 'profilo_user_mm.user_id = user_profile.user_id')
                ->innerJoin('profilo', 'profilo.id = profilo_user_mm.profilo_id')
                ->andWhere(['profilo.deleted_at' => null])
                ->andWhere(['profilo_user_mm.profilo_id' => $userOrganizzazione->profilo_id])
                ->andWhere(['profilo_user_mm.status' => ProfiloUserMm::STATUS_ACTIVE])
                ->andWhere(['user_profile.attivo' => true])
                //            ->andWhere(['!=', 'user_profile.user_id', Yii::$app->user->id])
                ->orderBy(['id' => SORT_ASC])
                ->distinct();
        $modelOrganizzazioni =  \open20\amos\organizzazioni\models\Profilo::findOne($userOrganizzazione->profilo_id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 6
            ]
        ]);
    }

    if (!empty($dataProvider) && $dataProvider->getTotalCount() > 0) {
        if ($dataProvider->getCount() > 3) {
            $loaderContainerMinHeight = '226px';
            $loaderPadding = '85px';
        } else {
            $loaderContainerMinHeight = '122px';
            $loaderPadding = '30px';
        }
    }

    $modelLabel = strtolower($model->getGrammar()->getModelLabel());
    $hideBiPluginHeader = (strpos($cssClass, 'hide-bi-plugin-header') !== false) ? true : false;
    $hideSearch = strpos($cssClass, 'hide-search') !== false;
    $hideModuleBackendIfEmptyList = (strpos($cssClass, 'hide-module-if-empty-list') !== false && $dataProvider->getTotalCount() <= 0) ? true : false;

    $classModuloBackend = 'modulo-backend-' . $modelLabel . ' ' . $cssClass;
    if ($hideModuleBackendIfEmptyList) {
        $classModuloBackend .= ' ' . 'd-none';
    }
    ?>

    <div class="<?= $classModuloBackend ?>">
        <?php
        if (!$hideBiPluginHeader) {
            $titleSection = AmosAdmin::t('amosadmin', 'LA MIA ORGANIZZAZIONE');

            echo $this->render(
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
                        'hideCreate' => true
                    ]
            );
        }
        ?>

        <?php
        if ($isUserEmployee) {
            if (isset($modelOrganizzazioni) && !is_null($modelOrganizzazioni->logoOrganization)) {
                $url = $modelOrganizzazioni->logoOrganization->getUrl('original', [
                    'class' => 'img-responsive'
                ]);
            } else {
                $url = '/img/img_default.jpg';
            }
            ?>
            <div class="row mb-2 mt-4">
                <div class="col-md-7">
                    <div class="d-flex align-items-center">
                        <div class="mr-4">
                            <?= Html::img($url, ['alt' => $modelOrganizzazioni->name, 'class' => 'img-responsive', 'width' => '100px']); ?>

                        </div>
                        <div>
                            <h3 class="mb-0"><?= $modelOrganizzazioni->name ?></h3>
                            <span><?= substr($modelOrganizzazioni->presentazione_della_organizzaz, 0, 100) . '...' ?></span>
                        </div>
                    </div>

                </div>
                <div class="col-md-5 text-md-right mt-3">
                    <?php
                    if (!empty($modelOrganizzazioni->community_id)) {
                        echo Html::a(
                                Yii::t('app', 'Community organizzazione'),
                                ['/community/join/open-join', 'id' => $modelOrganizzazioni->community_id],
                                [
                                    'class' => 'btn btn-xs btn-outline-primary',
                                    'title' => Yii::t('app', 'Vai alla community dedicata a ') . $modelOrganizzazioni->name
                                ]
                        );
                    }
                    ?>
                    <?=
                    Html::a(
                            Yii::t('app', 'Vai all\'organizzazione'),
                            ['/organizzazioni/profilo/view', 'id' => $modelOrganizzazioni->id],
                            [
                                'class' => 'btn btn-xs btn-primary',
                                'title' => Yii::t('app', 'Vai all\'organizzazione ') . $modelOrganizzazioni->name
                            ]
                    )
                    ?>
                </div>
            </div>
            <hr class="mb-4">

            <div class="card-<?= $modelLabel ?>-container">
                <?php
                if ($dataProvider->getTotalCount() > 0) {

                    Pjax::begin(['id' => 'user-mia-organizzazione', 'timeout' => 15000]);
                    ?>

                    <!-- LOADING -->
                    <div class="container " id="loader" style="display: none; min-height: <?= $loaderContainerMinHeight ?>;">
                        <div class="d-flex justify-content-center" style="padding-top: <?= $loaderPadding ?>">
                            <div class="progress-spinner progress-spinner-active">
                                <span class="sr-only">Caricamento...</span>
                            </div>
                        </div>
                    </div>

                    <?php
                    $listViewLayout = $this->render('@vendor/open20/design/src/components/yii2/views/listViewLayout');

                    echo ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_itemCardOrganizzazioniCustom',
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

                    Pjax::end();
                }
                ?>
            </div>
        <?php } else { ?>
            <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                <div class="alert alert-warning" role="alert">
                    <div><strong><?= Yii::t('app', 'Non sei ancora collegato a nessuna organizzazione!'); ?></strong></div>
                    <div class="mt-2">
                        <?=
                        Html::a(
                                AmosAdmin::t('amosadmin', 'Cerca la tua organizzazione'),
                                '/organizzazioni/profilo',
                                [
                                    'title' => Yii::t('app', 'Vedi tutti le organizzazioni'),
                                    'class' => 'btn btn-xs btn-primary'
                                ]
                        ) . ' ' . Yii::t('app', 'e collegati a quella a cui appartieni');
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>