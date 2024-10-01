<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\assistenza
 * @category   CategoryName
 */

use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\assets\TicketAsset;
use open2\amos\ticket\models\TicketCategorie;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open2\amos\ticket\models\search\TicketCategorieSearch $searchModel
 * @var array $ticketCategorieArray
 */

TicketAsset::register($this);

$this->title = AmosTicket::t('amosticket', 'Faq');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '/ticket'];

$mutualClose = false;

?>


<div class="cerca-faq-view">

    <div class="row">

        <div class="col-xs-12">
            <?php foreach ($ticketCategorieArray as $ticketCategorieLivello) : ?>
                <?php
                $k = 0;
                ?>
                <?php if ($k == 0 && !is_null($ticketCategorieLivello[$k]->categoriaPadre)) : ?>
                    <div class="faq-category-div">
                        <?php /*echo (!is_null($ticketCategorieLivello[$k]->categoriaPadre) ? $ticketCategorieLivello[$k]->categoriaPadre->titolo : '')*/ ?>
                    </div>
                <?php endif; ?>
                <ul class="faq-category-list">
                    <?php foreach ($ticketCategorieLivello as $cat) : ?>
                        <?php /** @var TicketCategorie $cat */ ?>
                        <li class="faq-category-item <?= ($cat->selected) ? 'faq-category-selected' : ''; ?>">
                            <a class="faq-category-link" href="?categoriaSelezionataId=<?= $cat->id ?>" title="<?= AmosTicket::t('amosticket', 'Clicca per navigare la categoria {nomeCat}', ['nomeCat' => $cat->titolo]) ?>">
                                <div>
                                    <div class="image">
                                        <?php
                                        $url = $cat->getCategoryIconUrl('item_community',false);
                                        $contentImage = Html::img($url, [
                                            'class' => 'img-responsive',
                                            'alt' => AmosTicket::t('amosticket', 'Immagine della categoria di faq {nameCat}', ['nameCat' => $cat->titolo])
                                        ]);
                                        echo $contentImage;
                                        ?>
                                    </div>
                                    <div class="title">
                                        <?php if ($k == 0 && !is_null($cat->categoriaPadre)) : ?>
                                            <?= Html::tag('small', (!is_null($cat->categoriaPadre) ? $cat->categoriaPadre->titolo : '')) ?>
                                        <?php endif; ?>
                                        <?= Html::tag('p', $cat->titolo, ['class' => 'text-uppercase title-category-faq']) ?>
                                    </div>
                                </div>
                                <div>
                                    <?= $cat->descrizione ?>
                                </div>
                            </a>
                            <?php $k++; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($categoriaSelezionata)) : ?>
            <?php
            /** @var TicketCategorie $categoriaSelezionata */
            $faqs = $categoriaSelezionata->ticketFaq;
            ?>
            <?php if (count($faqs) > 0) : ?>
                <div class="col-xs-12">
                    <div class="image-and-title-category m-b-30">
                        <?php
                        $url = $categoriaSelezionata->getCategoryIconUrl('item_community',false);
                        $contentImage = Html::img($url, [
                            'class' => 'img-responsive',
                            'alt' => AmosTicket::t('amosticket', 'Immagine della categoria di faq {nameCat}', ['nameCat' => $categoriaSelezionata->titolo])
                        ]);
                        echo $contentImage;
                        ?>
                        <?= Html::tag('strong', AmosTicket::t('amosticket', 'Lista FAQ') . ' ' . $categoriaSelezionata->titolo, ['class' => 'h3']) ?>
                    </div>
                    <?php foreach ($faqs as $faq) : ?>
                        <div class="faq-list panel-group" id="faqs<?= $faq->id ?>Cat<?= $categoriaSelezionata->id ?>" role="tablist" aria-multiselectable="true">

                            <div class="faq-list-item panel panel-default faq<?= $faq->id ?>">
                                <div class="panel-heading" role="tab" id="headerFaq<?= $faq->id ?>">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" <?= ($mutualClose) ? 'data-parent="faqs' . $faq->id . 'Cat' . $categoriaSelezionata->id . '"' : '' ?> href="#bodyFaq<?= $faq->id ?>" aria-expanded="false" aria-controls="bodyFaq<?= $faq->id ?>">
                                            <?= AmosIcons::show('question-circle-o', [], 'dash') ?><?= $faq->domanda ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="bodyFaq<?= $faq->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headerFaq<?= $faq->id ?>">
                                    <div class="panel-body">
                                        <?= $faq->risposta ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($categoriaSelezionata->abilita_ticket) : ?>

                <?php if (!$categoriaSelezionata->ticketFaq > 0) : ?>
                    <div class="col-xs-12">
                        <div class="callout callout-info">
                            <div class="callout-title">
                                <?= AmosIcons::show('info-outline') ?>
                            </div>
                            <p>
                                <?= AmosTicket::t('amosticket', 'Non sono presenti FAQ per la categoria selezionata {selectedCategory}', ['selectedCategory' => Html::tag('strong', $categoriaSelezionata->titolo)]) ?>
                            </p>
                        </div>
                    </div>
                <?php endif ?>
                <div class="col-xs-12 m-t-50">
                    <div class="callout callout-warning">
                        <div class="callout-title">
                            <?= AmosIcons::show('question-circle-o', [], 'dash') ?>
                        </div>
                        <p>
                            <?=
                            '<strong class="text-uppercase">' . AmosTicket::t('amosticket', 'Hai bisogno di ulteriore assistenza?') . '</strong>'
                                . ' ' .
                                Html::a(
                                    '<strong>' . AmosTicket::t('amosticket', 'Clicca qui') . '</strong>',
                                    [
                                        '/ticket/ticket/create',
                                        'categoriaId' => $categoriaSelezionata->id
                                    ],
                                    [
                                        'title' => AmosTicket::t('amosticket', 'Clicca qui per aprire un ticket e ricevere assistenza'),
                                        'target' => '_blank',
                                        'class' => ''
                                    ]
                                )
                                . ' ' .
                                AmosTicket::t('amosticket', 'per aprire un nuovo ticket e ricevere supporto!');
                            ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
