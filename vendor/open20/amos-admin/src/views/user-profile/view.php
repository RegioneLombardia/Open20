<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;


/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\UserProfile $model
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

\open20\amos\admin\assets\AmosAsset::register($this);

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;
$idTabAdministration = 'tab-administration';

$enableUserContacts = AmosAdmin::getInstance()->enableUserContacts;
$hideContactsInView = AmosAdmin::getInstance()->hideContactsInView;
$accordionNetworkOpenOnDefault = AmosAdmin::getInstance()->accordionNetworkOpenOnDefault;

$userCanChangeWorkflow = Yii::$app->user->can('CHANGE_USERPROFILE_WORKFLOW_STATUS');

if($accordionNetworkOpenOnDefault) {
    $js = <<<JS
$(document).ready(function(){
        $('#accordion-network-title').trigger('click');
});
JS;
    $this->registerJs($js);
}


//if($userCanChangeWorkflow) {
//    if ($model->status != UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) {
//        echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
//            'model' => $model,
//            'workflowId' => UserProfile::USERPROFILE_WORKFLOW,
//            'classDivMessage' => 'message',
//            'viewWidgetOnNewRecord' => true
//        ]);
//    }
//}

?>

<div class="profile">
    <!-- HEADER -->
    <div class="col-xs-12 info-view-header nop">
        <div class="col-md-3 col-sm-4 col-xs-12 nop">
            <div class="img-profile">
                <?php if (($adminModule->confManager->isVisibleBox('box_foto', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                    ($adminModule->confManager->isVisibleField('userProfileImage',
                        ConfigurationManager::VIEW_TYPE_VIEW))
                ): ?>
                    <?php
                    $url = $model->getAvatarUrl('original');
                    Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
                    try {
                        $getHorizontalImageClass = Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)['class'];
                    } catch (\Exception $ex) {
                        $getHorizontalImageClass = '';
                    }
                    ?>
                    <?= Html::img($url, [
                        'class' => 'img-responsive ' . $getHorizontalImageClass,
                        'alt' => AmosAdmin::t('amosadmin', 'Immagine del profilo')
                    ]); ?>
                <?php endif; ?>
                <div class="under-img">
                    <!-- IMPERSONATE -->
                    <?php
                    if ($model->user_id != Yii::$app->user->id && Yii::$app->user->can('IMPERSONATE_USERS')) {
                        echo Html::a(
                            AmosIcons::show('assignment-account', ['class' => 'btn-cancel-search']) . AmosAdmin::t('amosadmin', 'Impersonate'),
                            \Yii::$app->urlManager->createUrl(['/'.AmosAdmin::getModuleName().'/security/impersonate',
                                'user_id' => $model->user_id
                            ]),
                            ['class' => 'btn btn-action-primary']
                        );
                    }
                    ?>
                    <!-- end IMPERSONATE -->

                    <?php if ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)): ?>
                        <?php if ($model->nome): ?>
                            <h2><?= $model->nome ?>
                                <?php if ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW)): ?>
                                    <?= ($model->cognome ? $model->cognome : '') ?>
                                <?php endif; ?>
                            </h2>
                        <?php endif; ?>
                    <?php endif; ?>


                    <?php if ($model->validato_almeno_una_volta): ?>
                        <div class="container-info-icons">
                            <?php
                            if ($model->isFacilitator()) {
                                //TODO replace account with man dressing tie and jacket
                                $facilitatorIcon = AmosIcons::show('account', ['class' => 'am-2', 'title' => AmosAdmin::t('amosadmin', 'Facilitator')]);
                                echo Html::tag('div', $facilitatorIcon . AmosAdmin::t('amosadmin', 'Facilitator'), ['class' => 'facilitator']);
                            }
                            $googleContactIcon = \open20\amos\admin\widgets\GoogleContactWidget::widget(['model' => $model]);
                            if (!empty($googleContactIcon)) {
                                echo Html::tag('div', $googleContactIcon . AmosAdmin::t('amosadmin', 'Google Contact'), ['class' => 'google-contact']);
                            }

                            $title = AmosAdmin::t('amosadmin', 'Profile Active');
                            if ($model->status == \open20\amos\admin\models\UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) {
                                $title = AmosAdmin::t('amosadmin', 'Profile Validated');
                            }
                            //TODO replace check-all with cockade
                            echo Html::tag('div', AmosIcons::show('check-all', ['class' => 'am-2', 'title' => $title]) . $title, ['class' => 'col-xs-12 nop']);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-sm-8 col-xs-12">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/" . AmosAdmin::getModuleName() . "/user-profile/update?id=" . $model->id,
                'disableDelete' => true
            ]) ?>
            <!-- SCHEDA -->
            <section class="wrap-details">
                <?php if (
                    ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                    ($adminModule->confManager->isVisibleField('presentazione_breve', ConfigurationManager::VIEW_TYPE_VIEW))
                    && $model->presentazione_breve
                ): ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $model->getAttributeLabel('presentazione_breve') ?></div>
                        <div class="col-md-9 col-sm-8 col-xs-12"><?= strip_tags($model->presentazione_breve) ?></div>
                    </div>
                <?php endif; ?>
                <?php if (
                    ($adminModule->confManager->isVisibleBox('box_presentazione_personale', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                    ($adminModule->confManager->isVisibleField('presentazione_personale', ConfigurationManager::VIEW_TYPE_VIEW))
                    && $model->presentazione_personale
                ): ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $model->getAttributeLabel('presentazione_personale') ?></div>
                        <div class="col-md-9 col-sm-8 col-xs-12"><?= $model->presentazione_personale ?></div>
                    </div>
                <?php endif; ?>
                <!--                --><?php //if ( $adminModule->confManager->isVisibleBox('box_dati_contatto', ConfigurationManager::VIEW_TYPE_VIEW)): ?>
                <?php if ($adminModule->confManager->isVisibleField('email', ConfigurationManager::VIEW_TYPE_VIEW)): ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $model->getAttributeLabel('email') ?></div>
                        <div class="col-md-9 col-sm-8 col-xs-12"><?= $model->user->email ?></div>
                    </div>
                <?php endif; ?>
                <?php if ($adminModule->confManager->isVisibleField('telefono', ConfigurationManager::VIEW_TYPE_VIEW)): ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $model->getAttributeLabel('telefono') ?></div>
                        <div class="col-md-9 col-sm-8 col-xs-12"><?= !empty($model->telefono) ? $model->telefono : AmosAdmin::tHtml('amosadmin', 'Non presente') ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($adminModule->confManager->isVisibleField('email_pec', ConfigurationManager::VIEW_TYPE_VIEW)): ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $model->getAttributeLabel('email_pec') ?></div>
                        <div class="col-md-9 col-sm-8 col-xs-12"><?= $model->email_pec ?></div>
                    </div>
                <?php endif; ?>
                <!--                --><?php //endif; ?>
                <?php if (
                    ($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                    ($adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_VIEW))
                ): ?>
                    <div class="row prevalent-partnership-section">
                        <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= AmosAdmin::t('amosadmin', 'Partnership') ?></div>
                        <div class="col-md-9 col-sm-8 col-xs-12 wrap-partnership">
                            <?php if (!is_null($model->prevalentPartnership)) { ?>
                                <div class="img-profile">
                                    <?php
                                    // TODO da modificare quando ci sarà terminato il nuovo plugin organizzazioni
                                    $url = '/img/img_default.jpg';
                                    if (isset($model->prevalentPartnership) && isset($model->prevalentPartnership->logoOrganization)) {
                                        $url = $model->prevalentPartnership->logoOrganization->getUrl('profile_view_users', false, true);
                                    }
                                    echo Html::img($url, ['class' => 'img-responsive']);
                                    ?>
                                </div>
                                <div><strong><?= $model->prevalentPartnership->name ?></strong></div>
                                <!-- TODO tipologia organizzazione quando sarà presente -->
                                <!-- TODO referente operativo quando sarà presente -->
                            <?php } else { ?>
                                <div class="col-xs-12 nop">
                                    <?= AmosAdmin::tHtml('amosadmin', 'Prevalent partnership not specified') ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                if(class_exists('\open20\amos\community\models\CommunityUserField')){
                    $fields = \open20\amos\community\utilities\CommunityUserFieldUtility::getCommunityUserFieldValues();
                    foreach ($fields as $field){
                        $value = $field->getCommunityUserFieldVals($model->user_id)->one();
                        if(!empty($value->value)) {
                            ?>
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $field->description ?></div>
                                <div class="col-md-9 col-sm-8 col-xs-12"><?= $value->value ?></div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </section>
            <!-- end SCHEDA -->

            <?php if ($enableUserContacts && Yii::$app->user->id != $model->user_id && !$hideContactsInView): ?>
                <?= \open20\amos\admin\widgets\ConnectToUserWidget::widget([
                    'model' => $model,
                    'isProfileView' => true,
                    'btnClass' => 'btn btn-primary'
                ]) ?>
            <?php endif; ?>

        </div>
    </div>

    <!-- BODY -->
    <div class="col-xs-12 nop info-view-body">
        <div class="col-md-8 col-xs-12">
            <!-- NETWORK -->
            <?php
            $accordionNetwork = '';

            $moduleCwh = Yii::$app->getModule('cwh');
            if ($enableUserContacts && $model->validato_almeno_una_volta && !$hideContactsInView) {
                $accordionUserContacts = \open20\amos\admin\widgets\UserContacsWidget::widget([
                    'userId' => $model->user_id,
                    'isUpdate' => false
                ]);

                echo AccordionWidget::widget([
                    'items' => [
                        [
                            'header' => AmosAdmin::t('amosadmin', '#view_accordion_user_contacts'),
                            'content' => $accordionUserContacts,
                        ]
                    ],
                    'headerOptions' => ['tag' => 'h2'],
                    'clientOptions' => [
                        'collapsible' => true,
                        'active' => false,
                        'icons' => [
                            'header' => 'ui-icon-amos am am-plus-square',
                            'activeHeader' => 'ui-icon-amos am am-minus-square',
                        ]
                    ],
                    'options' => [
                        'class' => 'user-contacts-accordion'
                    ]
                ]);
            }

            if (isset($moduleCwh)) {
                $accordionNetwork = \open20\amos\cwh\widgets\UserNetworkWidget::widget([
                    'userId' => $model->user_id,
                    'isUpdate' => false
                ]);
            
                echo AccordionWidget::widget([
                'items' => [
                    [
                        'header' => AmosAdmin::t('amosadmin', '#view_accordion_network'),
                        'content' => $accordionNetwork,
                    ]
                ],
                'headerOptions' => ['tag' => 'h2', 'id'=> 'accordion-network-title'],
                'clientOptions' => [
                    'collapsible' => true,
                    'active' => false,
                    'icons' => [
                        'header' => 'ui-icon-amos am am-plus-square',
                        'activeHeader' => 'ui-icon-amos am am-minus-square',
                    ]
                ],
                'options' => [
                    'class' => 'sede-accordion'
                ]
            ]);
            }
            ?>
            <!-- end NETWORK -->

            <!-- ADMIN - PRIVILEGES -->
            <?php if (Yii::$app->user->can('PRIVILEGES_MANAGER')) {
                $accordionAdmin = '';
                $privilegesModule = Yii::$app->getModule('privileges');
                if (!empty($privilegesModule)) {
                    $accordionAdmin = \open20\amos\privileges\widgets\UserPrivilegesWidget::widget(['userId' => $model->user_id]);
                }
                
                echo AccordionWidget::widget([
                    'items' => [
                        [
                            'header' => AmosAdmin::t('amosadmin', '#view_accordion_admin'),
                            'content' => $accordionAdmin,
                        ]
                    ],
                    'headerOptions' => ['tag' => 'h2'],
                    'clientOptions' => [
                        'collapsible' => true,
                        'active' => false,
                        'icons' => [
                            'header' => 'ui-icon-amos am am-plus-square',
                            'activeHeader' => 'ui-icon-amos am am-minus-square',
                        ]
                    ],
                    'options' => [
                        'class' => 'sede-accordion'
                    ]
                ]);
            } ?>
            <!-- end ADMIN - PRIVILEGES -->
        </div>

        <?php if (Yii::$app->user->can('VIEW_TAG_TABS_PERMISSION')): ?>
            <div class="col-md-4 col-xs-12">
                <!-- AREE INTERESSE -->
                <?php if (\Yii::$app->getModule('tag')): ?>
                    <div class="col-xs-12 tags-section-sidebar nop" id="section-tags">
                        <?= Html::tag('h2', AmosIcons::show('tag', [], 'dash') . AmosAdmin::t('amosadmin', '#tags_title')) ?>
                        <div class="col-xs-12">
                            <?= \open20\amos\core\forms\ListTagsWidget::widget([
                                'userProfile' => $model->id,
                                'className' => $model->className(),
                                'viewFilesCounter' => true,
                            ]);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- end AREE INTERESSE -->
            </div>
        <?php endif; ?>
    </div>
    <?= Html::a(AmosAdmin::t('amosadmin', '#go_back'), (Yii::$app->session->get('previousUrl') ?: \Yii::$app->request->referrer), [
        'class' => 'btn btn-secondary pull-left'
    ]) ?>
</div>
