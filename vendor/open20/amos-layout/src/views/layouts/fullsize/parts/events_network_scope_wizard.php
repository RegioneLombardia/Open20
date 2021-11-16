<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

use open20\amos\community\models\CommunityType;
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\core\helpers\Html;
use open20\amos\layout\assets\BaseAsset;
use open20\amos\layout\Module;
use open20\amos\core\icons\AmosIcons;


$url = "/img/img_default.jpg";
if (!empty($model->eventLogo)) {
    $url = $model->eventLogo->getWebUrl();
}
$beginDate = new \DateTime($model->begin_date_hour);
$endDate = new \DateTime($model->end_date_hour);

$asset = BaseAsset::register($this);

$moduleCommunity = Yii::$app->getModule('community');
if (isset($community)) {
    open20\amos\community\assets\AmosCommunityAsset::register($this);
    $viewUrl = ['/community/join/index?id=' . $community->id];
    $exitUrl = (!empty(\Yii::$app->homeUrl) ? \Yii::$app->homeUrl : ['/dashboard']);
    $fixedCommunityType = (!is_null($moduleCommunity->communityType));
    ?>
    <div class="network-container community-network-container fullscreen-network-container">
    <!-- BEGIN: community data -->
    <div class="network-box">
        <img src="<?= $asset->baseUrl ?>/img/bg-community.jpg">

        <div class="network-infos">
            <div class="container-custom">
                <div class="header-community">
                    <div class="poster-community">
                        <img src="<?= ($url) ?>" class="img-fluid">

                    </div>

                    <div class="action-community">
                        <?php if (isset($this->params['CommunityParams']['outsideCommunity']) && $this->params['CommunityParams']['outsideCommunity']) {
                            echo JoinCommunityWidget::widget([
                                'model' => $community,
                                'isProfileView' => true,
                                'btnClass' => 'enter-community',
                            ]);
                        } else {
                            echo Html::a(
                                Module::t('amoslayout', '#network_scope_exit_from_community'),
                                $exitUrl,
                                [
                                    'class' => 'back-to-dashboard'
                                ]
                            );
                        } ?>
                    </div>

                    <div class="network-footer">
                        <div class="control-community">
                            <div class="d-flex align-items-start">
                                <div class="mr-2">
                                    <span class="tipo-evento"
                                          style="background-color:<?= $model->eventType->color ?>"></span>
                                </div>

                                <span><?= $model->eventType->title ?></span>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="header-right-community">
                    <h1 class="network-name"><strong><?= $model->getTitle() ?></strong></h1>
                    <div class="event-location d-flex align-items-start">
                        <div class="icon-lg mr-2"><?= AmosIcons::show('pin') ?></div>
                        <div>
                            <p class="lead"><strong><?= $model->eventLocation->name ?></strong></p>
                            <p class="lead"><?= !empty($model->eventLocationEntrance) ? $model->eventLocationEntrance->name : '' ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="event-date col-lg-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-lg mr-2"><?= AmosIcons::show('calendar-check') ?></div>
                                <div>
                                    <p class="lead"><strong><?= $beginDate->format('d F Y') ?></strong></p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-lg mr-2"><?= AmosIcons::show('time') ?></div>
                                <div>
                                    <p class="lead"><?= $beginDate->format('H:s') . ' - ' . $endDate->format('H:s') ?></p>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($model->seats_available)) : ?>
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center">
                                    <div class="icon-lg mr-2"><?= AmosIcons::show('seat') ?></div>
                                    <div>
                                        <p class="lead"><?= $model->getAttributeLabel('seats_available'); ?> <?= $model->seats_available ?></p>

                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>


                    <div class="actions">
                    
                        <?php if (\Yii::$app->user->can(
                            'EVENT_UPDATE',
                            ['model' => $model]
                        )) : ?>
                            <?=
                            Html::a(
                                \open20\amos\core\icons\AmosIcons::show(
                                    'modifica',
                                    [],
                                    \open20\amos\core\icons\AmosIcons::IC
                                ),
                                '/events/event-dashboard/view?id=' . $model->id,
                                ['class' => 'btn btn-icon btn-edit']
                            )
                            ?>
                        <?php endif; ?>
                        <?php
                        $reportModule = \Yii::$app->getModule('report');
                        if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                            echo \open20\amos\report\widgets\ReportDropdownWidget::widget([
                                'model' => $model,
                            ]);
                        }
                        ?>
                        <?php echo \open20\amos\core\forms\CreatedUpdatedWidget::widget(['model' => $model, 'isTooltip' => true]) ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: community data -->
<?php } ?>