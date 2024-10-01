<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var \open20\amos\discussioni\models\DiscussioniTopic $model
 */

$module = \Yii::$app->getModule('discussioni');
?>

<div class="listview-container discussion-topic-container discussion-topic-container-card">
    <div class=" ">
        <div class="row">
            <?php
                $creatoreDiscussione = $model->getCreatoreDiscussione()->one();
                $nomeCreatoreDiscussione = AmosDiscussioni::t('amosdiscussioni', 'Utente Cancellato');
                $dataPubblicazione = Yii::$app->getFormatter()->asDatetime($model->created_at);
            ?>

        
            <div class="first-container col-sm-12 row nop">
                <div class="col-sm-12 col-md-6">
                    <div class="post-content nop">
                        <div>
                            <p><?= $dataPubblicazione?></p>
                        </div>
                        <div class="post-title">
                            <?= Html::a(Html::tag('h2', $model->titolo), $model->getFullViewUrl()) ?>
                        </div>
                        <?= NewsWidget::widget(['model' => $model]); ?>
                        
                        
                    
                    </div>
                    
                    <div class=" nop">
                        <?= ItemAndCardHeaderWidget::widget([
                            'model' => $model,
                            'publicationDateField' => 'created_at',
                            
                        ]) ?>
                    
                    </div>
                    
                </div>

                <div class="col-sm-12 col-md-6">
                    <?= ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/discussioni/discussioni-topic/update?id=" . $model->id,
                            'actionDelete' => "/discussioni/discussioni-topic/delete?id=" . $model->id,
                            'modelValidatePermission' => 'DiscussionValidate'
                        ]) ?>
                    <div class="row nom post-wrap">
                                <?php
                                    $url = '/img/img_default.jpg';
                                    ?>
                                    <?php if (!is_null($model->discussionsTopicImage)): ?>
                                        <?php
                                        $url = $model->discussionsTopicImage->getUrl('square_medium', false, true);
                                        $contentImage = Html::img($url, [
                                            'class' => 'full-width ',
                                            'alt' => AmosDiscussioni::t('amosdiscussioni', 'Immagine della discussione')
                                        ]);
                                    ?>
                                    <?= Html::a($contentImage, $model->getFullViewUrl()) ?>
                                <?php endif; ?>

                                
                    </div>
                    
                </div>
            </div>

            <div class="col-sm-12 delete-mobile-padding">
                <div class="post-text">
                    <p>
                        <?php
                            $stringNoTags = strip_tags($model->testo);
                                   
                            if (strlen($stringNoTags) > 800) {
                                $stringCut = substr($stringNoTags, 0, 800);
                                echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                            } else {
                                echo $stringNoTags;
                            }
                        ?>
                    </p>

                    <?php
                    $comments = $model->getDiscussionComments();
                    $commentsNumber = $comments->count();

                    //numero partecipanti
                    $partecipanti = $comments->groupBy('created_by')->count();

                    $noComments = false;
                    $commentsNumberString = $commentsNumber;
                    if ($commentsNumberString == 0) {
                        $commentsNumberString = AmosDiscussioni::t('amosdiscussioni', 'Non ci sono ancora contributi');
                        $noComments = true;
                    } else if ($commentsNumber == 1) {
                        $commentsNumberString = $commentsNumberString . " " . AmosDiscussioni::t('amosdiscussioni', " contributo");
                    } else if ($commentsNumber > 1 && $commentsNumber <= 3) {
                        $commentsNumberString = AmosDiscussioni::t('amosdiscussioni', "Ultimi" . " " . $commentsNumber . " " . "contributi di") . " " . $commentsNumber . " " . AmosDiscussioni::t('amosdiscussioni', "totali");
                    } else if ($commentsNumber >= 4) {
                        $commentsNumberString = AmosDiscussioni::t('amosdiscussioni', "Ultimi 3 contributi di") . " " . $commentsNumber . " " . AmosDiscussioni::t('amosdiscussioni', "totali");
                    } else {
                        $commentsNumberString = AmosDiscussioni::t('amosdiscussioni', 'Non ci sono ancora contributi');
                        $noComments = true;
                    }
                    $numeroVisualizzazioni = $model->hints;
                    if (!$numeroVisualizzazioni) {
                        $numeroVisualizzazioni = 0;
                    }
                    $attributeModel = $model->getDiscussionsAttachments();
                    
                    $numeroAllegati = (is_array($attributeModel)) ? count($attributeModel) : 0;
                ?>


                    <div class="post-discussion-text">
                        <p>
                            <?= Html::a(AmosDiscussioni::t('amosdiscussioni', 'Leggi tutto'), $model->getFullViewUrl(), [
                                'class' => 'underline text-uppercase',
                                'title' => AmosDiscussioni::t('amosdiscussioni', 'leggi la discussione')
                            ]) ?>
                        </p>
                        <div class="people col-xs-7 nop">
                        
                    
                            
                            <p>
                            <strong>
                                    <?php
                                    if ($partecipanti == 1) {
                                        echo $partecipanti . ' partecipante';
                                    } else {
                                        echo $partecipanti . ' partecipanti';
                                    }
                                    // di cui 4 nella tua rete
                                    ?>
                            </strong
                            ></p>
                            <?php
                            $participants = $model->commentsUsersAvatars();
                            foreach ($participants as $participant) {
                                if ($participant) {
                                    echo UserCardWidget::widget(['model' => $participant, 'avatarXS' => true, 'enableLink' => true]);
                                }
                            }
                            ?>
                        </div>
                    </div>


                    
                                
                    </div>
            </div>


            <div class="text-content col-sm-12 delete-mobile-padding">
                <p class="numero-contributi"><?= $commentsNumberString ?></p>
                <div class="container-sidebar">
                    <div class="last-answer box">
                        <?php
                        if ($commentsNumber == 0) {
                            echo AmosDiscussioni::t('amosdiscussioni', 'Puoi essere il primo a lasciare un contributo.');
                        }
                        $lastComments = $model->getLastComments()->all();
                        foreach ($lastComments as $lastComment) {
                            /** @var \open20\amos\comments\models\Comment $lastComment */
                            /** @var \open20\amos\admin\models\UserProfile $lastCommentUser */
                            $lastCommentUser = $model->getCommentCreatorUser($lastComment)->one();
                            ?>
                            <div class="answer nop media">
                                <div class="media-left">
                                    <?php
                                    $mediafile = null;
                                    if (!$noComments) :
                                        if ($lastCommentUser) :
                                            echo UserCardWidget::widget(['model' => $lastCommentUser, 'enableLink' => true]);
                                        endif;
                                    endif;
                                    ?>
                                </div>
                                <?php if ($lastCommentUser): ?>
                                    <div class="answer_details media-body">
                                        <p class="answer_name">
                                            <?php
                                            echo $lastCommentUser->nome . " " . $lastCommentUser->cognome;
                                            ?>
                                        </p>
                                        <p>
                                            <?= Yii::$app->getFormatter()->asDatetime($lastComment->created_at); ?>
                                        </p>
                                        <div class="answer_text">
                                            <p>
                                                <?php
                                                if (strlen($lastComment->comment_text) > 100) {
                                                    $stringCut = substr(strip_tags($lastComment->comment_text), 0, 100);
                                                    echo $stringCut . '... ';
                                                } else {
                                                    echo $lastComment->comment_text;
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="footer_sidebar text-right">
                        <?php if ((isset($module->disableComments) && $module->disableComments) && $model->close_comment_thread): ?>
                            <span class="closed-label">
                                <?= AmosDiscussioni::t('amosdiscussioni', '#discussion_closed') ?>
                            </span>
                        
                        <?= Html::a(
                            AmosDiscussioni::t('amosdiscussioni', 'LEGGI'),
                            ['partecipa', 'id' => $model->id, '#' => 'comments_contribute'],
                            [
                                'class' => 'btn btn-navigation-primary',
                                'title' => AmosDiscussioni::t('amosdiscussioni', 'LEGGI')
                            ]
                        ) ?>
                        <?php else: ?>
                        <?= Html::a(
                            AmosDiscussioni::t('amosdiscussioni', 'Contribuisci'),
                            ['partecipa', 'id' => $model->id, '#' => 'comments_contribute'],
                            [
                                'class' => 'btn btn-navigation-primary',
                                'title' => AmosDiscussioni::t('amosdiscussioni', 'commenta')
                            ]
                        ) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


            
            <!--
            <div class="post-footer col-xs-12 nop">
                    <div class="post-info">
                        <?= PublishedByWidget::widget([
                            'model' => $model,
                            'layout' => '{publisher}{targetAdv}' . (Yii::$app->user->can('ADMIN') ? '{status}' : '')
                        ]) ?>
                    </div>
                        < ?php
                        $visible = isset($statsToolbar) ? $statsToolbar : false;
                        if ($visible) {
                            echo StatsToolbar::widget([
                                'model' => $model
                            ]);
                        }
                        ?>

                    
            </div>
            -->
            

            
        </div>
    </div>
</div>
