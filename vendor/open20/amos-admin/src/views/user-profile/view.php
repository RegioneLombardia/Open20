<?php

/**
 * Aria S.p.A.
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
use open20\amos\core\utilities\StringUtils;
use open20\amos\layout\assets\BaseAsset;
use open20\amos\layout\Module;
use open20\amos\admin\models\base\UserProfile;
use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\UserProfile $model
 */
$this->title = $model;
$this->params['titleSection'] = AmosAdmin::t('amosadmin', 'Il mio profilo');
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Partecipanti'), 'url' => ['/' . AmosAdmin::getModuleName() . '/user-profile/validated-users']];
$this->params['breadcrumbs'][] = ['label' => $model->nomeCognome];

\open20\amos\admin\assets\AmosAsset::register($this);

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();
$idTabAdministration = 'tab-administration';

$enableUserContacts = $adminModule->enableUserContacts;
$hideContactsInView = $adminModule->hideContactsInView;
$accordionNetworkOpenOnDefault = $adminModule->accordionNetworkOpenOnDefault;

$userCanChangeWorkflow = (!$adminModule->completeBypassWorkflow && Yii::$app->user->can('CHANGE_USERPROFILE_WORKFLOW_STATUS'));
$asset = BaseAsset::register($this);

if ($accordionNetworkOpenOnDefault) {
    $js = <<<JS
$(document).ready(function(){
        $('#accordion-network-title').trigger('click');
});
JS;
    $this->registerJs($js);
}


$jsReadMore = <<< JS

$("#moreTextJs .changeContentJs > .actionChangeContentJs").click(function(){
    $("#moreTextJs .changeContentJs").toggle();
    $('html, body').animate({scrollTop: $('#moreTextJs').offset().top - 120},1000);
});
JS;
$this->registerJs($jsReadMore);

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
<?php /* OLD VIEW
  <div class="profile">
  <!-- HEADER -->
  <div class="info-view-header row">
  <div class="col-md-3 col-sm-4 col-xs-12">
  <div class="img-profile">
  <div class="img-profile-circle">
  <?php if (($adminModule->confManager->isVisibleBox('box_foto', ConfigurationManager::VIEW_TYPE_VIEW)) &&
  ($adminModule->confManager->isVisibleField('userProfileImage',
  ConfigurationManager::VIEW_TYPE_VIEW))
  ): ?>
  <?php
  $url = $model->getAvatarUrl('original');
  Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
  try {
  $getHorizontalImageClass = (!empty($model->userProfileImage)? Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)['class'] : '');
  } catch (\Exception $ex) {
  $getHorizontalImageClass = '';
  }
  ?>
  <?= Html::img($url, [
  'class' => 'img-responsive ' . $getHorizontalImageClass,
  'alt' => AmosAdmin::t('amosadmin', 'Immagine del profilo')
  ]); ?>
  <?php endif; ?>
  </div>
  <div class="under-img">
  <!-- IMPERSONATE -->
  <?php
  if ($model->user_id != Yii::$app->user->id && Yii::$app->user->can('IMPERSONATE_USERS')) {
  $user = new \open20\amos\core\user\AmosUser();
  $user->setIdentity(
  \open20\amos\core\user\User::findOne(['id' => $model->user_id])
  );
  if(Yii::$app->user->can('ADMIN') || !$user->can("ADMIN")){
  echo Html::a(
  AmosIcons::show('assignment-account', ['class' => 'btn-cancel-search']) . AmosAdmin::t('amosadmin', 'Impersonate'),
  \Yii::$app->urlManager->createUrl(['/'.AmosAdmin::getModuleName().'/security/impersonate',
  'user_id' => $model->user_id
  ]),
  ['class' => 'btn btn-action-primary']
  );
  }
  }
  ?>
  <!-- end IMPERSONATE -->


  <?php if ($model->validato_almeno_una_volta): ?>
  <div class="container-info-icons text-info m-t-20">
  <?php
  if ($model->isFacilitator()) {
  $facilitatorIcon = AmosIcons::show('pin-assistant', ['class' => 'am-1', 'title' => AmosAdmin::t('amosadmin', 'Facilitator')]);
  echo Html::tag('div', $facilitatorIcon . AmosAdmin::t('amosadmin', 'Facilitator'), ['class' => 'facilitator']);
  }
  $googleContactIcon = \open20\amos\admin\widgets\GoogleContactWidget::widget(['model' => $model]);
  if (!empty($googleContactIcon)) {
  echo Html::tag('div', $googleContactIcon . AmosAdmin::t('amosadmin', 'Google Contact'), ['class' => 'google-contact']);
  }

  if (!$adminModule->completeBypassWorkflow) {
  $title = AmosAdmin::t('amosadmin', 'Profile Active');
  if ($model->status == \open20\amos\admin\models\UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) {
  $title = AmosAdmin::t('amosadmin', 'Profile Validated');
  }
  echo Html::tag('div', AmosIcons::show('check-circle', ['class' => 'am-1', 'title' => $title]) . $title, ['class' => 'col-xs-12 nop']);
  }
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
  <div class="row m-b-35">
  <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $model->getAttributeLabel('presentazione_personale') ?></div>
  <div class="col-md-9 col-sm-8 col-xs-12">
  <?php
  $desclen = 350;
  ?>
  <?php if (strlen($model->presentazione_personale) <= $desclen) : ?>
  <?= $model->presentazione_personale ?>
  <?php else : ?>
  <div id="moreTextJs">
  <?php
  $moreContentTextLink  = Module::t('amoslayout', 'espandi descrizione') . ' ' . AmosIcons::show("chevron-down");
  $moreContentTitleLink = Module::t('amoslayout', 'Leggi la descrizione completa');

  $lessContentTextLink  = Module::t('amoslayout', 'riduci descrizione') . ' ' . AmosIcons::show("chevron-up");
  $lessContentTitleLink = Module::t('amoslayout', 'Riduci testo');
  ?>
  <div class="changeContentJs partialContent">
  <?=
  StringUtils::truncateHTML($model->presentazione_personale, $desclen)
  ?>
  <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $moreContentTitleLink ?>"><?= $moreContentTextLink ?></a>
  </div>
  <div class="changeContentJs totalContent" style="display:none">
  <?= $model->presentazione_personale ?>
  <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $lessContentTitleLink ?>"><?= $lessContentTextLink ?></a>
  </div>
  </div>
  <?php endif ?>

  </div>
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
  <?php
  $privilegesView = false;
  $accordionAdmin = '';
  if ($adminModule->disablePrivilegesEnableProfiles == true) {
  $profileClasses = $model->profileClasses;
  $privilegesView = true;
  if (!empty($profileClasses)) {
  $accordionAdmin .= '<ul>';
  foreach ($model->profileClasses as $item) {
  $accordionAdmin .= '<li><strong>'.$item->name.'</strong> '.$item->description.'</li>';
  }
  $accordionAdmin .= '</ul>';
  } else {
  $accordionAdmin .= AmosAdmin::t('amosadmin', 'Nessun profilo associato');
  }
  } else if (Yii::$app->user->can('PRIVILEGES_MANAGER')) {

  $privilegesModule = Yii::$app->getModule('privileges');
  if (!empty($privilegesModule)) {
  $accordionAdmin = \open20\amos\privileges\widgets\UserPrivilegesWidget::widget(['userId' => $model->user_id]);
  }
  }
  if ($privilegesView) {
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
  }
  ?>
  <!-- end ADMIN - PRIVILEGES -->
  </div>

  <?php if (Yii::$app->user->can('VIEW_TAG_TABS_PERMISSION')): ?>
  <div class="col-md-4 col-xs-12">
  <!-- AREE INTERESSE -->
  <?php if (\Yii::$app->getModule('tag')): ?>
  <div class="col-xs-12 tags-section-sidebar nop" id="section-tags">
  <?= Html::tag('h2', AmosIcons::show('tag', [], 'dash') . AmosAdmin::t('amosadmin', '#tags_title'),  ['class' => 'm-0']) ?>
  <div class="col-xs-12 m-b-25">
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


  <?php
  if($adminModule->enableValidationInView && \Yii::$app->user->can(\open20\amos\admin\models\UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED)) {
  $form = \open20\amos\core\forms\ActiveForm::begin([
  'options' => [
  'id' => 'user-profile-form',
  'data-fid' => (isset($fid)) ? $fid : 0,
  'data-field' => ((isset($dataField)) ? $dataField : ''),
  'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
  //        'class' => 'default-form col-xs-12 nop',
  'enctype' => 'multipart/form-data' // important
  ]
  ]);
  ?>
  <?php
  echo \open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget::widget([
  // parametri ereditati da verioni precedenti del widget WorkflowTransition
  'form' => $form,
  'model' => $model,
  'workflowId' => UserProfile::USERPROFILE_WORKFLOW,
  'viewWidgetOnNewRecord' => true,

  'closeButton' => Html::a(AmosAdmin::t('amosadmin', 'Annulla'), !empty(Yii::$app->session->get('previousUrl')) ? Yii::$app->session->get('previousUrl') : \Yii::$app->request->referrer, ['class' => 'btn btn-secondary']),

  // fisso lo stato iniziale per generazione pulsanti e comportamenti
  // "fake" in fase di creazione (il record non e' ancora inserito nel db)
  'initialStatusName' => explode('/', $model->getWorkflowSource()->getWorkflow(UserProfile::USERPROFILE_WORKFLOW)->getInitialStatusId())[1],
  'initialStatus' => $model->getWorkflowSource()->getWorkflow(UserProfile::USERPROFILE_WORKFLOW)->getInitialStatusId(),
  // Stati da renderizzare obbligatoriamente in fase di creazione (quando il record non e' ancora inserito nel db)
  'statusToRender' => $statusToRender,

  'hideSaveDraftStatus' => $hideDraftStatus,

  'draftButtons' => $draftButtons
  ]);
  ?>
  <?php
  \open20\amos\core\forms\ActiveForm::end();
  }else { ?>
  <?= Html::a(AmosAdmin::t('amosadmin', '#go_back'), (Yii::$app->session->get('previousUrl') ?: \Yii::$app->request->referrer), [
  'class' => 'btn btn-secondary pull-left m-b-30'
  ]) ?>
  <?php } ?>
  </div>
 */ ?>

<div class="profile-view">

    <!-- HEADER -->
    <div class="basic-info ">
        <div class="row">
            <div class="col-md-2 col-sm-3">
                <div class="img-profile">
                    <div class="img-profile-circle">
                        <?php
                        if (($adminModule->confManager->isVisibleBox('box_foto', ConfigurationManager::VIEW_TYPE_FORM)) &&
                                ($adminModule->confManager->isVisibleField(
                                        'userProfileImage',
                                        ConfigurationManager::VIEW_TYPE_FORM
                                ))
                        ) :
                            ?>
                            <?php
                            $url = $model->getAvatarUrl('original');
                            Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
                            try {
                                $getHorizontalImageClass = (!empty($model->userProfileImage) ? Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)['class'] : '');
                            } catch (\Exception $ex) {
                                $getHorizontalImageClass = '';
                            }
                            ?>
                            <?=
                            Html::img($url, [
                                'class' => 'img-responsive ' . $getHorizontalImageClass,
                                'alt' => AmosAdmin::t('amosadmin', 'Immagine del profilo')
                            ]);
                            ?>
                        <?php endif; ?>
                    </div>
                    <div class="under-img">
                        <!-- IMPERSONATE -->
                        <?php
                        if ($model->user_id != Yii::$app->user->id && Yii::$app->user->can('IMPERSONATE_USERS')) {
                            $user = new \open20\amos\core\user\AmosUser();
                            $user->setIdentity(
                                    \open20\amos\core\user\User::findOne(['id' => $model->user_id])
                            );
                            if (Yii::$app->user->can('ADMIN') || !$user->can("ADMIN")) {
                                echo Html::a(
                                        AmosIcons::show('assignment-account', ['class' => 'btn-cancel-search']) . AmosAdmin::t('amosadmin', 'Impersonate'),
                                        \Yii::$app->urlManager->createUrl([
                                            '/' . AmosAdmin::getModuleName() . '/security/impersonate',
                                            'user_id' => $model->user_id
                                        ]),
                                        ['class' => 'btn btn-action-primary']
                                );
                            }
                        }
                        ?>
                        <!-- end IMPERSONATE -->


                        <?php if ($model->validato_almeno_una_volta) : ?>
                            <div class="container-info-icons text-info m-t-20">
                                <?php
                                if ($model->isFacilitator()) {
                                    $facilitatorIcon = AmosIcons::show('pin-assistant', ['class' => 'am-1', 'title' => AmosAdmin::t('amosadmin', 'Facilitator')]);
                                    echo Html::tag('div', $facilitatorIcon . AmosAdmin::t('amosadmin', 'Facilitator'), ['class' => 'facilitator']);
                                }
                                $googleContactIcon = \open20\amos\admin\widgets\GoogleContactWidget::widget(['model' => $model]);
                                if (!empty($googleContactIcon)) {
                                    echo Html::tag('div', $googleContactIcon . AmosAdmin::t('amosadmin', 'Google Contact'), ['class' => 'google-contact']);
                                }

                                if (!$adminModule->completeBypassWorkflow) {
                                    $title = AmosAdmin::t('amosadmin', 'Profile Active');
                                    if ($model->status == \open20\amos\admin\models\UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) {
                                        $title = AmosAdmin::t('amosadmin', 'Profile Validated');
                                    }
                                    echo Html::tag('div', AmosIcons::show('check-circle', ['class' => 'am-1', 'title' => $title]) . $title, ['class' => 'col-xs-12 nop']);
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-sm-9">
                <div class="basic-data-container row">
                    <?php if (!is_null($model->presentazione_breve) && $model->presentazione_breve != '' ) { ?>
                        <div class="basic-data-box col-md-6">

                            <p class="data-label"><?= $model->getAttributeLabel('presentazione_breve') ?></p>

                            <?php
							var_dump($model->presentazione_breve);
                            if (
                                    ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_FORM)) &&
                                    ($adminModule->confManager->isVisibleField('presentazione_breve', ConfigurationManager::VIEW_TYPE_FORM)) && $model->presentazione_breve
                            ) {
                                ?>


                                <?= strip_tags($model->presentazione_breve) ?>

                                <hr>
                            <?php } ?>


                        </div>
                    <?php } ?>
                    <div class="basic-data-box col-md-6">

                        <?php
                        if (
                                ($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                                ($adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_VIEW))
                        ) :
                            ?>
                            <?php if (!is_null($model->prevalentPartnership)  && $model->prevalentPartnership != '') { ?>
                                <div class="prevalent-partnership-section">
                                    <p class="data-label"><?= AmosAdmin::t('amosadmin', 'Partnership') ?></p>
                                    <div class="wrap-partnership">

                                        <?php
                                        // TODO da modificare quando ci sarà terminato il nuovo plugin organizzazioni
                                        $url = '/img/img_default.jpg';
                                        if (isset($model->prevalentPartnership) && isset($model->prevalentPartnership->logoOrganization)) {
                                            $url = $model->prevalentPartnership->logoOrganization->getUrl('profile_view_users', false, true);
                                        }
                                        echo Html::img($url, ['class' => 'img-responsive']);
                                        ?>
                                        <div>
                                            <p class="bold"><?= $model->prevalentPartnership->name ?></p>
                                            <a class="btn btn-primary btn-xs" href="/organizzazioni/profilo/view?id=<?= $model->prevalentPartnership->id ?>">Esplora</a>
                                        </div>

                                        <!-- TODO tipologia organizzazione quando sarà presente -->
                                        <!-- TODO referente operativo quando sarà presente -->


                                    </div>
                                </div>
                          <hr>
                            <?php } ?>
                        <?php endif; ?>

                      
                    </div>



                </div>


                <div class="basic-data-container row flexbox">

                    <?php
                    if (
                            ($adminModule->confManager->isVisibleBox('box_presentazione_personale', ConfigurationManager::VIEW_TYPE_FORM)) &&
                            ($adminModule->confManager->isVisibleField('presentazione_personale', ConfigurationManager::VIEW_TYPE_FORM)) && $model->presentazione_personale
                    ) {
                        ?>
                        <div class="basic-data-box col-md-6">
                            <p class="data-label"><?= $model->getAttributeLabel('presentazione_personale') ?></p>
                            <?php
                            $desclen = 100;
                            ?>
                            <?php if (strlen($model->presentazione_personale) <= $desclen) : ?>
                                <?= $model->presentazione_personale ?>
                            <?php else : ?>
                                <div id="moreTextJs">
                                    <?php
                                    $moreContentTextLink = Module::t('amoslayout', 'espandi descrizione') . ' ' . AmosIcons::show("chevron-down");
                                    $moreContentTitleLink = Module::t('amoslayout', 'Leggi la descrizione completa');

                                    $lessContentTextLink = Module::t('amoslayout', 'riduci descrizione') . ' ' . AmosIcons::show("chevron-up");
                                    $lessContentTitleLink = Module::t('amoslayout', 'Riduci testo');
                                    ?>
                                    <div class="changeContentJs partialContent">
                                        <?=
                                        StringUtils::truncateHTML($model->presentazione_personale, $desclen)
                                        ?>
                                        <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $moreContentTitleLink ?>"><?= $moreContentTextLink ?></a>
                                    </div>
                                    <div class="changeContentJs totalContent" style="display:none">
                                        <?= $model->presentazione_personale ?>
                                        <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $lessContentTitleLink ?>"><?= $lessContentTextLink ?></a>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    <?php } ?>
                    <div class="basic-data-box col-md-6">
                        <?php if (Yii::$app->user->can('VIEW_TAG_TABS_PERMISSION')) : ?>
                            <?php if (\Yii::$app->getModule('tag')) : ?>
                                <?=
                                \open20\amos\core\forms\ListTagsWidget::widget([
                                    'userProfile' => $model->id,
                                    'className' => $model->className(),
                                    'viewFilesCounter' => true,
									'onlyText' => true,
                                ]);
                                ?>
                                <?php if (Yii::$app->getUser()->can('USERPROFILE_UPDATE', ['model' => $model])) { ?>
                                    <a href="<?= '/' . AmosAdmin::getModuleName() . '/user-profile/update?id=' . $model->id . '#tab-tags' ?>" title="<?= AmosAdmin::t('amosadmin', 'Modifica tags') ?>" data-toggle="tooltip" class="d-flex text-decoration-none align-items-center"><?= AmosAdmin::t('amosadmin', 'Modifica') ?> </a>
                                <?php } ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>


            </div>
        </div>
        <!--manage menu-->
        <?=
        ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => "/" . AmosAdmin::getModuleName() . "/user-profile/update?id=" . $model->id,
            'disableDelete' => true
        ])
        ?>

    </div>
    <div class="connection-info row">
        <!-- NETWORK -->
        <!--utenti collegati-->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                    $accordionNetwork = '';
                    $moduleCwh = Yii::$app->getModule('cwh');

                    if ($enableUserContacts && $model->validato_almeno_una_volta && !$hideContactsInView) {
                        $accordionUserContacts = \open20\amos\admin\widgets\UserContacsWidget::widget([
                                    'userId' => $model->user_id,
                                    'isUpdate' => false,
                                    'iconView' => true,
                        ]);
                        /* TODO: STAMPARE IL DATA PROVIDER-->
                          $dataProvider->pagination= [
                          'pageSize' => 21,
                          ];
                          $dataProviderViewWidgetConf = [
                          'dataProvider' => $dataProvider,
                          //'filterModel' => $model,
                          'currentView' => $currentView,
                          'iconView' => [
                          'itemView' => '_icon'
                          ],
                          'listView' => [
                          'itemView' => '_item'
                          ],

                          ];
                          ?>
                          <?= DataProviderView::widget($dataProviderViewWidgetConf); */
                        ?>

                        <?= $accordionUserContacts ?>


                    <?php } ?>
                </div>
            
            <!--rete:community, organizzazione, sede-->
            <!--todo: utlizzare data provider-->
            <?php
            if (isset($moduleCwh)) {
                $accordionNetwork = \open20\amos\cwh\widgets\UserNetworkWidget::widget([
                            'userId' => $model->user_id,
                            'isUpdate' => false
                ]);
                ?>
               
                    <div class="panel-body">
                        <?= $accordionNetwork ?>
                    </div>
                

            <?php } ?>

            <!-- end NETWORK -->

            <!-- ADMIN - PRIVILEGES -->
            <!--todo: utlizzare data provider-->

            
                <div class="panel-body">
                    <h3>Profili</h3>
                    <?php
                    $privilegesView = false;
                    $accordionAdmin = '';
                    if ($adminModule->disablePrivilegesEnableProfiles == true) {
                        $profileClasses = $model->profileClasses;
                        $privilegesView = true;
                        if (!empty($profileClasses)) {
                            $accordionAdmin .= '<div class="tags-list-all m-t-30">';
                            foreach ($model->profileClasses as $item) {
                                //    $accordionAdmin .= '<li><strong>'.$item->name.'</strong> '.$item->description.'</li>';
                                $accordionAdmin .= '<div class="tags-bi" data-tag="' . $item->id . '" data-toggle="tooltip" title="' . $item->name . '">
							<div class="d-flex align-items-center">
								<div>
									<span class="mdi mdi-tag-multiple"></span>
								</div>
								<div>
									<span class="tag-label">
										' . $item->name . '
									</span>
								</div>

							</div>

						</div>';
                            }
                            $accordionAdmin .= '</div>';
                        } else {
                            $accordionAdmin = null;
                            $privilegesView = false;
                        }
                    } else if (Yii::$app->user->can('PRIVILEGES_MANAGER')) {

                        $privilegesModule = Yii::$app->getModule('privileges');
                        if (!empty($privilegesModule)) {
                            $accordionAdmin = \open20\amos\privileges\widgets\UserPrivilegesWidget::widget(['userId' => $model->user_id]);
                        }
                    }
                    if ($privilegesView) {
                        echo ($accordionAdmin);
                    }
                    ?>
                </div>
            
            <!-- end ADMIN - PRIVILEGES -->
        </div>




    </div>


</div>