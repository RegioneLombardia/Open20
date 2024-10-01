<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;
use open20\design\utility\DateUtility;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\core\forms\WorkflowStateWidget;

$js = <<<JS

    $('.change_status_workflow').on('click', function(e) {
        e.preventDefault();
        var element =  $(this);
      var workflowId = '';
        var idModel = element.data('id');
        var str = element.attr('id');
        var state = str.split(/[\s-]+/).pop();
        if(state){
            $.ajax({
                url:"/sondaggi/ajax/change-status-session?id="+idModel+"&new_state="+state+"&modelObj=open20\\\\amos\\\\sondaggi\\\\models\\\\SondaggiRisposteSessioni",
                type: "GET",
                data: {},
                success:function(result){
                    location.reload();
                },
                error: function(richiesta,stato,errori){

                }
              });
        }
       return false;
   });

JS;

$this->registerJs($js, \yii\web\View::POS_READY);

ModuleSondaggiAsset::register($this);
/**
 * @var \open20\amos\sondaggi\models\Sondaggi $model
 */
$hideStatusPoll = (isset($hideStatusPoll)) ? $hideStatusPoll : false;
$dateStart      = $model->created_at;
$dayStart       = DateUtility::getDate($dateStart, 'php:d');
$monthStart     = DateUtility::getDate($dateStart, 'php:M');
$yearStart      = DateUtility::getDate($dateStart, 'php:Y');
$dateStart      = DateUtility::getDate($dateStart);
$dateEnd = $model->close_date;


$hideDateEnd = empty($model->close_date);
if (isset($dateEnd)) {
    $dayEnd   = DateUtility::getDate($dateEnd, 'php:d');
    $monthEnd = DateUtility::getDate($dateEnd, 'php:M');
    $yearEnd  = DateUtility::getDate($dateEnd, 'php:Y');
    $dateEnd  = DateUtility::getDate($dateEnd);
}

$moduleNameSondaggi = $moduleName ?: $this->context->module->id;
?>

<div class="sondaggi-item-list-wrapper">
    <div class="card-wrapper">
        <div class="card">
            <div class="card-body border-bottom border-light px-0">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="content-date-img">
                            <div class="date-start">
                                <span class="small"><?= AmosSondaggi::t('amossondaggi', 'APERTO IL') ?></span>
                                <span class="card-day bold lead"><?= $dayStart ?></span>
                                <span class="card-mounth bold"><?= $monthStart ?></span>
                                <span class="card-year"><?= $yearStart ?></span>
                            </div>
                            <?php
                            $url = '/img/img_default.jpg';
                            if ($model->file) {
                                $url = $model->file->getUrl('dashboard_news');
                            }
                            ?>
                            <div class="img-sondaggio">
                                <?=
                                Html::img(
                                    $url,
                                    [
                                        'alt' => AmosSondaggi::t('amossondaggi', 'Immagine del sondaggio'),
                                        'class' => 'img-responsive'
                                    ]
                                );
                                ?>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="content-text-sondaggi">


                            <?php if (!$hideDateEnd) : ?>
                                <div class="date-end-poll">
                                    <div class="category-top small text-muted">
                                        <span class="font-weight-light"> <?= AmosSondaggi::t('amossondaggi', 'Fino al') . ' ' ?></span>
                                        <span class="card-day"><?= $dayEnd ?></span>
                                        <span class="card-month"><?= $monthEnd ?></span>
                                        <span class="card-year"><?= $yearEnd ?></span>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div class="list-title">
                                <?php if ($model->isCompilable()) { ?><a href="<?= $model->getFullViewUrl() ?>" title="<?= $model->titolo ?>" class="link-list-title title-two-line">
                                        <h5 class="card-title font-weight-bold big-heading mb-2 "><strong><?= $model->titolo ?></strong></h5>
                                    </a>
                                <?php } else { ?>
                                    <h5 class="card-title font-weight-bold big-heading mb-2 "><strong><?= $model->titolo ?></strong></h5>
                                <?php } ?>

                                <?php
                                if ($model->isCommunitySurvey()) {
                                    $community = \open20\amos\community\models\Community::findOne($model->community_id);
                                    if ($community) { ?>
                                        <a href="javascript:void(0)" data-toggle="tooltip" title="dalla community <?= $community->name ?>">

                                            <span class="mdi mdi-account-supervisor-circle text-muted m-l-5"></span>

                                            <span class="sr-only"><?= $community->name ?></span>
                                        </a>
                                    <?php }
                                } ?>

                                <div class="ml-auto">
                                    <?=
                                    ContextMenuWidget::widget([
                                        'model' => $model,
                                        'actionModify' => "/sondaggi/dashboard/dashboard?id=" . $model->id,
                                        'actionDelete' => "/sondaggi/sondaggi/delete?id=" . $model->id,
                                        'mainDivClasses' => ''
                                    ])
                                    ?>
                                </div>
                            </div>
                            <?php
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can(
                                'SONDAGGI_READ',
                                ['model' => $model]
                            )) {
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
                            }
                            ?>
                            <div class="footer-list">
                                <?php if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI')) { ?>
                                    <span class="partecipanti-poll"><?= AmosSondaggi::t('amossondaggi', 'Partecipanti') . ':' ?><strong> <?= $model->getNumeroPartecipazioni() ?></strong></span>
                                    <?php if (!$hideStatusPoll) { ?>
                                       <span class="status-poll"><?= \Yii::t('amossondaggi', '#compilation_state') ?>:<strong> <?=
                                          $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '--';
                                       ?></strong></span>
                                    <?php }; ?>
                                <?php
                                }
                                /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                                $url = \yii\helpers\Url::current();
                                //if (\Yii::$app->getUser()->can('PARTECIPANTE') || TRUE) {


                                ?>

                                <?php
                                //if (!$model->hasCompilazioniSuperate() && $model->isCompilable()) {
                                    $compilazioni = $model->getNumeroPartecipazioni(1);
                                    $module       = AmosSondaggi::instance();
                                    if (
                                        $compilazioni > 0 && $module->enableSingleCompilation == true && $module->enableRecompile
                                        == true && $module->enableCompilationWorkflow == true
                                    ) {

                                        $session = $model->getSondaggiRisposteSessionisByEntity()->one();


                                        $transitions = $session->getWorkflowSource()->getTransitions(
                                            $session->status,
                                            $session
                                        );

                                       if ($model->isCompilable()) {
                                        ?>

                                        <div class="dropdown change-status">
                                           <button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                           <?= AmosSondaggi::t('amossondaggi', $model->getSondaggiRisposteSessionisByEntity()->one()->status) ?>
                                               &nbsp;<span class="caret"></span>
                                           </button>
                                           <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">

                                        <?php
                                        foreach ($transitions as $transition) {
                                            echo '<li>'.Html::button(
                                                AmosSondaggi::tHtml(
                                                    'amossondaggi',
                                                    "Passa in: " . $transition->getEndStatus()->getLabel()
                                                ),
                                                [
                                                    'id' => $session->id . '-' . $transition->getEndStatus()->getId(),
                                                    'class' => 'read-more change_status_workflow',
                                                    'title' => AmosSondaggi::t('amossondaggi', 'Passa allo stato'),
                                                    'data' => ['id' => $session->id]
                                                ],
                                                $transition->getEndStatus()->getId(),
                                                ['model' => $session, 'status' => $transition->getEndStatus()->getId()]
                                            ).'</li>';
                                        }
                                       ?>

                                       </ul>
                                       </div>
                                       <?php
                                    } else {
                                       echo AmosSondaggi::t('amossondaggi', 'Stato'). ':&nbsp;<strong>'.AmosSondaggi::t('amossondaggi', $model->getSondaggiRisposteSessionisByEntity()->one()->status).'</strong>';
                                    }
                                    }
                                        ?>
                                        <div class="actions-poll">
                                        <?php
                                        if (\Yii::$app->getUser()->can('RESPONSABILE_ENTE') && $model->isCompilable()) {
                                            echo Html::a(
                                                AmosSondaggi::tHtml('amossondaggi', '#assign_compiler'),
                                                '#',
                                                [
                                                    'id' => 'assign-compiler-menu-' . $model->id,
                                                    'class' => 'assign-compiler-menu',
                                                    'title' => AmosSondaggi::t('amossondaggi', '#assign_compiler'),
                                                    'data' => ['id' => $model->id]
                                                ]
                                            ) . '&nbsp;';
                                        }
                                    if (
                                        $compilazioni > 0 && $module->enableSingleCompilation == true && $module->enableRecompile
                                        == true && $module->enableCompilationWorkflow == true
                                    ) {
                                        if ($model->getNumeroPartecipazioni(1) > 0) {
                                            $session = $model->lastSondaggiRisposteSessioniByEntity;
                                            echo Html::a(
                                                AmosSondaggi::tHtml('amossondaggi', '#view_compilation'),
                                                Yii::$app->urlManager->createUrl([
                                                    '/' . $moduleNameSondaggi . '/pubblicazione/compila',
                                                    'id' => $model->id,
                                                    'url' => $url,
                                                    'read' => true
                                                ]),
                                                [
                                                    'class' => 'read-more',
                                                    'title' => AmosSondaggi::t('amossondaggi', 'Compilato il ') . \Yii::$app->formatter->asDateTime($session->updated_at, 'humanalwaysdatetime') . ' ' . AmosSondaggi::t('amossondaggi', '#view_compilation'),
                                                    'data-toggle' => 'tooltip'
                                                ]
                                            );

                                        }

                                        if ($model->isCompilable())
                                          echo Html::a(
                                            AmosSondaggi::tHtml('amossondaggi', 'Ricompila'),
                                            Yii::$app->urlManager->createUrl([
                                                '/' . $moduleNameSondaggi . '/pubblicazione/compila',
                                                'id' => $model->id,
                                                'url' => $url,
                                            ]),
                                            [
                                                'data-confirm' => (($session->status != \open20\amos\sondaggi\models\SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA
                                                    && $session->status != null) ? AmosSondaggi::t(
                                                    'amossondaggi',
                                                    'Attenzione! La ri-compilazione rimetterà il sondaggio in stato Bozza'
                                                )
                                                    : null),
                                                'class' => 'btn btn-xs btn-primary',
                                                'title' => AmosSondaggi::t('amossondaggi', 'Ricompila'),
                                            ]
                                        );
                                    } else if ($model->isCompilable()) {
                                        echo Html::a(
                                            AmosSondaggi::tHtml('amossondaggi', 'Compila'),
                                            Yii::$app->urlManager->createUrl([
                                                '/' . $moduleNameSondaggi . '/pubblicazione/compila',
                                                'id' => $model->id,
                                                'url' => $url,
                                            ]),
                                            [
                                                'class' => 'btn btn-xs btn-primary',
                                                'title' => AmosSondaggi::t('amossondaggi', 'Compila'),
                                            ]
                                        );
                                    }
                                //}

                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
