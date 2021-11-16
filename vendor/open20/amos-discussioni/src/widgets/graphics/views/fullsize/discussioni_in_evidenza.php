<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

/**
 * @var View $this
 * @var ActiveDataProvider $listaTopic
 * @var WidgetGraphicsUltimeDiscussioni $widget
 * @var string $toRefreshSectionId
 */

use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\icons\AmosIcons;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni;
use open20\amos\discussioni\assets\ModuleDiscussioniInterfacciaAsset;

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;


$moduleDiscussioni = \Yii::$app->getModule(AmosDiscussioni::getModuleName());
ModuleDiscussioniInterfacciaAsset::register($this);
?>

<div class="box-widget-header">
    <?php if(isset($moduleDiscussioni) && !$moduleDiscussioni->hideWidgetGraphicsActions) { ?>
        <?= WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosDiscussioni::className(),
            'actionRoute' => '/discussioni/discussioni-topic/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]); ?>
    <?php }?>

    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('disc', ['class' => 'am-2'], AmosIcons::IC)?>
            <?= AmosDiscussioni::tHtml('amosdiscussioni', 'Discussioni in evidenza') ?>
        </h2>
    </div>

    <?php
    if (count($listaTopic->getModels()) == 0):
        $textReadAll =  AmosDiscussioni::t('amosdiscussioni', '#addDiscussions');
        $linkReadAll = ['/discussioni/discussioni-topic/create'];
    else:
        $textReadAll =  AmosDiscussioni::t('amosdiscussioni', '#showAll') . AmosIcons::show('chevron-right');
        $linkReadAll = ['/discussioni'];
    endif;
    ?>

    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => '']); ?></div>
</div>

<div class="box-widget evidence-discussions">
    <section>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>

            <?php if (count($listaTopic->getModels()) == 0): ?>
                <div class="list-items list-empty"><h3><?= AmosDiscussioni::tHtml('amosdiscussioni', 'Nessuna Discussione') ?></h3></div>
            <?else: ?>
                <div class="list-items">
                    <?php foreach ($listaTopic->getModels() as $topic): ?>
                        <?php
                        /** @var DiscussioniTopic $topic */
                        ?>
                        <div class="widget-listbox-option" role="option">
                            <article class="wrap-item-box">
                                <div>
                                    <div class="container-img">
                                    <!-- IMMAGINE -->
                                    </div>
                                </div>
                                <div class="container-text">
                                    <div class="box-widget-info-top">
                                        <p><?= Yii::$app->getFormatter()->asDatetime($topic->created_at); ?></p>
                                        <p><?= $topic->creatoreDiscussione  ?></p>
                                    </div>

                                    <h2 class="box-widget-subtitle">
                                        <?php
                                        if (strlen($topic->titolo) > 150) {
                                            $stringCut = substr($topic->titolo, 0, 150);
                                            echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                        } else {
                                            echo $topic->titolo;
                                        }
                                        ?>
                                    </h2>
                                    <div class="box-widget-text">
                                    <?php
                                    $stringNoTags = $topic->testo;
                                    //remove table from editor
                                    //$stringNoTags = preg_replace('/<table(.*?)>(.*?)<\/table>/s', '', $stringNoTags);
                                    $stringNoTags = preg_replace('/<table(.*$)/s', '', $stringNoTags);
                                    // remove iframe from editor
                                    //$stringNoTags = preg_replace('/<iframe(.*?)>(.*?)<\/iframe>/s', '', $stringNoTags);
                                    $stringNoTags = preg_replace('/<iframe(.*$)/s', '', $stringNoTags);
                                    // remove images from editor
                                    //$stringNoTags = preg_replace('/<img(.*?)\/>/s', '', $stringNoTags);
                                    $stringNoTags = preg_replace('/<p><img(.*$)/s', '', $stringNoTags);
                                    // remove empty paragraph
                                    $stringNoTags = preg_replace('/<p><\/p>/s', '', $stringNoTags);
                                    // remove &nbsp; space
                                    $stringNoTags = str_replace('&nbsp;', '', $stringNoTags);
                                    if (strlen($stringNoTags) > 300) {
                                        $stringCut = substr($stringNoTags, 0, 300);
                                        echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                    } else {
                                        echo $stringNoTags;
                                    }
                                    ?>
                                    </div>
                                    <div class="box-widget-info-bottom">
                                        <?php
                                        if ($topic->getDiscussioniRisposte()->count() > 1) :
                                            $stringContr =  AmosDiscussioni::tHtml('amosdiscussioni', 'contributi');
                                        else:
                                            $stringContr =  AmosDiscussioni::tHtml('amosdiscussioni', 'contributo');
                                        endif;
                                        ?>
                                        <?= ($numeroContributi = $topic->getDiscussionComments()->count()) ? $numeroContributi . ' ' . $stringContr : AmosDiscussioni::tHtml('amosdiscussioni', 'Nessun contributo'); ?>
                                        
                                        <?php if ((isset($moduleDiscussioni->disableComments) && $moduleDiscussioni->disableComments) && $topic->close_comment_thread): ?>
                                            <div class="workflow-transition-state-descriptor-widget widget-body-content col-xs-12 nop">
                                                <?= AmosDiscussioni::t('amosdiscussioni', '#discussion_closed') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="footer-listbox">
                                    <?php if ((isset($moduleDiscussioni->disableComments) && $moduleDiscussioni->disableComments) && $topic->close_comment_thread): ?>
                                        <?= '<span class="sr-only">' . Html::a(AmosDiscussioni::t('amosdiscussioni', 'LEGGI'). '</span>' . AmosIcons::show('chevron-right'), ['../discussioni/discussioni-topic/partecipa', 'id' => $topic->id], ['class' => 'btn-action']); ?>
                                    <?php else: ?>
                                        <?= Html::a(AmosDiscussioni::t('amosdiscussioni', 'CONTRIBUISCI'), ['../discussioni/discussioni-topic/partecipa', 'id' => $topic->id], ['class' => 'btn-action']); ?>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php Pjax::end(); ?>
    </section>
</div>
