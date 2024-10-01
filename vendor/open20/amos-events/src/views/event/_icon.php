<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\core\forms\ContextMenuWidget;
$viewUrl = $model->getFullViewUrl();
?>
<div class="listview-container event-icon nop">
    <div class="event-container flex-column light-theme">
        <div class="calendar-img-container">
            <div class="d-flex flex-column-reverse flex-md-row">
                <div class=" box-calendar d-flex flex-row flex-md-column ">
                    <div class="date pt-3 py-md-2 px-md-4 mr-0 mr-md-3 mb-1 d-flex flex-md-column justify-content-md-center align-items-md-center text-uppercase flex-md-grow-1 lightgrey-bg-c1">
                        <p class="pr-2 pr-md-0 font-weight-bold mb-0 h2 d-none d-md-block"><?= date("d", strtotime($model->begin_date_hour)) ?></p>
                        <!-- <p class="pr-2 pr-md-0 font-weight-bold mb-0 h4 d-block d-md-none ">21</p> -->
                        <p class="font-weight-bold pr-2 pr-md-0 mb-0 h4"><?= Yii::$app->formatter->asDate($model->begin_date_hour, 'MMM') ?></p>
                        <p class="font-weight-normal mb-0 h4"><?= date("Y", strtotime($model->begin_date_hour)) ?></p>
                    </div>
                    <div class="hour d-none d-md-flex align-items-center justify-content-start mt-1 mr-3 py-4 px-5 bg-tertiary">
                        <span class="am am-time"></span> <span
                                class="mb-0 lead text-white"><?= ($model->begin_date_hour ? Yii::$app->getFormatter()->asTime($model->begin_date_hour) : '-') ?></span>
                    </div>
                </div>
            </div>
            <div class="external-image-container">
                <div class="image-wrapper">

            <?php echo ContextMenuWidget::widget([
              'model' => $model,
              'actionModify' => "/events/event/update?id=" . $model->id,
              'actionDelete' => "/events/event/delete?id=" . $model->id,
              'optionsModify' => [
                'class' => 'event-modify',
              ],
            ]) ?>
            <?php

                    $url = $model->getEventsImageUrl('square_large', false);
                    $logo = Html::img($url, [
                        'alt' => $model->getAttributeLabel('eventLogo'),
                        'class' => 'community-image img-fluid w-100'
                    ]);
                    ?>
                    <?= Html::a(
                        $logo,
                        $viewUrl,
                        [
                            'class' => '',
                            'title' => AmosEvents::t('amosevents', '#widget_title_view_event')
                        ]
                    )
                    ?>

                </div>
            </div>
        </div>
        <?= Html::a(Html::tag('h3', $model->title, ['class' => 'font-weight-bold m-t-10 mb-0']), $viewUrl, ['class' => 'link-list-title', 'title' => 'Vedi evento ' . $model->title]) ?>
        <p class="mb-0 subtitle"><?= $model->summary ?></p>

        <?php
        $classLink = 'btn btn-primary';
        ?>

        <div class="d-flex justify-content-between">
            <a class="btn btn-link my-3" href="<?= $viewUrl ?>"
               title="Vai alla pagina dell'evento <?= $model->title ?>">
                <p class="mb-0"><?= AmosEvents::t('amosevents', '#widget_title_view_event') ?></p>
            </a>

            <?php if (!empty($model->community_id) && ($model->show_community)): ?>
            <?php   $createUrlParams = [
                        '/community/join/open-join',
                        'id' => $model->community_id
                    ]; ?>
            <a class="btn btn-link my-3" href="<?= Yii::$app->urlManager->createUrl($createUrlParams) ?>"
               title="Vai alla community dell' evento <?= $model->title ?>">
                <p class="mb-0"><?= AmosEvents::t('amosevents', 'Go to the community') ?></p>
            </a>
            <?php endif; ?>
        </div>

        <!-- <?php if (!empty($model->community_id) && $model->show_community) : ?>
        <a class="btn btn-primary my-3 align-self-start" href="<?= '/community/join/open-join?id=' . $model->community_id ?>" title="Vai alla community dell'evento <?= $model->title ?>">
          <p class="mb-0"><?= AmosEvents::t('amosevents', '#widget_title_community_event') ?></p>
        </a>
      <?php endif; ?> -->
    </div>
</div>
