<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-categorie
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open2\amos\ticket\AmosTicket;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\TicketCategorie $model
 * @var AmosTicket $module
 */
$module = \Yii::$app->getModule('ticket');

$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Assistenza'), 'url' => '/ticket'];
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Categorie'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$categoryReferentsHide = (!empty($module) && is_array($module->categoryReferentsHide)) ? $module->categoryReferentsHide : false;
$fielsdToHide = (!empty($module) && is_array($module->categoryFieldsHide)) ? $module->categoryFieldsHide : [];
$enableCategoryIcon = (!empty($module) && is_array($module->enableCategoryIcon)) ? $module->enableCategoryIcon : false;
?>
<div class="news-categorie-view">
    <div class="row">
        <div class="col-xs-12">
            <div class="body">
                <section class="section-data">
                    <?= ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/ticket/ticket-categorie/update?id=" . $model->id,
                        'actionDelete' => "/ticket/ticket-categorie/delete?id=" . $model->id,
                        'labelDeleteConfirm' => AmosTicket::t('amosticket', 'Sei sicuro di voler cancellare questa categoria?'),
                    ]) ?>
                    <h2>
                        <?php /*<?= AmosIcons::show('rss'); */ ?>
                        <?= AmosTicket::t('amosticket', 'Dettagli'); ?>
                    </h2>
                    <?php
                    if ($enableCategoryIcon):
                        ?>
                        <dl>
                            <dt><?= $model->getAttributeLabel('categoryIcon'); ?></dt>
                            <dd><?= Html::img($model->getCategoryIconUrl(), ['class' => 'gridview-image']) ?></dd>
                        </dl>
                    <?php
                    endif;
                    ?>
                    
                    <?php
                    if (!in_array('titolo', $fielsdToHide)):
                        ?>
                        <dl>
                            <dt><?= $model->getAttributeLabel('titolo'); ?></dt>
                            <dd><?= $model->titolo; ?></dd>
                        </dl>
                    <?php
                    endif;
                    ?>
                    <?php /* 
                    <dl>
                        <dt><?= $model->getAttributeLabel('sottotitolo'); ?></dt>
                        <dd><?= $model->sottotitolo; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('descrizione_breve'); ?></dt>
                        <dd><?= $model->descrizione_breve; ?></dd>
                    </dl>
                     */ ?>
                    
                    <?php
                    if (!in_array('descrizione', $fielsdToHide)):
                        ?>
                        <dl>
                            <dt><?= $model->getAttributeLabel('descrizione'); ?></dt>
                            <dd><?= $model->descrizione; ?></dd>
                        </dl>
                    <?php
                    endif;
                    ?>
                    
                    <?php
                    if (!$module->oneLevelCategories):
                        ?>
                        <dl>
                            <dt><?= $model->getAttributeLabel('categoria_padre_id'); ?></dt>
                            <dd><?= ($model->categoria_padre_id) ? $model->categoriaPadre->nomeCompleto : "" ?></dd>
                        </dl>
                    <?php
                    endif;
                    ?>
                    
                    <?php
                    if (!in_array('attiva', $fielsdToHide)):
                        ?>
                        <dl>
                            <dt><?= $model->getAttributeLabel('attiva'); ?></dt>
                            <dd><?= ($model->attiva) ? AmosTicket::t('amosticket', 'Si') : AmosTicket::t('amosticket', 'No') ?></dd>
                        </dl>
                    <?php
                    endif;
                    ?>
                    
                    <?php
                    if (!in_array('abilita_ticket', $fielsdToHide)):
                        ?>
                        <dl>
                            <dt><?= $model->getAttributeLabel('abilita_ticket'); ?></dt>
                            <dd><?= ($model->abilita_ticket) ? AmosTicket::t('amosticket', 'Si') : AmosTicket::t('amosticket', 'No') ?></dd>
                        </dl>
                    <?php
                    endif;
                    ?>
                    <?php if (!in_array('tecnica', $fielsdToHide) || !in_array('administrative', $fielsdToHide)): ?>
                        <?php if (!in_array('tecnica', $fielsdToHide)): ?>
                            <dl>
                                <dt><?= $model->getAttributeLabel('tecnica'); ?></dt>
                                <dd><?= ($model->tecnica) ? AmosTicket::t('amosticket', 'Si') : AmosTicket::t('amosticket', 'No') ?></dd>
                            </dl>
                        <?php endif; ?>
                        <?php if (!in_array('administrative', $fielsdToHide)): ?>
                            <dl>
                                <dt><?= $model->getAttributeLabel('administrative'); ?></dt>
                                <dd><?= ($model->administrative) ? AmosTicket::t('amosticket', 'Si') : AmosTicket::t('amosticket', 'No') ?></dd>
                            </dl>
                        <?php endif; ?>
                        <dl>
                            <dt><?= $model->getAttributeLabel('email_tecnica'); ?></dt>
                            <dd><?= $model->email_tecnica; ?></dd>
                        </dl>
                    <?php endif; ?>
                    
                    <?php
                    if ($categoryReferentsHide):
                        ?>
                        <dl>
                            <dt><?= AmosTicket::t('amosticket', 'Referenti di questa categoria') ?></dt>
                            <dd>
                                <?php $referents = "";
                                foreach ($model->ticketCategorieUsersMms as $user) {
                                    $referents .= $user->userProfile . "<br>";
                                } ?>
                                <?= $referents ?>
                            </dd>
                        </dl>
                    <?php
                    endif;
                    ?>
                </section>
            </div>
        </div>
    </div>
    <div class="btnViewContainer pull-right">
        <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
    </div>
</div>
