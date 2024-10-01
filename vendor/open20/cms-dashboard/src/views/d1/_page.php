<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\helpers\Html;
use kartik\switchinput\SwitchInput;
use open20\cms\dashboard\Module;
use open20\cms\dashboard\utilities\Utility;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\icons\AmosIcons;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use open20\amos\attachments\components\CropInput;
use open20\cms\dashboard\assets\ModuleCmsDashboardAsset;

ModuleCmsDashboardAsset::register($this);

$canPublish = \Yii::$app->user->can('CMS_PUBLISH_PAGE');

$this->title = Module::t('amosdashboards', 'Crea nuova pagina');

$js = <<<JS
    
    $('#type_of_page-id').on('change', function(e){
        if($(this).val() == 2){ 
            //redirect 
            $('#modello-id input[type="radio"]').prop('checked', false);
            $('.hide-for-redirect').hide();
            $('.hide-for-page').show();
        }else{
            //normal page
            $('.hide-for-redirect').show();
            $('.hide-for-page').hide(); 
        }
    });
                

    $('#type_of_redirect-id').on('change', function(e){
        if($(this).val() == 2){
            $('#container-external-link').show();
            $('#container-internal-link').hide();
        }else{
            $('#container-external-link').hide();
            $('#container-internal-link').show();
        }
    });

JS;

$this->registerJs($js);

$isPageProtected = Utility::getNavProtection($nav->id);
$isPageReadOnly = Utility::getNavReadOnly($nav->id);

$menuQueryRedirect = Utility::getAdminMenuLuyaTree($container, true, []);

$hideForRedirect = '';
$hideForPage = 'display:none;';
if (!empty($model->type_of_page) && $model->type_of_page == 2) {
    $hideForRedirect = 'display:none;';
    $hideForPage = '';
}
if (!$model->isNewRecord) {
    $this->title = Module::t('amosdashboards', 'Proprietà pagina "{titolo}"', ['titolo' => $model->title]);
}

if (!empty($parent_id)) {
    $navParent = Utility::getNavItem($parent_id);
    if (!empty($navParent)) {
        $navParentOne = $navParent->one();
        if (!empty($navParentOne)) {
            $titleParent = $navParentOne['title'];
            if (!$model->isNewRecord) {
                $this->title = Module::t('amosdashboards', 'Modifica la sottopagina di "{nomePadre}"', ['nomePadre' => $titleParent]);
            } else {
                $this->title = Module::t('amosdashboards', 'Crea nuova sottopagina di "{nomePadre}"', ['nomePadre' => $titleParent]);
            }
        }
    }
}

//$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['forceBreadcrumbs'][] = ['label' => Module::txt('Pagine'), 'url' => ['/' . Module::getModuleName() . '/d1/pagine']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
$this->params['breadcrumbs'][] = $this->title;
if ($model->isNewRecord) {
    $this->registerJs(" 
    function getSeoUrl(title) {
        $.ajax({
            url : '/" . Module::getModuleName() . "/d1/seo-url',
                    type: 'GET',
            data: {
                'title': title
            },
            dataType: 'json',
            success: function (data) {
                $('#dynamicmodel-alias').val(data);
            },
            error: function (request, error)
            {
                
            }
        });
    }
        
    $('#dynamicmodel-title').on('change', function() {
        var title = $(this).val();
        if(title.length >= 3){
            getSeoUrl(title);
        }
    });
    ", \yii\web\View::POS_READY);
}

// id 20394: CHECK/VALIDATE FIELDS "DATE PUBBLICAZIONE" ON FOCUSOUT AND KEYUP
$this->registerJs(
        <<<JS
$("input[id*=publish_from-disp]").on("keyup", function () {
  validateDatePubblicazioneFields();
});

$("input[id*=publish_till-disp]").on("keyup", function () {
  validateDatePubblicazioneFields();
});

$("input[id*=publish_from-disp]").on("focusout", function () {
  validateDatePubblicazioneFields();
});

$("input[id*=publish_till-disp]").on("focusout", function () {
  validateDatePubblicazioneFields();
});

window.validateDatePubblicazioneFields = function () {
  setTimeout(function () {
    $('.d1-aform').yiiActiveForm('validateAttribute', 'dynamicmodel-publish_from');
    $('.d1-aform').yiiActiveForm('validateAttribute', 'dynamicmodel-publish_till');
  }, 700);
}
JS
);

$homepageExists = false;
$activeHomepage = Utility::getActiveHomepage();
if ($activeHomepage && $activeHomepage->id != $nav->id) {
    $oldHomepage = Utility::getNavItem($activeHomepage->id)->one();
    if ($oldHomepage) {
        $homepageExists = true;
        $modalIsHomeBody = Module::txt('La pagina attualmente impostata come homepage è <strong>{oldPageTitle}</strong>.<br>Sei sicuro di voler impostare la pagina <strong>{currentPageTitle}</strong> come nuova homepage?', [
                    'oldPageTitle' => $oldHomepage['title'],
                    'currentPageTitle' => $model->title
        ]);
    }
}

$jsModalIsHome = <<<JS

    var saveButton = $('#close-save-button-widget-container button[type="submit"]');
    saveButton.on('click', function(e){
        var isHomeChecked = $('#is-home').prop('checked');
        var homepageExists = '{$homepageExists}';
        if (isHomeChecked && homepageExists) {
            e.preventDefault();
            $('#modal-is-home').modal('show');
            saveButton.prop('disabled', false);
            return false;
        }
    });

JS;

$this->registerJs($jsModalIsHome);
?>
<div class="dashboards-page-create">
    <?php if (!$model->isNewRecord) { ?>
                                                                                <!-- <p>< ?= Module::t('amosdashboards', 'Modifica il seguente form e procedi alla costruzione della tua pagina web, includendo i componenti redazionali che desideri') ?></p> -->
    <?php } else { ?>
        <p class="lead m-b-0"><?= Module::t('amosdashboards', '<strong>Step 1.</strong> Inserisci le informazioni di base e completa i campi obbligatori') ?></p>
        <p class="lead"><?= Module::t('amosdashboards', '<strong>Step 2.</strong> Procedi alla costruzione dei contenuti di pagina includendo componenti redazionali tramite drag&drop.') ?></p>
    <?php } ?>

    <div class="dashboards-page-form">
        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'd1-aform']]);
        ?>

        <?php
        if ($homepageExists) {
            echo $this->render('parts/_modal_is_home', ['modalBody' => $modalIsHomeBody]);
        }
        ?>

        <?php if (!$model->isNewRecord) { ?>

            <div style="<?= $hideForRedirect ?>" id="versions">
                <div>
                    <h2 class="subtitle-form h4 m-t-30"><?= Module::t('amosdashboards', 'Versioni della pagina') ?></h2>
                </div>
                <div>
                    <?=
                    AmosGridView::widget([
                        'dataProvider' => new \yii\data\ArrayDataProvider([
                            'allModels' => $versions->all(),
                                ]),
                        'columns' => [
                            [
                                'attribute' => 'version_alias',
                                'value' => function ($model) {
                                    $online = (($model['id'] == $model['nav_item_type_id']) ? true : false);
                                    return ((($model['version_alias'] == 'Initial') ? Module::txt('Versione iniziale') : $model['version_alias'])
                                    . ($online ? ' <span class="label label-success">' . Module::txt('Versione pubblicata') . '</span>' : ''));
                                },
                                'format' => 'raw',
                                'label' => Module::txt('Nome versione'),
                            ],
                            [
                                'attribute' => 'timestamp_create',
                                'value' => function ($model) {
                                    return \Yii::$app->formatter->asDatetime($model['timestamp_create'], 'php:d/m/Y H:i');
                                },
                                'label' => Module::txt('Creata il'),
                            ],
                            [
                                'attribute' => 'user',
                                'value' => function ($model) {
                                    $userCms = Utility::getUserOpenFromCms($model['ver_create_user_id']);
                                    if (!empty($userCms)) {
                                        $user = \open20\amos\core\user\User::find()->andWhere(['email' => $userCms['email']])->one();
                                        if (!empty($user)) {
                                            $profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $user->id])->one();
                                            if (!empty($profile)) {
                                                return $profile->getNomeCognome();
                                            }
                                        }
                                    }
                                    return Module::txt('Nd');
                                    //return $model['timestamp_create'], 'php:d/m/Y H:i');
                                },
                                'label' => Module::txt('Creata da'),
                            ],
                            [
                                'class' => 'open20\amos\core\views\grid\ActionColumn',
                                'template' => '{pubblica}{delete}',
                                'buttons' => [
                                    'pubblica' => function ($url, $model) {
                                        $online = (($model['id'] == $model['nav_item_type_id']) ? true : false);
                                        $url = \yii\helpers\Url::current();
                                        if ($online) {
                                            return '';
                                        }

                                        return Html::a(
                                                AmosIcons::show('badge-check'),
                                                Yii::$app->urlManager->createUrl([
                                                    '/' . Module::getModuleName() . '/d1/publish-version',
                                                    'id' => $model['id'],
                                                    'url' => $url . '#versions',
                                                ]),
                                                [
                                                    'title' => Module::txt('Pubblica questa versione'),
                                                    'class' => 'btn btn-tool-secondary',
                                                    'data-confirm' => Module::txt('Confermi di voler pubblicare questa versione?'),
                                                ]
                                        );
                                    },
                                    'delete' => function ($url, $model) {

                                        $url = \yii\helpers\Url::current();
                                        $online = (($model['id'] == $model['nav_item_type_id']) ? true : false);
                                        if ($online) {
                                            return '';
                                        }
                                        return Html::a(AmosIcons::show('delete'), Yii::$app->urlManager->createUrl([
                                                    '/' . Module::getModuleName() . '/d1/delete-version',
                                                    'id' => $model['id'],
                                                    'url' => $url . '#versions',
                                                ]), [
                                            'title' => Module::txt('Cancella'),
                                            'class' => 'btn btn-danger-inverse',
                                            'data-confirm' => Module::txt('Sei sicuro di voler cancellare la versione? L\'operazione non è reversibile.'),
                                        ]);
                                    },
                                ],
                            ],
                        ]
                    ]);
                    ?>
                </div>

            </div>
        <?php } ?>

        <div>
            <div>
                <h2 class="subtitle-form h4 m-t-30"><?= Module::t('amosdashboards', 'Informazioni di base') ?></h2>
            </div>
            <?php if ($model->isNewRecord) { ?>
                <div>
                    <?=
                    $form->field($model, 'type_of_page')->widget(\kartik\select2\Select2::className(), [
                        'data' => [
                            1 => Module::t('amosdashboards', 'Pagina con contenuto'),
                            2 => Module::t('amosdashboards', 'Pagina con redirect'),
                        ],
                        'options' => ['id' => 'type_of_page-id']
                    ])->hint(Module::t('amosdashboards', 'Rappresenta la tipologia di pagina'));
                    ?>
                </div>
                <?php
            } else {
                $form->field($model, 'type_of_page')->hiddenInput()->label(false);
            }
            ?>
            <?php if (!$model->isNewRecord) { ?>
                <?php if (Utility::canSetHomepage($nav)) { ?>
                    <style>
                        .m-b-15 .form-group {
                            margin-bottom: 0;
                        }
                    </style>
                    <div class="row m-t-20">
                        <div class="col-xs-12 form-group m-b-15" style="display: flex; flex-direction: row; align-items: center">
                            <?=
                            Html::label(
                                    Module::txt('Imposta questa pagina come homepage'),
                                    'is-home',
                                    [
                                        'class' => 'control-label m-b-0 m-r-15',
                                    ]
                            );
                            ?>
                            <div>
                                <?=
                                $form->field($model, 'is_home')->widget(SwitchInput::classname(), [
                                    'pluginOptions' => [
                                        //                                        'size' => 'large',
                                        'onText' => Module::txt('Si'),
                                        'offText' => Module::txt('No'),
                                        'onColor' => 'success',
                                    ],
                                    'options' => ['id' => 'is-home']
                                ])->label(false);
                                ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="row m-t-20">
                <div class="col-xs-6 form-group m-b-15" style="display: flex; flex-direction: row; align-items: center">
                    <?=
                    Html::label(
                            Module::txt('Abilita protezione'),
                            'is-protected',
                            [
                                'class' => 'control-label m-b-0 m-r-15',
                            ]
                    );
                    ?>
                    <div>
                        <?=
                        $form->field($model, 'is_protected')->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                //                                        'size' => 'large',
                                'onText' => Module::txt('Si'),
                                'offText' => Module::txt('No'),
                                'onColor' => 'success'
                            ],
                            'options' => [
                                'id' => 'is-protected',
                                'checked' => $isPageProtected
                            ]
                        ])->label(false);
                        ?>
                    </div>
                </div>
                <div class="col-xs-6 form-group m-b-15" style="display: flex; flex-direction: row; align-items: center">
                    <?=
                    Html::label(
                            Module::txt('Visualizza sempre nel menu'),
                            'is-readonly',
                            [
                                'class' => 'control-label m-b-0 m-r-15',
                            ]
                    );
                    ?>
                    <div>
                        <?=
                        $form->field($model, 'is_readonly')->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                //                                        'size' => 'large',
                                'onText' => Module::txt('Si'),
                                'offText' => Module::txt('No'),
                                'onColor' => 'success'
                            ],
                            'options' => [
                                'id' => 'is-readonly',
                                'checked' => $isPageReadOnly
                            ]
                        ])->label(false);
                        ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div>
                <?= $form->field($model, 'title')->textInput()->hint(Module::t('amosdashboards', 'Rappresenta il titolo della pagina visualizzato nel menù')); ?>
            </div>
            <div>
                <?= $form->field($model, 'alias')->textInput()->label(Module::t('amosdashboards', 'Permalink'))->hint(Module::t('amosdashboards', 'Rappresenta la parte finale dell\'indirizzo web, se non compilato viene assegnato in automatico sulla base del titolo')); ?>
            </div>

        </div>
        <div>

            <div>
                <?=
                $form->field($model, 'tag_free')->widget(\xj\tagit\Tagit::className(), [
                    'clientOptions' => [
                        'tagSource' => \yii\helpers\Url::to(['/tag/manager/autocomplete-free-tag', 'id' => ($modelimage->isNewRecord ? null : $modelimage->id)]),
                        'autocomplete' => [
                            'delay' => 200,
                            'minLength' => 3,
                        ],
                        'singleField' => true,
                        'beforeTagAdded' => new \yii\web\JsExpression(
                                <<<EOF
    function(event, ui){
        if (!ui.duringInitialization) {
            /*console.log(event);
            console.log(ui);*/
        }
    }
EOF
                        ),
                    ],
                    'id' => 'tag-free_ids', //sembran doppi gli id ma servono per come lavora il plugin
                    'options' => ['id' => 'tag-free_ids'],
                ])->hint("Inserisci delle etichette di codifica per rendere più semplice la ricerca all’interno della piattaforma.");
                ?>
            </div>
        </div>  
        <?php
        if (\Yii::$app->user->can('CMS_PUBLISH_PAGE')) {
            if (Module::instance()->enableProfile) {
                ?>
                <div>
                    <div>             
                        <?=
                        $form->field($model, 'profiles_rbac')->widget(Select2::className(), [
                            'data' => \yii\helpers\ArrayHelper::map(\open20\amos\admin\models\UserProfileClasses::find()->asArray()->all(), 'id', 'name'),
                            'options' => ['placeholder' => Module::txt('Cerca ...'), 'id' => 'rbacprof'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => true,
                            /* 'minimumInputLength' => 3,
                              'ajax' => [
                              'url' => \yii\helpers\Url::to(['/dashboards/d1/ajax-profiles']),
                              'dataType' => 'json',
                              'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                              ], */
                            ],
                                ]
                        );
                        ?>
                    </div>
                </div>  
            <?php } else {
                ?>
                <div>
                    <div>             
                        <?=
                        $form->field($model, 'permission_rbac')->widget(\xj\tagit\Tagit::className(), [
                            'clientOptions' => [
                                //'tagSource' => \yii\helpers\Url::to(['/tag/manager/autocomplete-free-tag', 'id' => ($modelimage->isNewRecord ? null : $modelimage->id)]),
//                            'autocomplete' => [
//                                'delay' => 200,
//                                'minLength' => 3,
//                            ], 
                                'singleField' => true,
                            ],
                            'id' => 'perm-rbac_ids', //sembran doppi gli id ma servono per come lavora il plugin
                            'options' => ['id' => 'perm-rbac_ids'],
                        ]);
                        ?>
                    </div>
                </div>  
                <?php
            }
        }
        ?>
        <div>
            <div>
                <h2 class="subtitle-form m-t-30"><?= Module::t('amosdashboards', 'Livello nell\'alberatura del menù "{container}"', ['container' => $nameContainer]) ?></h2>
            </div>
            <?php if (empty($parent_id)) {
                ?>

                <div>
                    <?=
                    $form->field($model, 'parent_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($menuQuery->all(), 'nav0', 'title'),
                        'options' => ['multiple' => false, 'placeholder' => Module::txt('Nessuna'), 'id' => 'select-copy'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'templateResult' => new \yii\web\JsExpression("function formatState (state) {                                
                                    tree = state.text;    
                                    newTree = tree.replace(/\ \ /g, '&nbsp;&nbsp;&nbsp;');
                                    if(newTree == tree){
                                        return tree;
                                    } else {
                                    newTree = '<span class=\" d-flex \">' + tree.replace(/\ \ /g, '&nbsp;&nbsp;&nbsp;@') + '</span>';     
                                    var lastIndex = newTree.lastIndexOf('@');
                                    newTree = newTree.slice(0, lastIndex) + '<span class=\"mdi mdi-subdirectory-arrow-right m-r-10 \"></span>' + newTree.slice(lastIndex + 1);                           
                                    newTree = newTree.replace(/@/g, '&nbsp;&nbsp;&nbsp;')
                                    newTree2 = $(newTree);
                                    return newTree2;
                                }
                              }
                            "),
                        ],
                    ])->hint('Se selezionata una pagina già esistente, la pagina sarà inserita come sottopagina');
                    ?>
                </div>
                <?php if ($model->isNewRecord) { ?>
                    <div class="hide-for-redirect" style="<?= $hideForRedirect ?>">
                        <?= $form->field($model, 'modello')->radioList(Utility::getImageNavArray(), ['encode' => false, 'id' => 'modello-id']); ?>
                    </div>
                <?php } ?>
                <div class="hide-for-page" style="<?= $hideForPage ?>">
                    <?=
                    $this->render('parts/_redirect', [
                        'form' => $form,
                        'model' => $model,
                        'menuQuery' => $menuQueryRedirect
                    ]);
                    ?>
                </div>
            <?php } else { ?>
                <div>
                    <?=
                    $form->field($model, 'parent_id')->widget(\kartik\select2\Select2::className(), [
                        'data' => \yii\helpers\ArrayHelper::map(Utility::getParentPages($container), 'id', 'name'),
                        'options' => [
                            'multiple' => false,
                            'disabled' => true,
                            'value' => filter_input(INPUT_GET, 'parent_id')
                        ]
                    ]);
                    ?>
                    <?php $model->parent_id = $parent_id; ?>
                    <?= $form->field($model, 'parent_id', ['options' => ['style' => 'display:none;']])->hiddenInput()->label(false); ?>
                </div>


                <?php if ($model->isNewRecord) { ?>
                    <div class="hide-for-redirect" style="<?= $hideForRedirect ?>">
                        <h2 class="subtitle-form h4 m-t-30"><?= Module::t('amosdashboards', 'Seleziona modello di pagina') ?></h2>
                        <?=
                                $form->field($model, 'modello')
                                ->radioList(Utility::getImageNavArray(), ['encode' => false]);
                        ?>
                    </div>
                <?php } ?>
                <div class="hide-for-page" style="<?= $hideForPage ?>">
                    <div class="hide-for-page" style="<?= $hideForPage ?>">
                        <?=
                        $this->render('parts/_redirect', [
                            'form' => $form,
                            'model' => $model,
                            'menuQuery' => $menuQueryRedirect
                        ]);
                        ?>
                    </div>
                </div>
                <?= $form->field($model, 'parent_id', ['options' => ['style' => 'display:none;']])->hiddenInput()->label(false); ?>
            <?php } ?>
        </div>

        <?php
        if (\Yii::$app->getModule('socialwall')) :
            // Socialwall Block
            $socialwallSelectorWidget = \open20\socialwall\widgets\SocialwallSelectorWidget::widget([
                        'form' => $form,
                        'model' => $model,
                        'attribute' => 'socialwall',
                        'explicitModuleId' => 'cms',
                        'explicitModuleRecordId' => $nav->id,
            ]);
        endif;
        if (
                !is_null(\Yii::$app->getModule('socialwall')) &&
                !empty($socialwallSelectorWidget)
        ) :
            ?>
            <!-- SOCIAL WALL -->
            <div class="row">
                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form"><?= Module::t('amosdashboards', 'Social Wall') ?></h2>

                    <?= $socialwallSelectorWidget; ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-xs-12">
                    <h2 class="subtitle-form h4 m-t-30"><?= Module::t('amosdashboards', 'Impostazioni per la pubblicazione programmata') ?></h2>
                </div>
                <div class="col-xs-12 col-md-6">
                    <?php
                    $tooltipDataPubblicazione = '<span class="mdi mdi-information-outline" title="' . Module::t('amosdashboards', 'Se non impostata la data di pubblicazione, la pagina sarà pubblicata immediatamente al passaggio in stato Pubblicata') . '" data-toggle="tooltip"></span>';
                    ?>
                    <?=
                    $form->field($model, 'publish_from')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATETIME,
                        'saveFormat' => 'php:U',
                        'ajaxConversion' => true,
                        'disabled' => (!empty($nav) && !$nav->is_offline),
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'startDate' => date('Y-m-d'),
                                'autoclose' => true,
                            ],
                            'options' => [
                                'placeholder' => Module::txt('Pubblicazione immediata'),
                            ],
                            'pluginEvents' => [
                                "changeDate" => "function () { validateDatePubblicazioneFields(); }",
                            ],
                        ]
                    ])->label(Module::t('amosdashboards', 'Data inizio pubblicazione'))->hint(Module::t('amosdashboards', 'La pagina sarà raggiungibile tramite url a partire da questa data {tooltipDataPubblicazione}', [
                                'tooltipDataPubblicazione' => $tooltipDataPubblicazione
                    ]));
                    ?>
                </div>
                <div class="col-xs-12 col-md-6">
                    <?=
                    $form->field($model, 'publish_till')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATETIME,
                        'saveFormat' => 'php:U',
                        'ajaxConversion' => true,
                        'disabled' => !((!empty($nav) && $nav->is_offline) || $canPublish),
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'startDate' => date('Y-m-d'),
                                'autoclose' => true
                            ],
                            'options' => [
                                'placeholder' => Module::txt('Sempre pubblica'),
                            ],
                            'pluginEvents' => [
                                "changeDate" => "function () { validateDatePubblicazioneFields(); }",
                            ],
                        ]
                    ])->hint(Module::t('amosdashboards', 'La pagina non sarà più raggiungibile tramite url a partire da questa data'));
                    ?>

                </div>

            </div>

            <div class="panel-group accordionPage m-t-30" id="accordionPage" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h2 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordionPage" href="#collapsePage" aria-expanded="false" aria-controls="collapsePage">
                                <?= Module::t('amosdashboards', 'Search Engine Optimization') ?>
                                <span class="mdi mdi-chevron-down"></span>
                            </a>
                        </h2>
                    </div>
                    <div id="collapsePage" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">


                            <div>

                                <div>
                                    <?php
                                    $tooltipMetaTitle = '<span class="mdi mdi-information-outline" title="' . Module::t('amosdashboards', 'Se lasciato vuoto verrà utilizzato in automatico il contenuto del campo titolo') . '" data-toggle="tooltip"></span>';
                                    ?>
                                    <?=
                                    $form->field($modelimage, 'title_tag')->textInput()->label(Module::t('amosdashboards', 'Meta Tag Title'))->hint(Module::t('amosdashboards', 'Specifica il titolo di una pagina web, visualizzato nelle ricerche e nella condivisione social {tooltipMetaTitle}', [
                                                'tooltipMetaTitle' => $tooltipMetaTitle
                                    ]));
                                    ?>
                                </div>
                                <div>
                                    <?=
                                    $form->field($modelimage, 'description')->textArea(['rows' => 1])->label(Module::t('amosdashboards', 'Meta Tag Description'))->hint(Module::t('amosdashboards', 'Fornisce una descrizione breve e completa della pagina web nelle ricerche'));
                                    ?>
                                </div>
                                <div>
                                    <?php
                                    $form->field($modelimage, 'keywords')->widget(\xj\tagit\Tagit::className(), [
                                        'clientOptions' => [
                                            'allowSpaces' => true,
                                            'autocomplete' => [
                                                'delay' => 200,
                                                'minLength' => 3,
                                            ],
                                            'singleField' => false,
                                            'beforeTagAdded' => new \yii\web\JsExpression(
                                                    <<<EOF
    function(event, ui){
        if (!ui.duringInitialization) {
            /*console.log(event);
            console.log(ui);*/
        }
    }
EOF
                                            ),
                                        ],
                                        'id' => 'keywords-seo_ids', //sembran doppi gli id ma servono per come lavora il plugin
                                        'options' => ['id' => 'keywords-seo_ids'],
                                    ])->label(Module::t('amosdashboards', 'Meta Tag Keywords'))->hint(Module::t('amosdashboards', 'Lista di parole chiave, anche composte, riconducibili agli argomenti trattati nella pagina web'));
                                    ?>
                                </div>
                                <div>
                                    <?php /*
                                      $form->field(
                                      $modelimage,
                                      'seo_image'
                                      )->widget(\open20\amos\attachments\components\AttachmentsInput::classname(), [
                                      'options' => [ // Options of the Kartik's FileInput widget
                                      'multiple' => false, // If you want to allow multiple upload, default to false
                                      'accept' => "image/*"
                                      ],
                                      'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                                      'maxFileCount' => 1,
                                      'showRemove' => false, // Client max files,
                                      'indicatorNew' => false,
                                      'allowedPreviewTypes' => ['image'],
                                      'previewFileIconSettings' => false,
                                      'overwriteInitial' => false,
                                      'layoutTemplates' => false
                                      ],
                                      'enableUploadFormDatabankFile' => false
                                      ])->label(Module::t('amosdashboards', 'Image'))->hint(Module::t('amosdashboards', 'Immagine visualizzata nei risultati di ricerca e nelle condivisioni social'));
                                      ?>
                                     */
                                    ?>

                                    <?= $form->field($modelimage, 'seo_image')->widget(CropInput::class, ['hidePreviewDeleteButton' => $hidePreviewDeleteButton, 'jcropOptions' => ['aspectRatio' => '1.7']])->label(Module::t('amosdashboards', 'OG image'))->hint(Module::t('amosdashboards', 'Immagine visualizzata nei risultati di ricerca e nelle condivisioni social')) ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <div>
                <div class="m-t-30 note_asterisk">
                    <p><?= Module::txt('I campi <span class="red">*</span> sono obbligatori.') ?></p>
                </div>
            </div>


            <div id="close-save-button-widget-container">

                <div class="hide-for-redirect" style="<?= $hideForRedirect ?>">
                    <?=
                    CloseSaveButtonWidget::widget([
                        'model' => $model,
                        'buttonNewSaveLabel' => Module::txt('Procedi alla costruzione'),
                        'buttonSaveLabel' => Module::txt('Salva'),
                        'closeButtonLabel' => Module::txt('Indietro'),
                        'urlClose' => (!empty(filter_input(INPUT_GET, 'url')) ? filter_input(INPUT_GET, 'url') : ['/' . \open20\cms\dashboard\Module::getModuleName() . '/d1/pagine?container=' . $container . (!is_null($parent_id) ? '&openPage=' . $parent_id : '')]),
                    ]);
                    ?>
                </div>
                <div class="hide-for-page" style="<?= $hideForPage ?>">
                    <?=
                    CloseSaveButtonWidget::widget([
                        'model' => $model,
                        //'buttonNewSaveLabel' => Module::txt('Procedi alla costruzione'),
                        'buttonSaveLabel' => Module::txt('Salva'),
                        'closeButtonLabel' => Module::txt('Indietro'),
                        'urlClose' => (!empty(filter_input(INPUT_GET, 'url')) ? filter_input(INPUT_GET, 'url') : ['/' . \open20\cms\dashboard\Module::getModuleName() . '/d1/pagine?container=' . $container . (!is_null($parent_id) ? '&openPage=' . $parent_id : '')]),
                    ]);
                    ?>
                </div>

            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="clearfix"></div>
    </div>