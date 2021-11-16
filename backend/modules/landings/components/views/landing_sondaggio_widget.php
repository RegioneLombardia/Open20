<?php

use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\core\helpers\Html;

/**
 * @var \open20\amos\sondaggi\models\Sondaggi $model
 * @var string $poolUrl url da richiamare in una pagina nuova
 * @var string $noFillableMessage se il questionario non è compilabile 1) non compilabile mess generico 2) l'a già compilato
 *
 * @var string $dayStart
 * @var string $monthStart
 * @var string $yearStart
 * @var string $dateStart
 *
 * @var string $hideDateEnd
 * @var string $dayEnd
 * @var string $monthEnd
 * @var string $yearEnd
 * @var string $dateEnd
 */

?>

<div class="sondaggi-item-list-wrapper">
    <div class="card-wrapper">
        <div class="card">
            <div class="card-body border-bottom border-light px-0">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="content-date-img">
                            <div class="date-start">
                                <span class="small"><?= AmosSondaggi::t('amossondaggi', 'Apre il') ?></span>
                                <span class="card-day font-weight-bold lead"><?= $dayStart ?></span>
                                <span class="card-mounth font-weight-bold"><?= $monthStart ?></span>
                                <span class="card-year"><?= $yearStart ?></span>
                            </div>
                            <?php
                            $url = '/img/img_default.jpg';
                            if ($model->file) {
                                $url = $model->file->getUrl('dashboard_news');
                            }

                            ?>
                            <div class="img-sondaggio">
                                <?= Html::img($url, [
                                    'alt' => AmosSondaggi::t('amossondaggi', 'Immagine del sondaggio'),
                                    'class' => 'img-responsive'
                                ]); ?>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="content-text-sondaggi">


                            <?php
                            if (!$hideDateEnd) : ?>
                                <div class="date-end-poll">
                                    <div class="category-top small text-muted mb-0">
                                        <span class="font-weight-light"> <?= AmosSondaggi::t('amossondaggi', 'Fino al') . ' ' ?></span>
                                        <span class="card-day"><?= $dayEnd ?></span>
                                        <span class="card-month"><?= $monthEnd ?></span>
                                        <span class="card-year"><?= $yearEnd ?></span>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div class="list-title">

                                    <h5 class="card-title font-weight-bold big-heading mb-2 ">
                                        <strong><?= $model->titolo ?></strong>
                                    </h5>

<!--                                <div class="ml-auto">-->
<!--                                    context menu-->
<!--                                </div>-->
                            </div>
                            <?php
//                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGI_READ', ['model' => $model])) {
                                ?>
                                <div class="desc-poll text-muted">
                                    <p>
                                        <?php
                                        if (strlen($model->descrizione) > 300) {
                                            $stringCut = substr($model->descrizione, 0, 300);
                                            echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                        } else {
                                            echo $model->descrizione;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
//                            }
                            ?>
                            <div class="footer-list">
                                <?php
                                if (!is_null($poolUrl)):
                                    echo Html::a(
                                        AmosSondaggi::tHtml('amossondaggi', 'Partecipa'),
                                        $poolUrl,
                                        [
                                            'class' => 'read-more',
//                                            'target' => '_blank',
                                            'title' => AmosSondaggi::t('amossondaggi', 'Partecipa'),
                                        ]
                                    );
                                else:
                                   echo $noFillableMessage;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

